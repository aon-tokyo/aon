#!/usr/bin/env bash
set -euo pipefail
ROOT="$(cd "$(dirname "$0")" && pwd)"
REPO_ROOT="$(cd "$ROOT/../.." && pwd)"
echo "deploy-hub installer"
echo "  Repo: $REPO_ROOT"
echo "  Node: $(node --version 2>/dev/null || echo 'not found — install Node 18+')"

if ! command -v node >/dev/null 2>&1; then
  echo "Install Node.js from https://nodejs.org/ (LTS recommended)."
  exit 1
fi

echo "Installing deploy-sync dependencies..."
(cd "$REPO_ROOT/tools/deploy-sync" && npm install)

echo "Installing deploy-hub dependencies..."
(cd "$ROOT" && npm install)

BIN_DIR="${HOME}/.local/bin"
mkdir -p "$BIN_DIR"
WRAPPER="$BIN_DIR/deploy-hub"
cat > "$WRAPPER" <<EOF
#!/usr/bin/env bash
exec node "$ROOT/cli.js" "\$@"
EOF
chmod +x "$WRAPPER"
echo "Symlink-style wrapper: $WRAPPER"
echo "Add to PATH if needed: export PATH=\"\$HOME/.local/bin:\$PATH\""

DS="$BIN_DIR/deploy-sync"
cat > "$DS" <<EOF
#!/usr/bin/env bash
exec node "$REPO_ROOT/tools/deploy-sync/cli.js" "\$@"
EOF
chmod +x "$DS"
echo "Also: $DS"

echo "Done. Run: deploy-hub check"
