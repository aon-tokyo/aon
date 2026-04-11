# audiocafe.tokyo — static site (Vite + TypeScript)

This folder builds the language-selection page at `https://audiocafe.tokyo/` (parent directory `../index.html` and hashed assets). **LOLIPOP and similar hosts only need the uploaded static files** — no Node.js on the server.

## Commands

```bash
cd audiocafe.tokyo/web
npm install
npm run build
```

Output goes to `../` (the `audiocafe.tokyo/` directory that maps to the site root). `audiocafe.tokyo/top/` is not removed by the build (`emptyOutDir: false`).

## Develop locally

```bash
npm run dev
```

## Stack

- **Vite 6** — fast dev server and optimized static production bundles
- **TypeScript** — type-checking for `src/*.ts` (legacy YouTube logic stays in `youtube-bg.js`)

Next.js, WunderGraph, and similar **server** frameworks are intentionally not used so the same files work on shared PHP/static hosting.
