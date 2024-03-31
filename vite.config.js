import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/css/styles.css',
                'resources/assets/scss/styles.scss',
                'resources/assets/js/dselect.js',
                'resources/js/app.js',
                'resources/js/components/loadMoreNews.js',
                'resources/js/components/loanConfirm.js',
                'resources/js/components/withdrawal.js',
            ],
            refresh: true,
        }),
    ],
});
