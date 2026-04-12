import { execFileSync } from "node:child_process";

/**
 * Print git diff for paths (or whole repo if paths empty).
 * @param {"stat"|"patch"} format
 */
export function printGitDiff(repoRoot, gitArgs, paths, format) {
  const fmt = format === "patch" ? [] : ["--stat"];
  const args = ["diff", "--no-color", ...fmt, ...gitArgs];
  if (paths && paths.length) {
    args.push("--");
    args.push(...paths);
  }
  try {
    const out = execFileSync("git", args, {
      cwd: repoRoot,
      encoding: "utf8",
      maxBuffer: 64 * 1024 * 1024,
      env: { ...process.env, GIT_PAGER: "cat" },
    });
    if (out && out.trim()) {
      console.log(out);
    } else {
      console.log("(no textual diff output — binary or no changes in selection)");
    }
  } catch (e) {
    const stderr = e.stderr ? String(e.stderr) : "";
    console.warn("deploy-sync: git diff:", e.message || e, stderr);
  }
}

/**
 * Diff for uploadMode git — same refs as resolveGitUploadPaths.
 */
export function printGitModeDiff(repoRoot, gitDiffMode, gitAgainst, uploadPaths, format) {
  const mode = (gitDiffMode || "ahead").toLowerCase();
  const ref = gitAgainst || "@{u}";
  console.log(
    `\n========== deploy-sync: diff (${mode} vs ${ref}, format=${format}) ==========\n`
  );
  if (mode === "working") {
    printGitDiff(repoRoot, ["HEAD"], uploadPaths, format);
    try {
      const u = execFileSync("git", ["ls-files", "--others", "--exclude-standard"], {
        cwd: repoRoot,
        encoding: "utf8",
      });
      const untracked = u
        .split(/\r?\n/)
        .map((l) => l.trim())
        .filter(Boolean);
      const set = new Set(uploadPaths);
      const inScope = untracked.filter((p) => set.has(p));
      if (inScope.length) {
        console.log("\n--- untracked (in upload set) ---\n");
        console.log(inScope.join("\n"));
      }
    } catch {
      /* ignore */
    }
    return;
  }
  if (mode === "against") {
    printGitDiff(repoRoot, [ref], uploadPaths, format);
    return;
  }
  // ahead: three-dot
  const range = `${ref}...HEAD`;
  printGitDiff(repoRoot, [range], uploadPaths, format);
}

export function printFullModeDiff(repoRoot, uploadPaths, format) {
  console.log(
    `\n========== deploy-sync: diff (working tree vs HEAD for upload paths, format=${format}) ==========\n`
  );
  printGitDiff(repoRoot, ["HEAD"], uploadPaths, format);
}

export function printIncrementalDiffHint(repoRoot, uploadPaths, format) {
  console.log(
    `\n========== deploy-sync: diff hint (git vs HEAD for same paths — may differ from manifest) ==========\n`
  );
  if (!uploadPaths.length) {
    console.log("(nothing to upload)");
    return;
  }
  printGitDiff(repoRoot, ["HEAD"], uploadPaths, format);
}

export function printIncrementalManifestList(upload, previous, currentMap) {
  console.log(
    `\n========== deploy-sync: upload list (manifest incremental) ==========\n`
  );
  for (const rel of upload) {
    const prev = previous[rel];
    const cur = currentMap.get(rel);
    if (!prev) {
      console.log(`  + ${rel}  (new since last deploy)`);
    } else if (cur && prev.sha256 !== cur.sha256) {
      console.log(`  ~ ${rel}  (content changed)`);
    } else {
      console.log(`  * ${rel}`);
    }
  }
}
