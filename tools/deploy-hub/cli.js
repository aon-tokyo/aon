#!/usr/bin/env node
/**
 * deploy-hub — multi-profile launcher, backups, Cursor-friendly
 */
import minimist from "minimist";
import { spawn } from "node:child_process";
import path from "node:path";
import fs from "node:fs";
import { fileURLToPath } from "node:url";
import readline from "node:readline";

import { getDefaultStorePath, loadStore, addProfile } from "./lib/store.js";
import { MAX_PROFILES } from "./lib/constants.js";
import { printPreflightReport, checkGit } from "./lib/preflight.js";
import { runBackup } from "./lib/backup.js";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const deploySyncCli = path.join(__dirname, "..", "deploy-sync", "cli.js");

function printHelp() {
  console.log(`
deploy-hub — up to ${MAX_PROFILES} profiles, backup store to local/NAS paths, run deploy-sync

Usage:
  deploy-hub [command] [options]

Commands:
  (default)       Interactive menu (TTY) or show help
  check           Show Node and Git; exit 1 if Git missing
  list            List profiles
  add             --id ID --config PATH [--label L] [--backup path1,path2]
  backup          Copy store + deploy configs to all backup destinations
  deploy          --id ID [-- ...deploy-sync args]

Options:
  --store PATH    store.json location (default: .deploy-hub/store.json in cwd)

Examples:
  deploy-hub check
  deploy-hub add --id prod --label Production --config deploy.config.yaml --backup /Volumes/NAS/hub,/home/me/GDrive/backup
  deploy-hub backup
  deploy-hub deploy --id prod -- --target both --dry-run
`);
}

function runDeploySync(cwd, extraArgs) {
  return new Promise((resolve, reject) => {
    if (!fs.existsSync(deploySyncCli)) {
      reject(new Error(`deploy-sync not found at ${deploySyncCli}`));
      return;
    }
    const child = spawn(process.execPath, [deploySyncCli, ...extraArgs], {
      cwd,
      stdio: "inherit",
    });
    child.on("close", (code) => resolve(code === 0 ? 0 : code ?? 1));
    child.on("error", reject);
  });
}

async function prompt(rl, q) {
  return new Promise((resolve) => rl.question(q, resolve));
}

async function interactiveMenu(storePath) {
  const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout,
  });
  const data = loadStore(storePath);

  try {
    while (true) {
      console.log(`
--- deploy-hub (${data.profiles.length}/${MAX_PROFILES} profiles) ---
  1) List profiles
  2) Add profile (interactive)
  3) Git + server (deploy-sync both)
  4) Git push only
  5) Server upload only
  6) Dry-run (both)
  7) Backup store to configured destinations
  8) Environment check (Git)
  0) Exit
`);
      const choice = (await prompt(rl, "Choice: ")).trim();
      if (choice === "0") break;

      if (choice === "1") {
        listProfiles(data);
        continue;
      }
      if (choice === "2") {
        const id = (await prompt(rl, "Profile id (short name): ")).trim();
        const label = (await prompt(rl, "Label: ")).trim();
        const cfg = (await prompt(
          rl,
          "Path to deploy.config.yaml (from repo root): "
        )).trim();
        const dests = (
          await prompt(
            rl,
            "Backup folder(s) (comma-separated local paths, or empty): "
          )
        ).trim();
        const backupDestinations = dests
          ? dests.split(",").map((s) => ({
              type: "local",
              path: s.trim(),
            }))
          : [];
        try {
          addProfile(storePath, {
            id,
            label,
            deployConfigPath: cfg,
            backupDestinations,
          });
          Object.assign(data, loadStore(storePath));
          console.log("OK: profile added.");
        } catch (e) {
          console.error(e.message || e);
        }
        continue;
      }

      if (["3", "4", "5", "6"].includes(choice)) {
        const id = (await prompt(rl, "Profile id: ")).trim();
        const prof = data.profiles.find((p) => p.id === id);
        if (!prof) {
          console.log("Unknown profile.");
          continue;
        }
        const target = path.join(path.dirname(storePath), "..");
        const args = ["--config", prof.deployConfigPath];
        if (choice === "4") args.push("--target", "github");
        else if (choice === "5") args.push("--target", "server");
        else if (choice === "6") args.push("--target", "both", "--dry-run");
        else args.push("--target", "both");
        const code = await runDeploySync(target, args);
        console.log(`deploy-sync exited ${code}`);
        continue;
      }

      if (choice === "7") {
        const fresh = loadStore(storePath);
        runBackup(fresh, storePath);
        continue;
      }
      if (choice === "8") {
        printPreflightReport();
        continue;
      }
      console.log("Invalid choice.");
    }
  } finally {
    rl.close();
  }
}

function listProfiles(data) {
  if (!data.profiles.length) {
    console.log("No profiles. Use: deploy-hub add --id ... --config ...");
    return;
  }
  for (const p of data.profiles) {
    console.log(`  [${p.id}] ${p.label}`);
    console.log(`      config: ${p.deployConfigPath}`);
    const n = (p.backupDestinations || []).length;
    console.log(`      backups: ${n} destination(s)`);
  }
}

async function main() {
  const argv = minimist(process.argv.slice(2), {
    string: ["store", "id", "label", "config", "backup"],
    boolean: ["help", "h"],
    alias: { h: "help" },
    "--": true,
  });

  if (argv.help) {
    printHelp();
    return;
  }

  const cwd = process.cwd();
  const storePath = argv.store
    ? path.resolve(argv.store)
    : getDefaultStorePath(cwd);

  const cmd = argv._[0];

  if (!cmd && process.stdin.isTTY) {
    loadStore(storePath);
    await interactiveMenu(storePath);
    return;
  }

  if (!cmd) {
    printHelp();
    process.exit(1);
  }

  if (cmd === "check") {
    printPreflightReport();
    process.exit(checkGit().ok ? 0 : 1);
  }

  if (cmd === "list") {
    listProfiles(loadStore(storePath));
    return;
  }

  if (cmd === "add") {
    if (!argv.id || !argv.config) {
      console.error("deploy-hub add: --id and --config required");
      process.exit(1);
    }
    const backups = argv.backup
      ? String(argv.backup)
          .split(",")
          .map((s) => s.trim())
          .filter(Boolean)
          .map((pathStr) => ({ type: "local", path: pathStr }))
      : [];
    addProfile(storePath, {
      id: argv.id,
      label: argv.label || argv.id,
      deployConfigPath: argv.config,
      backupDestinations: backups,
    });
    console.log("OK");
    return;
  }

  if (cmd === "backup") {
    const data = loadStore(storePath);
    runBackup(data, storePath);
    return;
  }

  if (cmd === "deploy") {
    if (!argv.id) {
      console.error("deploy-hub deploy: --id required");
      process.exit(1);
    }
    const data = loadStore(storePath);
    const prof = data.profiles.find((p) => p.id === argv.id);
    if (!prof) {
      console.error("Unknown profile id");
      process.exit(1);
    }
    const repoRoot = path.join(path.dirname(storePath), "..");
    const extra = argv["--"] || [];
    const args = ["--config", prof.deployConfigPath, ...extra];
    const code = await runDeploySync(repoRoot, args);
    process.exit(code);
  }

  printHelp();
  process.exit(1);
}

main().catch((e) => {
  console.error(e);
  process.exit(1);
});
