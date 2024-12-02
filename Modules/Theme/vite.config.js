import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';

const dotenvExpand = require('dotenv-expand');
dotenvExpand(require('dotenv').config({ path: '../../.env'/*, debug: true*/ }));

export default defineConfig({
    build: {
        outDir: '../../public/build-theme',
        emptyOutDir: true,
        manifest: true,
    },
    plugins: [
        laravel({
            publicDirectory: '../../public',
            buildDirectory: 'build-theme',
            input: [
                __dirname + '/Resources/assets/sass/app.scss',
                __dirname + '/Resources/assets/sass/_post-content.scss',
                __dirname + '/Resources/assets/sass/_variables.scss',
                __dirname + '/Resources/assets/sass/mixins.scss',
                __dirname + '/Resources/assets/js/app.js',
                __dirname + '/Resources/assets/js/seo.js'
            ],
            refresh: true,
        }),
    ],
    css: {
        postcss: {
            plugins: [
                tailwindcss,
                autoprefixer,
            ],
        },
    },
});