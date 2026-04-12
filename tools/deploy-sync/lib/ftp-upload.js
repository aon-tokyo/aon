import { Client } from "basic-ftp";
import path from "node:path";
import { posixJoinRemote } from "./files.js";

function sleep(ms) {
  return new Promise((r) => setTimeout(r, ms));
}

function buildClient(server) {
  const timeout = server.timeout || 30000;
  const passiveIpv4Only = server.ftpCompat?.passiveIpv4Only === true;
  return new Client(timeout, {
    allowSeparateTransferHost: !passiveIpv4Only,
  });
}

/**
 * @param {object} opts
 * @param {object} opts.server
 * @param {string} opts.repoRoot
 * @param {string[]} opts.uploadList relative posix paths
 * @param {string[]} opts.deleteList
 */
export async function uploadFtp(opts) {
  const { server, repoRoot, uploadList, deleteList } = opts;
  const client = buildClient(server);
  client.ftp.verbose = Boolean(server.verbose);

  const retries = Math.max(0, Number(server.ftpCompat?.uploadRetries ?? 2));
  const delayMs = Math.max(0, Number(server.ftpCompat?.retryDelayMs ?? 800));
  const betweenMs = Math.max(0, Number(server.ftpCompat?.delayBetweenUploadsMs ?? 0));

  try {
    await client.access({
      host: server.host,
      port: server.port || 21,
      user: server.user,
      password: server.password,
      secure: server.secure === true || server.secure === "implicit",
      secureOptions: server.secureOptions,
    });

    if (server.ftpCompat?.binaryBeforeUpload !== false) {
      try {
        await client.send("TYPE I");
      } catch (e) {
        console.warn("deploy-sync: FTP TYPE I:", e.message || e);
      }
    }

    const root = server.remoteRoot.replace(/\\/g, "/").replace(/\/+$/, "");

    for (let i = 0; i < uploadList.length; i++) {
      const rel = uploadList[i];
      const local = path.join(repoRoot, rel);
      const remote = posixJoinRemote(root, rel).replace(/\\/g, "/");

      for (let attempt = 0; attempt <= retries; attempt++) {
        try {
          if (attempt > 0 && delayMs) await sleep(delayMs);
          const remoteDir = path.posix.dirname(remote);
          await client.ensureDir(remoteDir);
          await client.uploadFrom(local, remote);
          console.log(
            `  FTP PUT ${rel}${attempt > 0 ? ` (retry ${attempt})` : ""}`
          );
          break;
        } catch (e) {
          if (attempt === retries) throw e;
          console.warn(
            `  FTP PUT retry ${rel} (${attempt + 1}/${retries}):`,
            e.message || e
          );
        }
      }
      if (betweenMs && i < uploadList.length - 1) await sleep(betweenMs);
    }

    for (const rel of deleteList) {
      const remote = posixJoinRemote(root, rel).replace(/\\/g, "/");
      try {
        await client.remove(remote);
        console.log(`  FTP DELETE ${rel}`);
      } catch (e) {
        console.warn(`  FTP DELETE skip ${rel}:`, e.message || e);
      }
    }
  } finally {
    client.close();
  }
}
