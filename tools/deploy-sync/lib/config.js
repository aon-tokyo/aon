import fs from "node:fs";
import path from "node:path";
import YAML from "yaml";

const VAR_RE = /\$\{([A-Za-z_][A-Za-z0-9_]*)\}/g;

function expandEnv(str) {
  if (typeof str !== "string") return str;
  return str.replace(VAR_RE, (_, name) => {
    const v = process.env[name];
    return v !== undefined ? v : "";
  });
}

function walkExpand(obj) {
  if (obj === null || obj === undefined) return obj;
  if (typeof obj === "string") return expandEnv(obj);
  if (Array.isArray(obj)) return obj.map(walkExpand);
  if (typeof obj === "object") {
    const o = {};
    for (const k of Object.keys(obj)) o[k] = walkExpand(obj[k]);
    return o;
  }
  return obj;
}

export function loadConfig(configPath, cwd) {
  const raw = fs.readFileSync(configPath, "utf8");
  let data;
  if (configPath.endsWith(".json")) {
    data = JSON.parse(raw);
  } else {
    data = YAML.parse(raw);
  }
  data = walkExpand(data);

  const localPath = path.join(path.dirname(configPath), "deploy.local.yaml");
  const localPathYml = path.join(path.dirname(configPath), "deploy.local.yml");
  let local = {};
  if (fs.existsSync(localPath)) {
    local = YAML.parse(fs.readFileSync(localPath, "utf8")) || {};
  } else if (fs.existsSync(localPathYml)) {
    local = YAML.parse(fs.readFileSync(localPathYml, "utf8")) || {};
  }
  const merged = deepMerge(data, walkExpand(local));
  merged._configDir = path.dirname(path.resolve(configPath));
  merged._cwd = cwd;
  return merged;
}

function deepMerge(a, b) {
  if (!b || typeof b !== "object") return { ...a };
  const out = { ...a };
  for (const k of Object.keys(b)) {
    if (
      b[k] &&
      typeof b[k] === "object" &&
      !Array.isArray(b[k]) &&
      out[k] &&
      typeof out[k] === "object" &&
      !Array.isArray(out[k])
    ) {
      out[k] = deepMerge(out[k], b[k]);
    } else {
      out[k] = b[k];
    }
  }
  return out;
}
