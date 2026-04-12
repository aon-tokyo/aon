# deploy-sync

CLI tool: **GitHub** (`git push`) and/or **incremental upload** to a rental server over **FTP** or **SFTP**.

Behavior matches [docs/deployment-sync-app-spec.md](../../docs/deployment-sync-app-spec.md):

- Server upload is **incremental** (new/changed files only). Files removed from the repo are **not** deleted on the server by default.
- Optional **mirror delete** requires `server.deleteRemoved: true` and `--i-understand-delete-on-server`.

## Install

```bash
cd tools/deploy-sync
npm install
```

Run via `npx` from repo root (after install):

```bash
node tools/deploy-sync/cli.js --help
```

Or link globally: `npm link` inside `tools/deploy-sync`, then `deploy-sync`.

## Config

Create `deploy.config.yaml` in your project root (or pass `--config`).

Copy from `deploy.config.example.yaml` and add secrets via `deploy.local.yaml` (gitignored) or environment variables `${VAR_NAME}`.

## Examples

```bash
# Dry-run: list git commands and files to upload
deploy-sync --dry-run

# Push to GitHub only
deploy-sync --target github

# Upload to server only (incremental)
deploy-sync --target server

# Full upload every time (no manifest skip) — for rental hosts weak to incremental
deploy-sync --target server --full-upload
# or in YAML: server.uploadMode: full

# Git-diff-aligned upload (only files that git reports changed, ∩ deploy paths)
deploy-sync --target server --upload-mode git
# YAML: server.uploadMode: git, server.gitDiffMode: ahead | working | against

# Both (git push first, then incremental SFTP/FTP)
deploy-sync --target both
```

### Rental server quirks

- **`server.uploadMode: full`** or **`--full-upload`**: uploads every matched file every run (still does not delete on server unless mirror flags). Use when the host does not behave well with “skip unchanged”.
- **`server.ftpCompat`**: `passiveIpv4Only: true` (some shared hosts), `uploadRetries`, `delayBetweenUploadsMs`, `retryDelayMs`.
- **`server.sftpCompat`**: same retries/delays for SFTP.

## Requirements

- Node.js 18+
- `git` in PATH for GitHub targets
