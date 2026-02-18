"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const path_1 = require("path");
const vite_1 = require("vite");
/**
 * @type {import('vite').UserConfig}
 */
exports.default = (0, vite_1.defineConfig)({
    build: {
        outDir: 'resources/js/dist',
        lib: {
            entry: (0, path_1.resolve)(__dirname, 'resources/js/src/main.ts'),
            name: 'pan',
            fileName: 'pan',
            formats: ['iife'],
        }
    },
});
