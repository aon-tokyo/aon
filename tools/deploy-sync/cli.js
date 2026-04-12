#!/usr/bin/env node
/**
 * deploy-sync — GitHub push + optional incremental FTP/SFTP upload
 * See ../../docs/deployment-sync-app-spec.md
 */
import minimist from "minimist";
import { loadConfig } from "./lib/config.js";
import { runGit } from "./lib/git.js";
import { collectFiles, hashFile } from "./lib/files.js";
import {
  loadManifest,
  saveManifest,
  diffIncremental,
  diffMirrorDeletes,
} from "./lib/manifest.js";
import { uploadFtp } from "./lib/ftp-upload.js";
import { uploadSftp } from "./lib/sftp-upload.js";
import {
  resolveGitUploadPaths,
  intersectWithDeployPaths,
} from "./lib/git-files.js";
import path from "node:path";
import fs from "node:fs";

function printHelp() {
  console.log(`
deploy-sync — GitHub + incremental server deploy

Usage:
  deploy-sync [options]

Options:
  --config <path>     Config file (default: deploy.config.yaml, then deploy.config.yml)
  --target <t>        github | server | both  (overrides config target)
  --dry-run           List actions only
  --continue-on-git-failure  After failed git push, still run server upload (or set in YAML)
  --full-upload         Upload every matched file (ignore manifest); for hosts weak to incremental
  --upload-mode <m>     incremental | full | git (overrides server.uploadMode)
  --i-understand-delete-on-server  Required with mirror delete (server.deleteRemoved)

Environment:
  Substitute \${VAR} in YAML with process.env.VAR (e.g. DEPLOY_SFTP_PASSWORD)
`);
}

async function main() {
  const argv = minimist(process.argv.slice(2), {
    string: ["config", "target", "upload-mode"],
    boolean: [
      "dry-run",
      "help",
      "h",
      "i-understand-delete-on-server",
      "continue-on-git-failure",
      "full-upload",
    ],
    alias: { h: "help" },
  });

  if (argv.help) {
    printHelp();
    process.exit(0);
  }

  const cwd = process.cwd();
  const configPath = argv.config || findDefaultConfig(cwd);
  if (!configPath || !fs.existsSync(configPath)) {
    console.error(
      "deploy-sync: No deploy.config.yaml / deploy.config.yml found. Use --config PATH"
    );
    process.exit(1);
  }

  const cfg = loadConfig(configPath, cwd);
  if (argv["continue-on-git-failure"]) {
    cfg.continueOnGitFailure = true;
  }
  if (argv["full-upload"]) {
    cfg.server = cfg.server || {};
    cfg.server.uploadMode = "full";
  }
  if (argv["upload-mode"]) {
    cfg.server = cfg.server || {};
    cfg.server.uploadMode = argv["upload-mode"];
  }

  const normalizedTarget = normalizeTarget(
    argv.target || cfg.target || "dual"
  );
  const dryRun = argv["dry-run"] === true;

  console.log(`deploy-sync: config=${path.relative(cwd, configPath) || "."}`);
  console.log(`deploy-sync: target=${normalizedTarget} dry-run=${dryRun}`);

  const repoRoot = path.resolve(cwd, cfg.paths?.localRoot || ".");
  let gitOk = true;

  if (normalizedTarget === "github" || normalizedTarget === "both") {
    const g = cfg.git || {};
    const r = await runGit(repoRoot, {
      pushOnly: g.pushOnly !== false,
      remote: g.remote || "origin",
      branch: g.branch || "main",
      commitMessage: g.commitMessage || null,
      dryRun,
    });
    gitOk = r.ok;
    if (!r.ok && !cfg.continueOnGitFailure) {
      console.error("deploy-sync: git step failed; aborting.");
      process.exit(1);
    }
    if (!r.ok && cfg.continueOnGitFailure) {
      console.warn("deploy-sync: continuing despite git failure (--continue-on-git-failure)");
    }
  }

  if (normalizedTarget === "server" || normalizedTarget === "both") {
    if (normalizedTarget === "both" && !gitOk && !cfg.continueOnGitFailure) {
      process.exit(1);
    }
    const server = cfg.server || {};
    const protocol = (server.protocol || "sftp").toLowerCase();
    const manifestPath = path.resolve(
      repoRoot,
      cfg.manifest?.path || ".deploy-manifest.json"
    );

    const files = await collectFiles(repoRoot, cfg.paths || {});
    const current = new Map();
    for (const rel of files) {
      const abs = path.join(repoRoot, rel);
      const h = await hashFile(abs);
      current.set(rel.replace(/\\/g, "/"), h);
    }

    const uploadMode = (server.uploadMode || "incremental").toLowerCase();
    const previous = loadManifest(manifestPath);

    let upload;
    let skipReason = [];
    if (uploadMode === "full") {
      upload = Array.from(current.keys());
      console.log(
        `deploy-sync: server uploadMode=full (all ${upload.length} matched files; no manifest skip)`
      );
    } else if (uploadMode === "git") {
      const gitDiffMode = (server.gitDiffMode || "ahead").toLowerCase();
      const gitAgainst = server.gitAgainst || "@{u}";
      let gitPaths;
      try {
        gitPaths = resolveGitUploadPaths(repoRoot, gitDiffMode, gitAgainst);
      } catch (e) {
        console.error("deploy-sync:", e.message || e);
        process.exit(1);
      }
      const allowed = new Set(current.keys());
      upload = intersectWithDeployPaths(gitPaths, allowed);
      upload = upload.filter((rel) => {
        const abs = path.join(repoRoot, rel);
        return fs.existsSync(abs) && fs.statSync(abs).isFile();
      });
      console.log(
        `deploy-sync: server uploadMode=git (${gitDiffMode} vs ${gitAgainst}) gitPaths=${gitPaths.length} after paths filter=${upload.length}`
      );
    } else {
      const diff = diffIncremental(previous, current);
      upload = diff.upload;
      skipReason = diff.skipReason;
      console.log(
        `deploy-sync: server files matched=${current.size} toUpload=${upload.length} (incremental)`
      );
      if (skipReason.length && dryRun) {
        for (const s of skipReason.slice(0, 20))
          console.log(`  skip unchanged: ${s}`);
        if (skipReason.length > 20)
          console.log(`  ... +${skipReason.length - 20} more`);
      }
    }

    const syncMode = (server.syncMode || "incremental").toLowerCase();
    const deleteRemoved = server.deleteRemoved === true;
    const allowDelete =
      deleteRemoved &&
      argv["i-understand-delete-on-server"] === true;

    let toDelete = [];
    if (syncMode === "mirror" && deleteRemoved) {
      if (!allowDelete) {
        console.error(
          "deploy-sync: mirror delete requires --i-understand-delete-on-server and server.deleteRemoved: true"
        );
        process.exit(1);
      }
      toDelete = diffMirrorDeletes(previous, current);
      console.log(`deploy-sync: mirror delete count=${toDelete.length}`);
    }

    if (dryRun) {
      for (const u of upload) console.log(`  [dry-run] PUT ${u}`);
      for (const d of toDelete) console.log(`  [dry-run] DELETE ${d}`);
      console.log("deploy-sync: dry-run complete.");
      process.exit(0);
    }

    if (upload.length === 0 && toDelete.length === 0) {
      console.log(
        "deploy-sync: server nothing to upload or delete; updating manifest to match local tree."
      );
      saveManifest(manifestPath, current);
      console.log(`deploy-sync: manifest saved ${path.relative(cwd, manifestPath)}`);
      console.log("deploy-sync: done.");
      process.exit(0);
    }

    if (protocol === "ftp") {
      await uploadFtp({
        server,
        repoRoot,
        uploadList: upload,
        deleteList: toDelete,
      });
    } else if (protocol === "sftp") {
      await uploadSftp({
        server,
        repoRoot,
        uploadList: upload,
        deleteList: toDelete,
      });
    } else {
      console.error(`deploy-sync: unknown server.protocol: ${protocol}`);
      process.exit(1);
    }

    saveManifest(manifestPath, current);
    console.log(`deploy-sync: manifest saved ${path.relative(cwd, manifestPath)}`);
  }

  console.log("deploy-sync: done.");
}

function findDefaultConfig(cwd) {
  for (const n of ["deploy.config.yaml", "deploy.config.yml", "deploy.config.json"]) {
    const p = path.join(cwd, n);
    if (fs.existsSync(p)) return p;
  }
  return null;
}

function normalizeTarget(t) {
  const x = String(t || "dual").toLowerCase();
  if (x === "github-only" || x === "github") return "github";
  if (x === "server-only" || x === "server") return "server";
  if (x === "dual" || x === "both") return "both";
  return "both";
}

main().catch((e) => {
  console.error(e);
  process.exit(1);
});
