import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
  plugins: [],
  base: '/dist/',
  build: {
    manifest: true,
    outDir: 'event_management/dist',
    rollupOptions: {
      input: {
        main: resolve(process.cwd(), 'event_management/assets/js/main.js'),
        style: resolve(process.cwd(), 'event_management/assets/css/style.css')
      },
    },
  },
  server: {
    strictPort: false,
    port: 5173,
    host: true,
    origin: 'http://localhost:5173',
    cors: true,
  },
});
