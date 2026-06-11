use std::collections::HashMap;
use std::sync::{Arc, RwLock};

use chrono::{DateTime, Duration, Utc};
use poem::http::StatusCode;
use poem::listener::TcpListener;
use poem::web::{Json, Path, Query};
use poem::{get, handler, post, EndpointExt, IntoResponse, Response, Route, Server};
use rand::Rng;
use serde::{Deserialize, Serialize};
use uuid::Uuid;

const OTP_TTL_MINUTES: i64 = 10;
const SESSION_TTL_HOURS: i64 = 12;
const OTP_MAX_ATTEMPTS: u8 = 5;

#[derive(Clone, Default)]
struct AppState {
    store: Arc<RwLock<Store>>,
}

#[derive(Default)]
struct Store {
    otp_challenges: HashMap<String, OtpChallenge>,
    sessions: HashMap<String, SessionInfo>,
    domains: HashMap<String, DomainConfig>,
}

#[derive(Clone)]
struct OtpChallenge {
    code: String,
    expires_at: DateTime<Utc>,
    attempts_left: u8,
}

#[derive(Clone)]
struct SessionInfo {
    login_id: String,
    email: String,
    expires_at: DateTime<Utc>,
}

#[derive(Clone, Debug, Serialize, Deserialize)]
struct DomainConfig {
    domain: String,
    tls_enabled: bool,
    tls_provider: String,
    subdomains: Vec<SubdomainConfig>,
    updated_at: DateTime<Utc>,
}

#[derive(Clone, Debug, Serialize, Deserialize)]
struct SubdomainConfig {
    subdomain: String,
    upstream: String,
    tls_enabled: bool,
}

#[derive(Debug, Serialize)]
struct ErrorBody {
    error: String,
}

#[derive(Debug)]
struct ApiError {
    status: StatusCode,
    message: String,
}

impl ApiError {
    fn bad_request(message: impl Into<String>) -> Self {
        Self {
            status: StatusCode::BAD_REQUEST,
            message: message.into(),
        }
    }

    fn unauthorized(message: impl Into<String>) -> Self {
        Self {
            status: StatusCode::UNAUTHORIZED,
            message: message.into(),
        }
    }

    fn not_found(message: impl Into<String>) -> Self {
        Self {
            status: StatusCode::NOT_FOUND,
            message: message.into(),
        }
    }
}

impl IntoResponse for ApiError {
    fn into_response(self) -> Response {
        (
            self.status,
            Json(ErrorBody {
                error: self.message,
            }),
        )
            .into_response()
    }
}

fn to_poem_error(err: ApiError) -> poem::Error {
    poem::Error::from_response(err.into_response())
}

#[derive(Debug, Deserialize)]
struct RequestOtpInput {
    login_id: String,
    email: String,
}

#[derive(Debug, Serialize)]
struct RequestOtpOutput {
    message: String,
    expires_at: DateTime<Utc>,
}

#[derive(Debug, Deserialize)]
struct VerifyOtpInput {
    login_id: String,
    email: String,
    otp_code: String,
}

#[derive(Debug, Serialize)]
struct VerifyOtpOutput {
    session_token: String,
    expires_at: DateTime<Utc>,
}

#[derive(Debug, Deserialize)]
struct SessionInput {
    session_token: String,
}

#[derive(Debug, Deserialize)]
struct DomainCreateInput {
    session_token: String,
    domain: String,
}

#[derive(Debug, Deserialize)]
struct SubdomainUpsertInput {
    session_token: String,
    subdomain: String,
    upstream: String,
}

#[derive(Debug, Deserialize)]
struct HttpsEnableInput {
    session_token: String,
}

#[derive(Debug, Serialize)]
struct MaintenanceSummary {
    total_domains: usize,
    tls_enabled_domains: usize,
    tls_pending_domains: Vec<String>,
    active_sessions: usize,
}

#[derive(Debug, Serialize)]
struct HealthResponse {
    status: &'static str,
    now: DateTime<Utc>,
}

impl AppState {
    fn request_otp(&self, input: RequestOtpInput) -> Result<RequestOtpOutput, ApiError> {
        validate_login_id(&input.login_id)?;
        validate_email(&input.email)?;

        let otp_code = format!("{:06}", rand::thread_rng().gen_range(0..1_000_000));
        let expires_at = Utc::now() + Duration::minutes(OTP_TTL_MINUTES);
        let key = otp_key(&input.login_id, &input.email);

        let challenge = OtpChallenge {
            code: otp_code.clone(),
            expires_at,
            attempts_left: OTP_MAX_ATTEMPTS,
        };

        let mut store = self.store.write().expect("store lock poisoned");
        store.otp_challenges.insert(key, challenge);

        // Placeholder transport. Replace with SES/SendGrid/postfix integration in production.
        println!(
            "[OTP DEBUG] login_id={} email={} code={}",
            input.login_id, input.email, otp_code
        );

        Ok(RequestOtpOutput {
            message: "OTP sent to email (development mode: check server logs)".to_string(),
            expires_at,
        })
    }

    fn verify_otp(&self, input: VerifyOtpInput) -> Result<VerifyOtpOutput, ApiError> {
        validate_login_id(&input.login_id)?;
        validate_email(&input.email)?;
        if input.otp_code.len() != 6 || !input.otp_code.chars().all(|c| c.is_ascii_digit()) {
            return Err(ApiError::bad_request("otp_code must be a 6 digit number"));
        }

        let key = otp_key(&input.login_id, &input.email);
        let mut store = self.store.write().expect("store lock poisoned");
        let challenge = store
            .otp_challenges
            .get_mut(&key)
            .ok_or_else(|| ApiError::unauthorized("OTP challenge not found"))?;

        if challenge.expires_at < Utc::now() {
            store.otp_challenges.remove(&key);
            return Err(ApiError::unauthorized("OTP challenge expired"));
        }

        if challenge.otp_mismatch(&input.otp_code) {
            if challenge.attempts_left > 0 {
                challenge.attempts_left -= 1;
            }
            if challenge.attempts_left == 0 {
                store.otp_challenges.remove(&key);
                return Err(ApiError::unauthorized(
                    "OTP invalid and max attempts exceeded",
                ));
            }
            return Err(ApiError::unauthorized("OTP invalid"));
        }

        store.otp_challenges.remove(&key);
        let session_token = Uuid::new_v4().to_string();
        let expires_at = Utc::now() + Duration::hours(SESSION_TTL_HOURS);
        let session_info = SessionInfo {
            login_id: input.login_id,
            email: input.email,
            expires_at,
        };
        store.sessions.insert(session_token.clone(), session_info);

        Ok(VerifyOtpOutput {
            session_token,
            expires_at,
        })
    }

    fn require_session(&self, token: &str) -> Result<SessionInfo, ApiError> {
        if token.trim().is_empty() {
            return Err(ApiError::unauthorized("session_token is required"));
        }

        let mut store = self.store.write().expect("store lock poisoned");
        let session = store
            .sessions
            .get(token)
            .cloned()
            .ok_or_else(|| ApiError::unauthorized("invalid session_token"))?;

        if session.expires_at < Utc::now() {
            store.sessions.remove(token);
            return Err(ApiError::unauthorized("session expired"));
        }

        Ok(session)
    }

    fn list_domains(&self, session_token: &str) -> Result<Vec<DomainConfig>, ApiError> {
        self.require_session(session_token)?;
        let store = self.store.read().expect("store lock poisoned");
        let mut domains = store.domains.values().cloned().collect::<Vec<_>>();
        domains.sort_by(|a, b| a.domain.cmp(&b.domain));
        Ok(domains)
    }

    fn create_domain(&self, input: DomainCreateInput) -> Result<DomainConfig, ApiError> {
        self.require_session(&input.session_token)?;
        validate_domain(&input.domain)?;

        let mut store = self.store.write().expect("store lock poisoned");
        if store.domains.contains_key(&input.domain) {
            return Err(ApiError::bad_request("domain already exists"));
        }

        let config = DomainConfig {
            domain: input.domain.clone(),
            tls_enabled: false,
            tls_provider: "letsencrypt-via-caddy".to_string(),
            subdomains: Vec::new(),
            updated_at: Utc::now(),
        };
        store.domains.insert(input.domain, config.clone());
        Ok(config)
    }

    fn upsert_subdomain(
        &self,
        domain: String,
        input: SubdomainUpsertInput,
    ) -> Result<DomainConfig, ApiError> {
        self.require_session(&input.session_token)?;
        validate_domain(&domain)?;
        validate_subdomain(&input.subdomain)?;
        validate_upstream(&input.upstream)?;

        let mut store = self.store.write().expect("store lock poisoned");
        let config = store
            .domains
            .get_mut(&domain)
            .ok_or_else(|| ApiError::not_found("domain not found"))?;

        if let Some(existing) = config
            .subdomains
            .iter_mut()
            .find(|item| item.subdomain == input.subdomain)
        {
            existing.upstream = input.upstream.clone();
            existing.tls_enabled = config.tls_enabled;
        } else {
            config.subdomains.push(SubdomainConfig {
                subdomain: input.subdomain,
                upstream: input.upstream,
                tls_enabled: config.tls_enabled,
            });
        }
        config.updated_at = Utc::now();
        Ok(config.clone())
    }

    fn enable_https(
        &self,
        domain: String,
        input: HttpsEnableInput,
    ) -> Result<DomainConfig, ApiError> {
        self.require_session(&input.session_token)?;
        validate_domain(&domain)?;

        let mut store = self.store.write().expect("store lock poisoned");
        let config = store
            .domains
            .get_mut(&domain)
            .ok_or_else(|| ApiError::not_found("domain not found"))?;
        config.tls_enabled = true;
        for item in &mut config.subdomains {
            item.tls_enabled = true;
        }
        config.updated_at = Utc::now();
        Ok(config.clone())
    }

    fn maintenance_summary(&self, session_token: &str) -> Result<MaintenanceSummary, ApiError> {
        let session = self.require_session(session_token)?;
        let store = self.store.read().expect("store lock poisoned");
        let total_domains = store.domains.len();
        let tls_enabled_domains = store.domains.values().filter(|d| d.tls_enabled).count();
        let mut tls_pending_domains = store
            .domains
            .values()
            .filter(|d| !d.tls_enabled)
            .map(|d| d.domain.clone())
            .collect::<Vec<_>>();
        tls_pending_domains.sort();

        let active_sessions = store
            .sessions
            .values()
            .filter(|s| s.expires_at > Utc::now())
            .count();

        println!(
            "[AUDIT] maintenance summary requested by login_id={} email={}",
            session.login_id, session.email
        );

        Ok(MaintenanceSummary {
            total_domains,
            tls_enabled_domains,
            tls_pending_domains,
            active_sessions,
        })
    }
}

impl OtpChallenge {
    fn otp_mismatch(&self, code: &str) -> bool {
        self.code != code
    }
}

fn otp_key(login_id: &str, email: &str) -> String {
    format!(
        "{}|{}",
        login_id.trim().to_lowercase(),
        email.trim().to_lowercase()
    )
}

fn validate_login_id(login_id: &str) -> Result<(), ApiError> {
    let trimmed = login_id.trim();
    if trimmed.len() < 3 {
        return Err(ApiError::bad_request(
            "login_id must be at least 3 characters",
        ));
    }
    if !trimmed
        .chars()
        .all(|c| c.is_ascii_alphanumeric() || c == '_' || c == '-')
    {
        return Err(ApiError::bad_request(
            "login_id may contain only letters, numbers, '_' and '-'",
        ));
    }
    Ok(())
}

fn validate_email(email: &str) -> Result<(), ApiError> {
    let trimmed = email.trim();
    let parts = trimmed.split('@').collect::<Vec<_>>();
    if parts.len() != 2 || parts[0].is_empty() || !parts[1].contains('.') {
        return Err(ApiError::bad_request("email format is invalid"));
    }
    Ok(())
}

fn validate_domain(domain: &str) -> Result<(), ApiError> {
    let trimmed = domain.trim().to_lowercase();
    if trimmed.len() < 4 || !trimmed.contains('.') {
        return Err(ApiError::bad_request("domain format is invalid"));
    }
    if trimmed.starts_with('.') || trimmed.ends_with('.') || trimmed.contains("..") {
        return Err(ApiError::bad_request("domain format is invalid"));
    }
    if !trimmed
        .chars()
        .all(|c| c.is_ascii_lowercase() || c.is_ascii_digit() || c == '-' || c == '.')
    {
        return Err(ApiError::bad_request(
            "domain contains unsupported characters",
        ));
    }
    Ok(())
}

fn validate_subdomain(subdomain: &str) -> Result<(), ApiError> {
    let trimmed = subdomain.trim().to_lowercase();
    if trimmed.is_empty() || trimmed == "*" {
        return Err(ApiError::bad_request("subdomain label is invalid"));
    }
    if trimmed.starts_with('-') || trimmed.ends_with('-') || trimmed.contains("..") {
        return Err(ApiError::bad_request("subdomain label is invalid"));
    }
    if !trimmed
        .chars()
        .all(|c| c.is_ascii_lowercase() || c.is_ascii_digit() || c == '-' || c == '.')
    {
        return Err(ApiError::bad_request(
            "subdomain contains unsupported characters",
        ));
    }
    Ok(())
}

fn validate_upstream(upstream: &str) -> Result<(), ApiError> {
    let trimmed = upstream.trim();
    if trimmed.is_empty() || !trimmed.contains(':') || trimmed.contains(' ') {
        return Err(ApiError::bad_request(
            "upstream must be host:port, for example 127.0.0.1:3000",
        ));
    }
    Ok(())
}

#[handler]
async fn health() -> Json<HealthResponse> {
    Json(HealthResponse {
        status: "ok",
        now: Utc::now(),
    })
}

#[handler]
async fn request_otp(
    state: poem::web::Data<&AppState>,
    Json(input): Json<RequestOtpInput>,
) -> poem::Result<Json<RequestOtpOutput>> {
    let output = state.request_otp(input).map_err(to_poem_error)?;
    Ok(Json(output))
}

#[handler]
async fn verify_otp(
    state: poem::web::Data<&AppState>,
    Json(input): Json<VerifyOtpInput>,
) -> poem::Result<Json<VerifyOtpOutput>> {
    let output = state.verify_otp(input).map_err(to_poem_error)?;
    Ok(Json(output))
}

#[handler]
async fn list_domains(
    state: poem::web::Data<&AppState>,
    Query(input): Query<SessionInput>,
) -> poem::Result<Json<Vec<DomainConfig>>> {
    let list = state
        .list_domains(&input.session_token)
        .map_err(to_poem_error)?;
    Ok(Json(list))
}

#[handler]
async fn create_domain(
    state: poem::web::Data<&AppState>,
    Json(input): Json<DomainCreateInput>,
) -> poem::Result<Json<DomainConfig>> {
    let config = state.create_domain(input).map_err(to_poem_error)?;
    Ok(Json(config))
}

#[handler]
async fn upsert_subdomain(
    state: poem::web::Data<&AppState>,
    Path(domain): Path<String>,
    Json(input): Json<SubdomainUpsertInput>,
) -> poem::Result<Json<DomainConfig>> {
    let config = state
        .upsert_subdomain(domain, input)
        .map_err(to_poem_error)?;
    Ok(Json(config))
}

#[handler]
async fn enable_https(
    state: poem::web::Data<&AppState>,
    Path(domain): Path<String>,
    Json(input): Json<HttpsEnableInput>,
) -> poem::Result<Json<DomainConfig>> {
    let config = state.enable_https(domain, input).map_err(to_poem_error)?;
    Ok(Json(config))
}

#[handler]
async fn maintenance_summary(
    state: poem::web::Data<&AppState>,
    Query(input): Query<SessionInput>,
) -> poem::Result<Json<MaintenanceSummary>> {
    let summary = state
        .maintenance_summary(&input.session_token)
        .map_err(to_poem_error)?;
    Ok(Json(summary))
}

#[tokio::main]
async fn main() -> Result<(), std::io::Error> {
    let bind_addr = std::env::var("APP_BIND").unwrap_or_else(|_| "127.0.0.1:3000".to_string());
    let state = AppState::default();

    let app = Route::new()
        .at("/api/health", get(health))
        .at("/api/auth/request-otp", post(request_otp))
        .at("/api/auth/verify-otp", post(verify_otp))
        .at("/api/domains", get(list_domains).post(create_domain))
        .at("/api/domains/:domain/subdomains", post(upsert_subdomain))
        .at("/api/domains/:domain/https/enable", post(enable_https))
        .at("/api/maintenance/summary", get(maintenance_summary))
        .data(state);

    println!("Starting rust-https-gui API on {bind_addr}");
    Server::new(TcpListener::bind(bind_addr)).run(app).await
}

#[cfg(test)]
mod tests {
    use super::*;

    fn seed_session(state: &AppState) -> String {
        state
            .verify_otp(VerifyOtpInput {
                login_id: "admin01".to_string(),
                email: "ops@example.com".to_string(),
                otp_code: {
                    state
                        .request_otp(RequestOtpInput {
                            login_id: "admin01".to_string(),
                            email: "ops@example.com".to_string(),
                        })
                        .expect("otp request should work");
                    let store = state.store.read().expect("lock should work");
                    store
                        .otp_challenges
                        .get(&otp_key("admin01", "ops@example.com"))
                        .expect("challenge should exist")
                        .code
                        .clone()
                },
            })
            .expect("otp verify should work")
            .session_token
    }

    #[test]
    fn otp_auth_flow_works() {
        let state = AppState::default();
        state
            .request_otp(RequestOtpInput {
                login_id: "admin01".to_string(),
                email: "ops@example.com".to_string(),
            })
            .expect("otp request should work");

        let otp_code = {
            let store = state.store.read().expect("lock should work");
            store
                .otp_challenges
                .get(&otp_key("admin01", "ops@example.com"))
                .expect("challenge should exist")
                .code
                .clone()
        };

        let verify = state
            .verify_otp(VerifyOtpInput {
                login_id: "admin01".to_string(),
                email: "ops@example.com".to_string(),
                otp_code,
            })
            .expect("otp verify should work");

        assert!(!verify.session_token.is_empty());
    }

    #[test]
    fn domain_and_https_flow_works() {
        let state = AppState::default();
        let session_token = seed_session(&state);

        let domain = state
            .create_domain(DomainCreateInput {
                session_token: session_token.clone(),
                domain: "example.com".to_string(),
            })
            .expect("domain create should work");
        assert!(!domain.tls_enabled);

        let domain = state
            .upsert_subdomain(
                "example.com".to_string(),
                SubdomainUpsertInput {
                    session_token: session_token.clone(),
                    subdomain: "api".to_string(),
                    upstream: "127.0.0.1:4000".to_string(),
                },
            )
            .expect("subdomain upsert should work");
        assert_eq!(domain.subdomains.len(), 1);
        assert!(!domain.subdomains[0].tls_enabled);

        let domain = state
            .enable_https(
                "example.com".to_string(),
                HttpsEnableInput { session_token },
            )
            .expect("https enable should work");
        assert!(domain.tls_enabled);
        assert!(domain.subdomains[0].tls_enabled);
    }

    #[test]
    fn invalid_session_is_rejected() {
        let state = AppState::default();
        let result = state.list_domains("invalid-token");
        assert!(result.is_err());
    }
}
