import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import path from "path";

export default defineConfig({
  plugins: [
    react(),
  ],
  resolve: {
    alias: {
      "@": path.resolve(import.meta.dirname, "client", "src"),
      "@shared": path.resolve(import.meta.dirname, "shared"),
      "@assets": path.resolve(import.meta.dirname, "attached_assets"),
    },
  },
  root: path.resolve(import.meta.dirname, "client"),
  build: {
    outDir: path.resolve(import.meta.dirname, "dist/public"),
    emptyOutDir: true,
  },
  server: {
  host: true,            // ฟัง 0.0.0.0 ให้เครื่องนอกแลนเข้าได้
  port: 5173,
  strictPort: true,
  hmr: {                 // ให้ HMR/WebSocket ใช้โฮสต์พอร์ตภายนอกจริง
    host: 'therem.3bbddns.com', // เช่น myhome.ddns.net
    port: 5173,
  },
  proxy: {
    "/api": { target: "http://localhost:8000", changeOrigin: true, secure: false },
  },
}

});
