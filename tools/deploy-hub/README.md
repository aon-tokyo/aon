# deploy-hub

Multi-profile wrapper for **[deploy-sync](../deploy-sync/README.md)** (Git push + FTP/SFTP), with:

- **Up to 200** server profiles (`deploy.config.yaml` per profile)
- **Backup** of `store.json` + all referenced configs to **local paths** (NAS / Google Drive mount / any folder)
- **Interactive menu** (TTY) or CLI
- **Environment check** — prompts to install **Git** if missing (Node required)

This does **not** embed Google Drive API; mount Drive as a folder and add that path as a backup destination.

## Install

From repo root:

**Linux / macOS**

```bash
chmod +x tools/deploy-hub/install.sh
./tools/deploy-hub/install.sh
```

**Windows (PowerShell)**

```powershell
.\tools\deploy-hub\install.ps1
```

Or manually:

```bash
cd tools/deploy-sync && npm install
cd ../deploy-hub && npm install
```

## Cursor

- **Tasks**: Run **Terminal → Run Task** → `deploy-hub: interactive menu` or `deploy-hub: environment check (Git)`.
- Data: `.deploy-hub/store.json` (up to 200 profiles). Add to `.gitignore` if secrets inside.

## CLI

```bash
node tools/deploy-hub/cli.js check
node tools/deploy-hub/cli.js list
node tools/deploy-hub/cli.js add --id mysite --config deploy.config.yaml --backup /path/to/NAS/backup,/path/to/GDrive/backup
node tools/deploy-hub/cli.js backup
node tools/deploy-hub/cli.js deploy --id mysite -- --target both --dry-run
```

## Backup layout

Each run writes:

`<destination>/deploy-hub-backups/<timestamp>/store.json`  
`<destination>/deploy-hub-backups/<timestamp>/configs/*.yaml`  
Optional `deploy.local.yaml` if present next to store.

## Requirements

- **Node.js 18+**
- **Git** (for `deploy-sync` Git targets) — `deploy-hub check` verifies
