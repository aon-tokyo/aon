import { execFileSync } from "node:child_process";

export function checkNode() {
  return {
    ok: true,
    version: process.version,
  };
}

export function checkGit() {
  try {
    const v = execFileSync("git", ["--version"], {
      encoding: "utf8",
    }).trim();
    return { ok: true, version: v };
  } catch {
    return {
      ok: false,
      hint: "Install Git: https://git-scm.com/downloads (Windows/macOS/Linux)",
    };
  }
}

export function printPreflightReport() {
  const node = checkNode();
  const git = checkGit();
  console.log("--- Environment ---");
  console.log(`Node.js: ${node.version}`);
  if (git.ok) {
    console.log(`Git:     ${git.version}`);
  } else {
    console.log(`Git:     NOT FOUND`);
    console.log(`         ${git.hint}`);
  }
  console.log("");
  console.log(
    "Google Drive / NAS: mount as a normal folder and add its path as a backup destination."
  );
  console.log("");
  return git.ok;
}
