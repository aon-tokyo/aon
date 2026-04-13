import fs from "node:fs";
import path from "node:path";
import { MAX_PROFILES } from "./constants.js";

function copyFile(src, dest) {
  fs.mkdirSync(path.dirname(dest), { recursive: true });
  fs.copyFileSync(src, dest);
}

function copyDirRecursive(src, dest) {
  fs.mkdirSync(dest, { recursive: true });
  for (const name of fs.readdirSync(src, { withFileTypes: true })) {
    const s = path.join(src, name.name);
    const d = path.join(dest, name.name);
    if (name.isDirectory()) copyDirRecursive(s, d);
    else copyFile(s, d);
  }
}

/**
 * Backup store + referenced deploy configs to each local destination.
 * @param {object} storeData from loadStore
 * @param {string} storePath path to store.json
 */
export function runBackup(storeData, storePath) {
  const allDests = new Map();
  for (const p of storeData.profiles) {
    for (const bd of p.backupDestinations || []) {
      if (bd.type !== "local" || !bd.path) continue;
      const resolved = path.resolve(bd.path);
      allDests.set(resolved, true);
    }
  }
  if (allDests.size === 0) {
    console.log("deploy-hub: no backupDestinations configured on any profile.");
    return;
  }

  const stamp = new Date().toISOString().replace(/[:.]/g, "-");
  const storeDir = path.dirname(storePath);
  const hubRoot = path.join(storeDir, "..");

  for (const destRoot of allDests.keys()) {
    const sessionDir = path.join(destRoot, "deploy-hub-backups", stamp);
    fs.mkdirSync(sessionDir, { recursive: true });

    copyFile(storePath, path.join(sessionDir, "store.json"));

    const seen = new Set();
    for (const prof of storeData.profiles) {
      const cfg = prof.deployConfigPath;
      if (!cfg || !fs.existsSync(cfg)) continue;
      const base = path.basename(cfg);
      let targetName = base;
      let n = 0;
      while (seen.has(targetName)) {
        n++;
        targetName = `${prof.id}_${n}_${base}`;
      }
      seen.add(targetName);
      copyFile(cfg, path.join(sessionDir, "configs", targetName));
    }

    try {
      const localYaml = path.join(hubRoot, "deploy.local.yaml");
      if (fs.existsSync(localYaml)) {
        copyFile(localYaml, path.join(sessionDir, "deploy.local.yaml"));
      }
    } catch {
      /* ignore */
    }

    console.log(`deploy-hub: backup written: ${sessionDir}`);
  }
}
