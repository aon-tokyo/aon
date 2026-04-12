import SftpClient from "ssh2-sftp-client";
import fs from "node:fs";
import path from "node:path";
import { posixJoinRemote } from "./files.js";

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

    for (const rel of uploadList) {
      const local = path.join(repoRoot, rel);
      const remote = posixJoinRemote(root, rel).replace(/\\/g, "/");
      const remoteDir = path.posix.dirname(remote);
      await mkdirPSftp(sftp, remoteDir);
      await sftp.put(local, remote);
      console.log(`  SFTP PUT ${rel}`);
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
