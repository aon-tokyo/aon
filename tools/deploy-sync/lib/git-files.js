import { execFileSync } from "node:child_process";
import path from "node:path";

/**
 * List repo-relative paths changed vs last commit (staged + unstaged + untracked under exclude-standard).
 */
export function listWorkingTreeVsHead(repoRoot) {
  const tracked = splitLines(
    execFileSync("git", ["diff", "--name-only", "HEAD"], {
      cwd: repoRoot,
      encoding: "utf8",
      maxBuffer: 32 * 1024 * 1024,
    })
  );
  const untracked = splitLines(
    execFileSync("git", ["ls-files", "--others", "--exclude-standard"], {
      cwd: repoRoot,
      encoding: "utf8",
      maxBuffer: 32 * 1024 * 1024,
    })
  );
  return uniq([...tracked, ...untracked].map((p) => p.replace(/\\/g, "/")));
}

/**
 * Symmetric diff: commits on HEAD not reachable from ref (e.g. unpushed work).
 * Uses three-dot: merge-base(ref, HEAD)..HEAD
 */
export function listCommitsAheadOfRef(repoRoot, ref) {
  const range = `${ref}...HEAD`;
  const out = execFileSync(
    "git",
    ["diff", "--name-only", range],
    {
      cwd: repoRoot,
      encoding: "utf8",
      maxBuffer: 32 * 1024 * 1024,
    }
  );
  return splitLines(out).map((p) => p.replace(/\\/g, "/"));
}

/**
 * Working tree + index vs ref (e.g. @{u} or origin/main): all paths that differ from remote tip.
 */
export function listDiffNameOnly(repoRoot, ref) {
  const out = execFileSync(
    "git",
    ["diff", "--name-only", ref],
    {
      cwd: repoRoot,
      encoding: "utf8",
      maxBuffer: 32 * 1024 * 1024,
    }
  );
  return splitLines(out).map((p) => p.replace(/\\/g, "/"));
}

function splitLines(s) {
  return String(s || "")
    .split(/\r?\n/)
    .map((l) => l.trim())
    .filter(Boolean);
}

function uniq(arr) {
  return [...new Set(arr)];
}

/**
 * Resolve which files to upload when uploadMode is "git".
 * @param {"working"|"ahead"|"against"} mode
 * @param {string} [againstRef] e.g. origin/main, @{u}
 */
export function resolveGitUploadPaths(repoRoot, mode, againstRef) {
  const m = (mode || "ahead").toLowerCase();
  if (m === "working") {
    return listWorkingTreeVsHead(repoRoot);
  }
  if (m === "against") {
    const ref = againstRef || "@{u}";
    try {
      return listDiffNameOnly(repoRoot, ref);
    } catch (e) {
      throw new Error(
        `git diff against ${ref} failed: ${e.message}. Set server.gitAgainst or use uploadMode: incremental.`
      );
    }
  }
  // ahead: commits not in upstream ref (three-dot)
  const ref = againstRef || "@{u}";
  try {
    return listCommitsAheadOfRef(repoRoot, ref);
  } catch (e1) {
    try {
      const fb = againstRef || "origin/main";
      return listCommitsAheadOfRef(repoRoot, fb);
    } catch (e2) {
      throw new Error(
        `git three-dot diff failed (${ref}): ${e1.message}. Try server.gitDiffMode: working or set server.gitAgainst.`
      );
    }
  }
}

/**
 * Keep only paths that exist under repoRoot and match allowedSet (from collectFiles).
 * @param {string[]} gitPaths
 * @param {Set<string>} allowedSet posix-relative paths from collectFiles
 */
export function intersectWithDeployPaths(gitPaths, allowedSet) {
  const out = [];
  for (const p of gitPaths) {
    const n = p.replace(/\\/g, "/");
    if (allowedSet.has(n)) out.push(n);
  }
  return uniq(out);
}
