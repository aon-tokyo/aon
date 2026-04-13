import { spawn, execFileSync } from "node:child_process";

function runSpawn(cmd, args, cwd, dryRun) {
  return new Promise((resolve, reject) => {
    if (dryRun) {
      console.log(`  [dry-run] ${cmd} ${args.join(" ")}`);
      resolve(0);
      return;
    }
    const p = spawn(cmd, args, {
      cwd,
      stdio: "inherit",
      shell: process.platform === "win32",
    });
    p.on("close", (code) => resolve(code ?? 0));
    p.on("error", reject);
  });
}

/**
 * @returns {Promise<{ ok: boolean }>}
 */
export async function runGit(repoRoot, opts) {
  const { pushOnly, remote, branch, commitMessage, dryRun } = opts;

  try {
    if (!pushOnly) {
      const addCode = await runSpawn("git", ["add", "-A"], repoRoot, dryRun);
      if (addCode !== 0) return { ok: false };

      if (!dryRun) {
        let hasChanges = true;
        try {
          const out = execFileSync("git", ["status", "--porcelain"], {
            cwd: repoRoot,
            encoding: "utf8",
          });
          hasChanges = Boolean(out && out.trim());
        } catch {
          hasChanges = true;
        }
        if (hasChanges) {
          const msg = commitMessage || `deploy-sync ${new Date().toISOString()}`;
          const c = await runSpawn("git", ["commit", "-m", msg], repoRoot, false);
          if (c !== 0) return { ok: false };
        } else {
          console.log("deploy-sync: git nothing to commit, skipping commit");
        }
      }
    }

    const pushCode = await runSpawn(
      "git",
      ["push", remote, branch],
      repoRoot,
      dryRun
    );
    return { ok: pushCode === 0 };
  } catch (e) {
    console.error("deploy-sync git:", e.message || e);
    return { ok: false };
  }
}
