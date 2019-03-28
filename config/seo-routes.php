<?php

return [
    'mask' => '(:any)',

    /*
     |--------------------------------------------------------------------------
     | Array of the current SEO routes
     |--------------------------------------------------------------------------
     |
     | Example: ['/pages/view/(:any)' => '/page-(:any).html']
     |
     | @key Real path
     | @value SEO URL
     */
    'current' => [
//        '/' => '/index.html',
//        '/old/(:any)' => '/(:any).html',

    ],

    /*
     |--------------------------------------------------------------------------
     | Array of the old SEO routes. Used for 301 redirect to the current route.
     |--------------------------------------------------------------------------
     |
     | Example: ['/old-page.html' => '/pages/view/1']
     |
     | @key SEO URL
     | @value Real path
     */
    'old' => [
//        '/old-index.html' => '/',
//        '/(:any).php' => '/old/(:any)',
    ],
];