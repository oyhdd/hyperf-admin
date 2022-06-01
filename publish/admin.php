<?php

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
    'name' => 'Hyperf Admin',

    /*
    |--------------------------------------------------------------------------
    | Hyperf-admin html title
    |--------------------------------------------------------------------------
    */
    'title' => 'Hyperf Admin',

    /*
    |--------------------------------------------------------------------------
    | Hyperf-admin logo mini
    |--------------------------------------------------------------------------
    */
    'logo_mini' => '<b>HA</b>',

    /*
    |--------------------------------------------------------------------------
    | Hyperf-admin logo
    |--------------------------------------------------------------------------
    */
    'logo' => '<b>Hyperf Admin</b>',

    /*
    |--------------------------------------------------------------------------
    | The default avatar of current user.
    |--------------------------------------------------------------------------
    */
    'default_avatar' => '/vendor/hyperf-admin/AdminLTE/dist/img/avatar.png',

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
    'layout' => ['sidebar-mini', 'layout-fixed'],

    /*
    |--------------------------------------------------------------------------
    | Show the footer page
    |--------------------------------------------------------------------------
    */
    'show_footer' => true,

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
    'version' => '1.0.0',

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

    /*
    |--------------------------------------------------------------------------
    | hyperf-admin install directory
    |--------------------------------------------------------------------------
    |
    | The installation directory of the controller and routing configuration
    | files of the administration page. The default is `app/Admin`, which must
    | be set before running `artisan admin::install` to take effect.
    |
    */
    'directory' => 'app/Admin',

    /*
    |--------------------------------------------------------------------------
    | hyperf-admin route settings
    |--------------------------------------------------------------------------
    |
    | The routing configuration of the admin page, including the path prefix,
    | the controller namespace, and the default middleware. If you want to
    | access through the root path, just set the prefix to empty string.
    |
    */
    'route' => [
        'namespace' => 'App\\Admin\\Controller',

        'prefix' => 'admin',
    ],

    /*
    |--------------------------------------------------------------------------
    | CSRF token
    |--------------------------------------------------------------------------
    */
    'csrf_token' => [
        'enable' => true,

        // All method to path like: auth/*
        'except' => [
            // 'auth/*',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Time to live
    |--------------------------------------------------------------------------
    */
    'ttl' => 3600 * 24 * 14,

    /*
    |--------------------------------------------------------------------------
    | hyperf-admin database settings
    |--------------------------------------------------------------------------
    |
    | Here are database settings for hyperf-admin builtin model & tables.
    |
    */
    'database' => [

        // Database connection for following tables.
        'connection' => 'default',

        // User tables and model.
        'user_table' => 'admin_user',
        'user_model' => Oyhdd\Admin\Model\AdminUser::class,

        // Role table and model.
        'role_table' => 'admin_role',
        'role_model' => Oyhdd\Admin\Model\AdminRole::class,

        // Permission table and model.
        'permission_table' => 'admin_permission',
        'permission_model' => Oyhdd\Admin\Model\AdminPermission::class,

        // Menu table and model.
        'menu_table' => 'admin_menu',
        'menu_model' => Oyhdd\Admin\Model\AdminMenu::class,

        // Operation log table and model.
        'operation_log_table' => 'admin_operation_log',
        // 'operation_log_model' => Oyhdd\Admin\Model\AdminOperationLog::class,

        // Site table and model.
        'site_table' => 'admin_site',
        'site_model' => Oyhdd\Admin\Model\AdminSite::class,

        // Pivot table for table above.
        'role_user_table'       => 'admin_role_user',
        'role_permission_table' => 'admin_role_permission',
        'role_menu_table'        => 'admin_role_menu',
    ],
];
