import { Client } from "basic-ftp";
import path from "node:path";
import { posixJoinRemote } from "./files.js";

/**
 * @param {object} opts
 * @param {object} opts.server
 * @param {string} opts.repoRoot
 * @param {string[]} opts.uploadList relative posix paths
 * @param {string[]} opts.deleteList
 */
export async function uploadFtp(opts) {
  const { server, repoRoot, uploadList, deleteList } = opts;
  const client = new Client(server.timeout || 30000);
  client.ftp.verbose = Boolean(server.verbose);

  try {
    await client.access({
      host: server.host,
      port: server.port || 21,
      user: server.user,
      password: server.password,
      secure: server.secure === true || server.secure === "implicit",
      secureOptions: server.secureOptions,
    });

    const root = server.remoteRoot.replace(/\\/g, "/").replace(/\/+$/, "");

    for (const rel of uploadList) {
      const local = path.join(repoRoot, rel);
      const remote = posixJoinRemote(root, rel).replace(/\\/g, "/");
      const remoteDir = path.posix.dirname(remote);
      await client.ensureDir(remoteDir);
      await client.uploadFrom(local, remote);
      console.log(`  FTP PUT ${rel}`);
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
