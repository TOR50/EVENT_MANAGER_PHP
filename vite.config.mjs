import { defineConfig } from 'vite';
import { resolve } from 'path';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    tailwindcss(),
  ],
  base: '/event_management/dist/',
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
    origin: 'http://localhost:5173',
  },
});
