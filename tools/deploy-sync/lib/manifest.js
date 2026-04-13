import fs from "node:fs";

/**
 * Manifest: { "path/to/file": { sha256, size, mtimeMs } }
 */
export function loadManifest(manifestPath) {
  try {
    if (!fs.existsSync(manifestPath)) return {};
    const j = JSON.parse(fs.readFileSync(manifestPath, "utf8"));
    return j && typeof j === "object" ? j : {};
  } catch {
    return {};
  }
}

export function saveManifest(manifestPath, map) {
  const obj = {};
  for (const [k, v] of map) obj[k] = v;
  fs.writeFileSync(manifestPath, JSON.stringify(obj, null, 0), "utf8");
}

export function diffIncremental(previous, current) {
  const upload = [];
  const skipReason = [];
  for (const [rel, meta] of current) {
    const prev = previous[rel];
    if (!prev) {
      upload.push(rel);
      continue;
    }
    if (
      prev.sha256 !== meta.sha256 ||
      prev.size !== meta.size ||
      prev.mtimeMs !== meta.mtimeMs
    ) {
      upload.push(rel);
    } else {
      skipReason.push(rel);
    }
  }
  return { upload, skipReason };
}

/** Paths in previous but not in current — for optional mirror delete */
export function diffMirrorDeletes(previous, current) {
  const dels = [];
  for (const rel of Object.keys(previous)) {
    if (!current.has(rel)) dels.push(rel);
  }
  return dels;
}
