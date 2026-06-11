# rust-https-gui (PoC)

Linux向けのWeb GUI管理システムを想定した、Rust + Poem の最小API実装です。

## 目的

- ID + email + OTP による管理者ログイン
- ドメイン/サブドメイン設定のAPI化
- HTTPS有効化操作のワンクリック化（PoCでは状態管理のみ）
- 運用メンテナンス状況の可視化

## 起動

```bash
cargo run
```

デフォルト待受: `127.0.0.1:3000`

環境変数で変更:

```bash
APP_BIND=127.0.0.1:8080 cargo run
```

## API例

### 1. OTP発行

```bash
curl -sS -X POST http://127.0.0.1:3000/api/auth/request-otp \
  -H "Content-Type: application/json" \
  -d '{"login_id":"admin01","email":"ops@example.com"}'
```

> PoCではOTPコードをサーバーログに出力します（本番ではメール送信に差し替え）。

### 2. OTP検証（セッション取得）

```bash
curl -sS -X POST http://127.0.0.1:3000/api/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d '{"login_id":"admin01","email":"ops@example.com","otp_code":"123456"}'
```

### 3. ドメイン登録

```bash
curl -sS -X POST http://127.0.0.1:3000/api/domains \
  -H "Content-Type: application/json" \
  -d '{"session_token":"<TOKEN>","domain":"example.com"}'
```

### 4. サブドメイン設定

```bash
curl -sS -X POST http://127.0.0.1:3000/api/domains/example.com/subdomains \
  -H "Content-Type: application/json" \
  -d '{"session_token":"<TOKEN>","subdomain":"api","upstream":"127.0.0.1:4000"}'
```

### 5. HTTPS有効化

```bash
curl -sS -X POST http://127.0.0.1:3000/api/domains/example.com/https/enable \
  -H "Content-Type: application/json" \
  -d '{"session_token":"<TOKEN>"}'
```

### 6. メンテナンスサマリ

```bash
curl -sS "http://127.0.0.1:3000/api/maintenance/summary?session_token=<TOKEN>"
```

## テスト

```bash
cargo test
```

## 次に実装すべき本番機能

- OTPメール送信（SES/SendGrid/Postfix）
- 永続ストレージ（PostgreSQL + sqlx）
- 監査ログ（誰がいつ何を変更したか）
- Caddy APIまたはDNS API連携による証明書発行自動化
- GUIフロントエンド（Leptos / Yew / Svelte + API）
