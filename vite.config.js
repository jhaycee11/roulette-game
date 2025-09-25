import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/roulette.css', 'resources/css/custom-winner.css', 'resources/js/app.js', 'resources/js/roulette.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
