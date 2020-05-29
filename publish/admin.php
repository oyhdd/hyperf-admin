<?php declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Login page title
    |--------------------------------------------------------------------------
    |
    | This value is the name of Hyperf-admin, This setting is displayed on the
    | login page.
    |
    */
    'name' => '<span style="color:white"> 消息中心 </span>',

    /*
    |--------------------------------------------------------------------------
    | Hyperf-admin html title
    |--------------------------------------------------------------------------
    |
    | Html title for all pages.
    |
    */
    'title' => 'Admin',

    /*
    |--------------------------------------------------------------------------
    | Hyperf-admin logo
    |--------------------------------------------------------------------------
    |
    | The logo of all admin pages.
    |
    */
    'logo' => '/vendor/hyperf-admin/AdminLTE/dist/img/AdminLTELogo.png',

    /*
    |--------------------------------------------------------------------------
    | The default avatar of current user.
    |--------------------------------------------------------------------------
    */
    'default_avatar' => '/vendor/hyperf-admin/AdminLTE/dist/img/user2-160x160.jpg',

    /*
    |--------------------------------------------------------------------------
    | Login page background image
    |--------------------------------------------------------------------------
    |
    | This value is used to set the background image of login page.
    |
    */
    'login_background_image' => '/vendor/hyperf-admin/AdminLTE/dist/img/bg.jpeg',

    /*
    |--------------------------------------------------------------------------
    | Application layout
    |--------------------------------------------------------------------------
    |
    | This value is the layout of admin pages.
    |
    | Supported: "sidebar-mini", "layout-boxed", "layout-fixed", "layout-navbar-fixed", "layout-footer-fixed"
    |
    */
    'layout' => ['sidebar-mini', 'layout-fixed', 'text-sm'],

    /*
    |--------------------------------------------------------------------------
    | Lock screen page setting
    |--------------------------------------------------------------------------
    */
    'lockscreen' => [
        'enable'           => true,
        'background_image' => '/vendor/hyperf-admin/AdminLTE/dist/img/bg.jpeg',
    ],

    /*
    |--------------------------------------------------------------------------
    | The Version of hyperf-admin
    |--------------------------------------------------------------------------
    */
    'version' => '1.0.1',

    /*
    |--------------------------------------------------------------------------
    | User operation log setting
    |--------------------------------------------------------------------------
    |
    | By setting this option to open or close operation log in laravel-admin.
    |
    */
    'operation_log' => [

        'enable' => true,

        /*
         * Only logging allowed methods in the list
         */
        // 'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],
        'allowed_methods' => ['POST', 'PUT', 'DELETE'],

        /*
         * Routes that will not log to database.
         *
         * All method to path like: /admin/log
         * or /admin/log*
         */
        'except' => [
            'admin/log*',
        ],
    ],
];
