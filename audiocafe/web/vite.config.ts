import { defineConfig } from "vite";
import { dirname, resolve } from "node:path";
import { fileURLToPath } from "node:url";

const rootDir = dirname(fileURLToPath(import.meta.url));

/** Static output for LOLIPOP: upload contents of `audiocafe/` (parent of this folder). */
export default defineConfig({
  root: rootDir,
  base: "./",
  build: {
    outDir: resolve(rootDir, ".."),
    emptyOutDir: false,
    rollupOptions: {
      input: resolve(rootDir, "index.html"),
    },
  },
});
