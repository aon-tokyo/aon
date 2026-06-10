# AGENTS.md

## Project overview

Static multi-domain HTML monorepo for Japanese brand sites. Each domain folder (e.g. `aon.tokyo/`, `audiocafe.tokyo/`) contains:

- `index.html` — language picker (searchable grid, Google Translate links)
- `top/index.html` — main Japanese site content
- `world/index.html` — redirect to `/`

There is no build step, package manager, or backend. Python 3 is used only to serve files locally.

## Cursor Cloud specific instructions

### Running the app

Start a static HTTP server from the repository root:

```bash
python3 -m http.server 8080
```

Then open in a browser, for example:

- Language picker: http://localhost:8080/aon.tokyo/
- Main Japanese site: http://localhost:8080/aon.tokyo/top/

All eight domains follow the same layout: `aon.tokyo`, `audiocafe.tokyo`, `icpo.tokyo`, `ion.tokyo`, `nanosoft.jp`, `nasa.tokyo`, `raysoft.jp`.

Use a tmux session for long-running servers (e.g. session name `static-http-server`).

### Lint / test / build

None configured in this repository. Validation is manual: serve locally and verify pages return HTTP 200 and render in a browser.

### External runtime dependencies

Language-picker pages load YouTube background video, flag images (`flagcdn.com`), and Google Translate proxy links. A network connection is required for full behavior; local HTML/CSS/JS still serves without it.

### Optional Windows deploy script

`scripts/sync-github-and-local-mirror.ps1` pushes to GitHub and mirrors to a local path (`LOCAL_MIRROR_PATH`, default `E:\aon-mirror`). Not used in the Linux cloud VM.
