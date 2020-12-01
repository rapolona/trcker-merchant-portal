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
    | STATUS COLOR
    |--------------------------------------------------------------------------
    */
    'status' =>[
        'ONGOING' => 'primary',
        'UNDER REVIEW' => 'warning',
        'DISABLED' => 'light',
        'COMPLETED' => 'success',
        'PENDING' => 'dark',
    ],

    'ticket_status' =>[
        'Pending' => 'primary',
        'PENDING' => 'primary',
        'REJECTED' => 'danger',
        'APPROVED' => 'success',
    ],
    /*
    |--------------------------------------------------------------------------
    | MENU / BREADCRUMBS
    |--------------------------------------------------------------------------
    */

    'menu' => [
        'dashboard' => [
            'url' => 'dashboard',
            'icon' => 'fa-home',
            'text' => 'Dashboard',
            'in_menu' => true,
            'sub_url'  => null
            ],
        'merchant' => [
            'branch' => [
                'url' => 'merchant/branches',
                'icon' => 'fa-sitemap',
                'text' => 'Branches',
                'in_menu' => true,
                'sub_url'  => [
                    'add' => [ 'text' => 'Add'],
                    'edit' => [ 'text' => 'Update']
                ]
            ],
            'branches' => [
                'url' => 'merchant/branches',
                'icon' => 'fa-sitemap',
                'text' => 'Branches',
                'in_menu' => true,
                'sub_url'  => [
                    'add' => [ 'text' => 'Add'],
                    'edit' => [ 'text' => 'Update']
                ]
            ],
            'product' => [
                'url' => 'merchant/product',
                'icon' => 'fa-sitemap',
                'text' => 'Products',
                'in_menu' => true,
                'sub_url'  => [
                    'add' => [ 'text' => 'Add'],
                    'edit' => [ 'text' => 'Update']
                ]
            ],

            'users' => [
                'url' => 'merchant/users',
                'icon' => 'fa-sitemap',
                'text' => 'User',
                'in_menu' => true,
                'sub_url'  => [
                    'add' => [ 'text' => 'Add'],
                    'edit' => [ 'text' => 'Update']
                ]
            ],

            'profile' => [
                'url' => 'merchant/profile',
                'icon' => 'fa-sitemap',
                'text' => 'Merchant Profile',
                'in_menu' => true,
                'sub_url'  => [
                    'add' => [ 'text' => 'Add'],
                    'edit' => [ 'text' => 'Update']
                ]
            ],
        ],
        'task' => [
            'url' => 'task/view',
            'icon' => 'fa-columns',
            'text' => 'Tasks',
            'in_menu' => true,
            'sub_url'  => [
                'add' => [ 'text' => 'Add'],
                'edit' => [ 'text' => 'Update']
            ]
        ],
        'campaign' => [
            'url' => 'merchant/branch',
            'icon' => 'fa-star',
            'text' => 'Campaigns',
            'in_menu' => true,
            'sub_url'  => [
                'add' => [ 'text' => 'Add'],
                'edit' => [ 'text' => 'Update']
            ]
        ],
        'ticket' => [
            'url' => 'ticket/view',
            'icon' => 'fa-server',
            'text' => 'Ticket Management',
            'in_menu' => true,
            'sub_url'  => [
                'add' => [ 'text' => 'Add'],
                'edit' => [ 'text' => 'Update']
            ]
        ]
    ],

];
