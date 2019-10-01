<?php

return [

    'demo' => [
        // enable/disable demo mode
        'enabled' => env('DEMO_ENABLED', false),

        // allow routes with the following methods or uris in demo mode
        'whitelist' => [
            'methods' => ['get'],
            'routes' => ['admin/login', 'admin/logout'],
        ],

        // demo user credentials (populates login form in demo mode)
        'user' => [
            'email' => env('DEMO_USER_EMAIL', 'admin@example.com'),
            'password' => env('DEMO_USER_PASSWORD', 'admin123'),
        ],
    ],

    // controllers used by package
    'controllers' => [
        'activity_log' => 'Wikichua\LaravelAdminPanel\Controllers\ActivityLogController',
        'auth' => [
            'change_password' => 'Wikichua\LaravelAdminPanel\Controllers\Auth\ChangePasswordController',
            'forgot_password' => 'Wikichua\LaravelAdminPanel\Controllers\Auth\ForgotPasswordController',
            'login' => 'Wikichua\LaravelAdminPanel\Controllers\Auth\LoginController',
            'profile' => 'Wikichua\LaravelAdminPanel\Controllers\Auth\ProfileController',
            'reset_password' => 'Wikichua\LaravelAdminPanel\Controllers\Auth\ResetPasswordController',
        ],
        'backend' => 'App\Http\Controllers\Admin\BackendController',
        'doc' => 'Wikichua\LaravelAdminPanel\Controllers\DocController',
        'role' => 'Wikichua\LaravelAdminPanel\Controllers\RoleController',
        'user' => 'Wikichua\LaravelAdminPanel\Controllers\UserController',
    ],

    // models used by package
    'models' => [
        'activity_log' => 'Wikichua\LaravelAdminPanel\Models\ActivityLog',
        'doc' => 'Wikichua\LaravelAdminPanel\Models\Doc',
        'permission' => 'Wikichua\LaravelAdminPanel\Models\Permission',
        'role' => 'Wikichua\LaravelAdminPanel\Models\Role',
        'setting' => 'Wikichua\LaravelAdminPanel\Models\Setting',
    ],

];