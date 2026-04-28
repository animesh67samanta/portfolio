<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Site Information
    |--------------------------------------------------------------------------
    |
    | Global defaults used across all pages. Page-level overrides
    | can be provided via Inertia props or the useSeoMeta composable.
    |
    */

    'default_image' => env('SEO_DEFAULT_IMAGE', '/logo.png'),
    'locale' => env('SEO_LOCALE', 'en_US'),
    'site_name' => env('SEO_SITE_NAME', 'Animesh Samanta Portfolio'),

    'default_title' => env(
        'SEO_DEFAULT_TITLE',
        'Animesh Samanta | Laravel & Vue.js Developer | Portfolio'
    ),

    'default_description' => env(
        'SEO_DEFAULT_DESCRIPTION',
        'Explore the portfolio of Animesh Samanta, a skilled Laravel and Vue.js developer specializing in building scalable web applications, modern user interfaces, and high-performance backend systems.'
    ),

    'default_keywords' => env(
        'SEO_DEFAULT_KEYWORDS',
        'Animesh Samanta, Laravel Developer, Vue.js Developer, Full Stack Developer India, Web Developer Portfolio, PHP Developer, Inertia.js, Tailwind CSS, Backend Developer, Frontend Developer, Web Application Development'
    ),

    /*
    |--------------------------------------------------------------------------
    | Twitter / X
    |--------------------------------------------------------------------------
    */

    'twitter' => [
        'card' => env('SEO_TWITTER_CARD', 'summary_large_image'),
        'site' => env('SEO_TWITTER_SITE', '@animeshsamanta'),
        'creator' => env('SEO_TWITTER_CREATOR', '@animeshsamanta'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Facebook / Open Graph
    |--------------------------------------------------------------------------
    */

    'facebook' => [
        'app_id' => env('SEO_FACEBOOK_APP_ID', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Robots
    |--------------------------------------------------------------------------
    |
    | Default robots directive. Use 'noindex, nofollow' for staging.
    |
    */

    'robots' => env('SEO_ROBOTS', 'index, follow'),
];

