# Poem on ConoHa VPS with automatic HTTPS (minimal setup)

This guide is focused on one goal: run a Rust Poem app on ConoHa VPS and add HTTPS (`https://`) with minimal operational overhead.

The architecture is:

- Poem app listens on `127.0.0.1:3000`
- Caddy listens on `:80` and `:443`
- Caddy automatically issues and renews Let's Encrypt certificates
- systemd keeps the app running

## 1) Prerequisites

- Ubuntu-based ConoHa VPS
- Domain A record pointing to the VPS public IP (for example: `api.example.com`)
- SSH key login to VPS
- Rust toolchain installed on your local machine (Cursor dev environment)

## 2) Build your Poem app locally

From your Poem project root:

```bash
cargo build --release
```

The binary will be in `target/release/<your_binary_name>`.

## 3) Prepare the VPS (one-time)

SSH into VPS:

```bash
ssh ubuntu@<VPS_IP>
```

Install required packages:

```bash
sudo apt update
sudo apt install -y caddy ufw
```

Create app user and directories:

```bash
sudo useradd --system --home /opt/poem-app --shell /usr/sbin/nologin poemapp || true
sudo mkdir -p /opt/poem-app/bin /opt/poem-app/config
sudo chown -R poemapp:poemapp /opt/poem-app
```

Allow firewall ports:

```bash
sudo ufw allow OpenSSH
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw --force enable
```

## 4) Configure service and reverse proxy

Copy templates from this repository:

- `deploy/poem-conoha/poem-app.service` -> `/etc/systemd/system/poem-app.service`
- `deploy/poem-conoha/Caddyfile` -> `/etc/caddy/Caddyfile`

Before copying, edit placeholders:

- `__DOMAIN__` -> your domain (`api.example.com`)
- `__APP_ENV_FILE__` -> `/opt/poem-app/config/.env`
- `__APP_EXEC__` -> `/opt/poem-app/bin/app`

## 5) Upload app files

From local machine, copy release binary and env:

```bash
scp target/release/<your_binary_name> ubuntu@<VPS_IP>:/tmp/app
scp .env.prod ubuntu@<VPS_IP>:/tmp/.env
```

On VPS:

```bash
sudo mv /tmp/app /opt/poem-app/bin/app
sudo mv /tmp/.env /opt/poem-app/config/.env
sudo chown poemapp:poemapp /opt/poem-app/bin/app /opt/poem-app/config/.env
sudo chmod 750 /opt/poem-app/bin/app
sudo chmod 640 /opt/poem-app/config/.env
```

## 6) Enable and start services

```bash
sudo systemctl daemon-reload
sudo systemctl enable --now poem-app
sudo systemctl restart poem-app

sudo systemctl enable --now caddy
sudo caddy validate --config /etc/caddy/Caddyfile
sudo systemctl reload caddy
```

Check status:

```bash
systemctl status poem-app --no-pager
systemctl status caddy --no-pager
```

## 7) Verify HTTPS

From local machine:

```bash
curl -I https://__DOMAIN__/
```

You should get a valid TLS response (HTTP status depends on your app route).

## 8) Daily operations

Logs:

```bash
journalctl -u poem-app -f
journalctl -u caddy -f
```

Deploy new build:

1. `cargo build --release`
2. Upload new binary
3. `sudo systemctl restart poem-app`
4. Check `journalctl -u poem-app -n 100 --no-pager`

## 9) Poem runtime notes

- Bind only to localhost in production:
  - `127.0.0.1:3000` (not `0.0.0.0:3000`)
- Use env vars for config (database URL, secrets, port)
- Keep TLS termination in Caddy for simplicity

This pattern is robust for small to medium services and keeps HTTPS management nearly hands-off.
