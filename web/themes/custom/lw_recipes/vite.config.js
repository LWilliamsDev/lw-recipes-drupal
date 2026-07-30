import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'path';

export default defineConfig({
  plugins: [tailwindcss()],
  build: {
    outDir: resolve(__dirname, './assets'),
    emptyOutDir: false,
    // 1. Enables minification (Vite uses esbuild by default, which is incredibly fast)
    minify: 'esbuild', 
    rollupOptions: {

      input: {
        // 2. Define your entry points so Vite knows what to compile
        style: resolve(__dirname, './src/assets/css/style.css'), // Your main HTML file
        global: resolve(__dirname, './src/assets/js/global-js.js'), // Replace with your specific JS file path
      },
      output: {
        // Keeps files named cleanly instead of adding a hash (optional)
        entryFileNames: 'js/[name].js',
        assetFileNames: 'css/[name].[ext]',
      },
    },
  },
});