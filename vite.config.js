import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// import { ngrok } from 'vite-plugin-ngrok';
// const { NGROK_AUTH_TOKEN } = loadEnv('', process.cwd(), 'NGROK')

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        // ngrok('2q42U9or7u3tS3LoXHayLZGuwTy_84iKNFW5AxbgMPicVMFyw'),

        // ngrok({
        //     authtoken: "2q42U9or7u3tS3LoXHayLZGuwTy_84iKNFW5AxbgMPicVMFyw",
        //   }),
    ],
    // server: {
    //     host: true,
    // },
});
