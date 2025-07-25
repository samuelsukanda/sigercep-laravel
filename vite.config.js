import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
  server: {
    host: '0.0.0.0',
    port: 5173, // kamu bisa ubah sesuai kebutuhan
    strictPort: true,
    hmr: {
      host: '192.168.142.33', // IP address PC kamu di LAN
    }
  },
});
