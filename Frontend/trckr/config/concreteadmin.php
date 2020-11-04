<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    */

    'title' => 'Trckr',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    */

    'logo' => '<b>Trackr</b>',
    'logo_img' => 'images/hustle_logo.png',
    'logo_img_white' => 'images/hustle_logo_white.png',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Trckr',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => false,
    'password_reset_url' => false,
    'password_email_url' => false,
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | MENU / BREADCRUMBS
    |--------------------------------------------------------------------------
    */

    'menu' => [
        'dashboard' => [
            'url' => 'dashboard',
            'icon' => '',
            'text' => 'Dashboard',
            'in_menu' => true,
            'sub_url'  => null
            ],
        'branch' => [
            'url' => 'merchant/branch',
            'icon' => '',
            'text' => 'Dashboard',
            'in_menu' => true,
            'sub_url'  => null
        ],
        'task' => [

        ],
        'campaign' => [

        ],
        'ticket' => [

        ]
    ],

];
