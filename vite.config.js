import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                'resources/js/medical-booking/roles.js',
                'resources/js/medical-booking/document-types.js',
                'resources/js/medical-booking/doctor.js',
                'resources/js/medical-booking/patient.js',
                'resources/js/medical-booking/users.js',
                'resources/js/medical-booking/specialties.js',
                'resources/js/medical-booking/consulting-rooms.js',
                'resources/js/medical-booking/schedules.js',
                'resources/js/medical-booking/appointments.js',

                'resources/js/medical-booking/profile.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
