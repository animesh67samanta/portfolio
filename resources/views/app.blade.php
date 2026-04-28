<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- DNS Prefetch & Preconnect for Performance -->
        <link rel="dns-prefetch" href="//fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>

        <!-- Base Title (overridden by Inertia Head) -->
        <title inertia>{{ config('app.name', 'HUB Portfolio') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="/favicon.ico" />
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png" />
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png" />
        <link rel="icon" type="image/svg+xml" href="/favicon.svg" />

        <!-- Apple -->
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />

        <!-- Android / PWA -->
        <link rel="manifest" href="/site.webmanifest" />

        <!-- Inertia Head MUST be after the Vite directive so SSR/client meta hydrates correctly -->
        @routes
        @vite(['resources/css/app.css', 'resources/js/app.ts'])
        @inertiaHead

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-5FRX3C8F');
        </script>
        <!-- End Google Tag Manager -->
    </head>
    <body class="font-sans antialiased">
        @inertia

        <!-- Google Tag Manager (noscript) -->
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5FRX3C8F" height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
        <!-- End Google Tag Manager (noscript) -->
    </body>
</html>
