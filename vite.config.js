import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  /*
  build: {
    assetsDir: '',
    manifest: true,
    rollupOptions: {
      input: ['resources/js/main.ts', 'resources/css/main.scss'],
      output: {
        assetFileNames: 'bs-blade-forms-[hash][extname]',
        entryFileNames: 'bs-blade-forms-[hash].js',
      },
    },
  },
   */
  plugins: [
    laravel({
      hotFile: 'public/vendor/bs-blade-forms/blade-forms.hot', // Most important lines
      buildDirectory: 'vendor/bs-blade-forms', // Most important lines
      input: ['resources/js/main.ts', 'resources/css/main.scss'],
      refresh: true,
    }),
  ],
  resolve: {
    alias: {
      '@': '/resources/js',
    },
  },
})
