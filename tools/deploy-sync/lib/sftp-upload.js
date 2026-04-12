import SftpClient from "ssh2-sftp-client";
import fs from "node:fs";
import path from "node:path";
import { posixJoinRemote } from "./files.js";

function sleep(ms) {
  return new Promise((r) => setTimeout(r, ms));
}

async function mkdirPSftp(sftp, dir) {
  if (!dir || dir === "/") return;
  const d = dir.replace(/\/+$/, "");
  try {
    await sftp.mkdir(d, true);
  } catch {
    // exists or partial
  }
}

export async function uploadSftp(opts) {
  const { server, repoRoot, uploadList, deleteList } = opts;
  const sftp = new SftpClient();

  const retries = Math.max(0, Number(server.sftpCompat?.uploadRetries ?? 2));
  const delayMs = Math.max(0, Number(server.sftpCompat?.retryDelayMs ?? 800));
  const betweenMs = Math.max(0, Number(server.sftpCompat?.delayBetweenUploadsMs ?? 0));

  const config = {
    host: server.host,
    port: server.port || 22,
    username: server.user,
  };
  if (server.privateKey) {
    config.privateKey = fs.readFileSync(
      path.resolve(server.privateKey),
      "utf8"
    );
    if (server.passphrase) config.passphrase = server.passphrase;
  } else if (server.password) {
    config.password = server.password;
  }

  try {
    await sftp.connect(config);
    const root = server.remoteRoot.replace(/\\/g, "/").replace(/\/+$/, "");

    for (let i = 0; i < uploadList.length; i++) {
      const rel = uploadList[i];
      const local = path.join(repoRoot, rel);
      const remote = posixJoinRemote(root, rel).replace(/\\/g, "/");
      const remoteDir = path.posix.dirname(remote);
      await mkdirPSftp(sftp, remoteDir);

      for (let attempt = 0; attempt <= retries; attempt++) {
        try {
          if (attempt > 0 && delayMs) await sleep(delayMs);
          await sftp.put(local, remote);
          console.log(
            `  SFTP PUT ${rel}${attempt > 0 ? ` (retry ${attempt})` : ""}`
          );
          break;
        } catch (e) {
          if (attempt === retries) throw e;
          console.warn(
            `  SFTP PUT retry ${rel} (${attempt + 1}/${retries}):`,
            e.message || e
          );
        }
      }
      if (betweenMs && i < uploadList.length - 1) await sleep(betweenMs);
    }

    for (const rel of deleteList) {
      const remote = posixJoinRemote(root, rel).replace(/\\/g, "/");
      try {
        await sftp.delete(remote);
        console.log(`  SFTP DELETE ${rel}`);
      } catch (e) {
        console.warn(`  SFTP DELETE skip ${rel}:`, e.message || e);
      }
    }
  } finally {
    await sftp.end();
  }
}
