import { defineConfig } from 'vite'

export default defineConfig({
  build: {
    assetsDir: '',
    rollupOptions: {
      input: ['resources/js/main.ts', 'resources/css/main.scss'],
      output: {
        entryFileNames: '[name].js',
        chunkFileNames: '[name].js',
        assetFileNames: '[name].[ext]',
      },
    },
  },
  resolve: {
    alias: {
      '@': '/resources/js',
    },
  },
})
