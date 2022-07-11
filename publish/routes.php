<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf Admin
 */
use Hyperf\HttpServer\Router\Router;

Router::addGroup(admin_url(), function (){
    // Home
    Router::get('', [\App\Admin\Controller\HomeController::class, 'index']);

    // Auth management
    Router::addGroup('/auth', function (){
        Router::addRoute(['GET', 'POST'], '/login', [\Oyhdd\Admin\Controller\AuthController::class, 'login']);
        Router::get('/logout', [\Oyhdd\Admin\Controller\AuthController::class, 'logout']);
        Router::get('/lock', [\Oyhdd\Admin\Controller\AuthController::class, 'lock']);
        Router::post('/unlock', [\Oyhdd\Admin\Controller\AuthController::class, 'unlock']);
        Router::addRoute(['GET', 'POST'], '/setting', [\Oyhdd\Admin\Controller\AuthController::class, 'setting']);

        // User
        Router::addGroup('/user', function (){
            Router::get('', [\Oyhdd\Admin\Controller\UserController::class, 'index']);
            Router::post('/export', [\Oyhdd\Admin\Controller\UserController::class, 'export']);
            Router::post('/delete', [\Oyhdd\Admin\Controller\UserController::class, 'delete']);
            Router::addRoute(['GET', 'POST'], '/create', [\Oyhdd\Admin\Controller\UserController::class, 'create']);
            Router::get('/{id}', [\Oyhdd\Admin\Controller\UserController::class, 'show']);
            Router::addRoute(['GET', 'POST'], '/{id}/edit', [\Oyhdd\Admin\Controller\UserController::class, 'edit']);
        });

        // Role
        Router::addGroup('/role', function (){
            Router::get('', [\Oyhdd\Admin\Controller\RoleController::class, 'index']);
            Router::post('/export', [\Oyhdd\Admin\Controller\RoleController::class, 'export']);
            Router::post('/delete', [\Oyhdd\Admin\Controller\RoleController::class, 'delete']);
            Router::addRoute(['GET', 'POST'], '/create', [\Oyhdd\Admin\Controller\RoleController::class, 'create']);
            Router::get('/{id}', [\Oyhdd\Admin\Controller\RoleController::class, 'show']);
            Router::addRoute(['GET', 'POST'], '/{id}/edit', [\Oyhdd\Admin\Controller\RoleController::class, 'edit']);
        });

        // Develop tool
        Router::addGroup('/site', function (){
            Router::addRoute(['GET', 'POST'], '/edit', [\Oyhdd\Admin\Controller\SiteController::class, 'edit']);
    });


}, [
    'middleware' => [
        \Hyperf\Session\Middleware\SessionMiddleware::class,
        \Oyhdd\Admin\Middleware\CsrfTokenMiddleware::class,
        \Oyhdd\Admin\Middleware\AuthMiddleware::class,
        \Oyhdd\Admin\Middleware\PermissionMiddleware::class,
        \Oyhdd\Admin\Middleware\LockMiddleware::class,
    ]
]);