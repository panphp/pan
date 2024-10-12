import { resolve } from 'path'
import { defineConfig } from 'vite'


/**
 * @type {import('vite').UserConfig}
 */
export default defineConfig({
    build: {
        outDir: 'resources/js/dist',
        lib: {
            entry: resolve(__dirname, 'resources/js/src/main.ts'),
            name: 'pan',
            fileName: 'pan',
            formats: ['iife'],
        }
    },
});
