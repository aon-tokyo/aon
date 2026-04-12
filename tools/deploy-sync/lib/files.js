import fg from "fast-glob";
import fs from "node:fs";
import path from "node:path";
import crypto from "node:crypto";

const DEFAULT_EXCLUDE = [
  "**/.git/**",
  "**/node_modules/**",
  "**/.env",
  "**/.env.*",
  "**/.deploy-manifest.json",
  "**/deploy.local.yaml",
  "**/deploy.local.yml",
];

/**
 * List files relative to repoRoot matching include/exclude globs.
 */
export async function collectFiles(repoRoot, pathsCfg) {
  const include = pathsCfg.include?.length
    ? pathsCfg.include
    : ["**/*"];
  const exclude = [...DEFAULT_EXCLUDE, ...(pathsCfg.exclude || [])];

  const entries = await fg(include, {
    cwd: repoRoot,
    ignore: exclude,
    dot: true,
    onlyFiles: true,
    unique: true,
  });

  return entries.map((p) => p.replace(/\\/g, "/"));
}

export async function hashFile(absPath) {
  const buf = await fs.promises.readFile(absPath);
  const h = crypto.createHash("sha256").update(buf).digest("hex");
  const st = await fs.promises.stat(absPath);
  return {
    sha256: h,
    size: st.size,
    mtimeMs: Math.floor(st.mtimeMs),
  };
}

export function posixJoinRemote(remoteRoot, relPath) {
  const r = String(remoteRoot || "").replace(/\\/g, "/").replace(/\/+$/, "");
  const p = relPath.replace(/\\/g, "/").replace(/^\/+/, "");
  return r ? `${r}/${p}` : p;
}
