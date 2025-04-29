import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    server: {
        host: true,
        cors: {
            origin: /^https?:\/\/(?:(?:[^:]+\.)?localhost|mysite\.test|127\.0\.0\.1|\[::1\])(?::\d+)?$/,
        },
        hmr: {
            // host: '192.168.1.225',
        },
        headers: {
            'Access-Control-Allow-Origin': '*',
        },
    },
    plugins: [
        laravel({
            input: 'resources/js/app.ts',
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
