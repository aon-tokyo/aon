import fs from "node:fs";
import path from "node:path";
import { MAX_PROFILES, STORE_VERSION } from "./constants.js";

function defaultStore() {
  return { version: STORE_VERSION, profiles: [] };
}

export function getDefaultStorePath(cwd) {
  return path.join(cwd, ".deploy-hub", "store.json");
}

export function loadStore(storePath) {
  if (!fs.existsSync(storePath)) {
    const dir = path.dirname(storePath);
    fs.mkdirSync(dir, { recursive: true });
    const empty = defaultStore();
    fs.writeFileSync(storePath, JSON.stringify(empty, null, 2), "utf8");
    return empty;
  }
  const raw = fs.readFileSync(storePath, "utf8");
  const data = JSON.parse(raw);
  if (!data.profiles || !Array.isArray(data.profiles)) data.profiles = [];
  return data;
}

export function saveStore(storePath, data) {
  if (data.profiles.length > MAX_PROFILES) {
    throw new Error(`Maximum ${MAX_PROFILES} profiles allowed`);
  }
  const dir = path.dirname(storePath);
  fs.mkdirSync(dir, { recursive: true });
  data.version = STORE_VERSION;
  fs.writeFileSync(storePath, JSON.stringify(data, null, 2), "utf8");
}

export function addProfile(storePath, profile) {
  const data = loadStore(storePath);
  if (data.profiles.length >= MAX_PROFILES) {
    throw new Error(`Maximum ${MAX_PROFILES} profiles reached`);
  }
  const id = profile.id || `p${Date.now()}`;
  if (data.profiles.some((p) => p.id === id)) {
    throw new Error(`Profile id already exists: ${id}`);
  }
  const repoRoot = path.join(path.dirname(storePath), "..");
  const deployPath = path.isAbsolute(profile.deployConfigPath)
    ? profile.deployConfigPath
    : path.resolve(repoRoot, profile.deployConfigPath);
  data.profiles.push({
    id,
    label: profile.label || id,
    deployConfigPath: deployPath,
    backupDestinations: profile.backupDestinations || [],
  });
  saveStore(storePath, data);
  return data;
}
