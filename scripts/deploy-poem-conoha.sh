#!/usr/bin/env bash
set -euo pipefail

# Minimal deploy helper:
# 1) builds release binary
# 2) uploads binary + env to VPS
# 3) installs files
# 4) restarts systemd service
#
# Usage:
#   REMOTE_HOST=ubuntu@1.2.3.4 \
#   APP_BINARY_NAME=myapp \
#   LOCAL_ENV_FILE=.env.prod \
#   ./scripts/deploy-poem-conoha.sh

: "${REMOTE_HOST:?REMOTE_HOST is required, e.g. ubuntu@1.2.3.4}"
: "${APP_BINARY_NAME:?APP_BINARY_NAME is required, e.g. myapp}"
: "${LOCAL_ENV_FILE:?LOCAL_ENV_FILE is required, e.g. .env.prod}"

if [[ ! -f "$LOCAL_ENV_FILE" ]]; then
  echo "Error: env file not found: $LOCAL_ENV_FILE"
  exit 1
fi

echo "==> Building release binary"
cargo build --release

if [[ ! -f "target/release/$APP_BINARY_NAME" ]]; then
  echo "Error: binary not found at target/release/$APP_BINARY_NAME"
  exit 1
fi

echo "==> Uploading artifacts to VPS"
scp "target/release/$APP_BINARY_NAME" "$REMOTE_HOST:/tmp/app"
scp "$LOCAL_ENV_FILE" "$REMOTE_HOST:/tmp/.env"

echo "==> Installing artifacts on VPS"
ssh "$REMOTE_HOST" "sudo mkdir -p /opt/poem-app/bin /opt/poem-app/config"
ssh "$REMOTE_HOST" "sudo mv /tmp/app /opt/poem-app/bin/app"
ssh "$REMOTE_HOST" "sudo mv /tmp/.env /opt/poem-app/config/.env"
ssh "$REMOTE_HOST" "sudo chown poemapp:poemapp /opt/poem-app/bin/app /opt/poem-app/config/.env"
ssh "$REMOTE_HOST" "sudo chmod 750 /opt/poem-app/bin/app"
ssh "$REMOTE_HOST" "sudo chmod 640 /opt/poem-app/config/.env"

echo "==> Restarting service"
ssh "$REMOTE_HOST" "sudo systemctl restart poem-app"
ssh "$REMOTE_HOST" "sudo systemctl status poem-app --no-pager"

echo "Done."
