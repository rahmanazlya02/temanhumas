import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/filament.scss',
                'resources/js/filament.js'
            ],
            refresh: [
                'app/Http/Livewire/**',
            ],
        }),
    ],
    server: {
        host: true, // Izinkan akses dari alamat IP atau domain
        hmr: {
            host: 'temanhumas.xath.site', // Ganti dengan domain Anda
        },
    },
    build: {
        outDir: 'public/build', // Sesuaikan output build Anda
    },
});
