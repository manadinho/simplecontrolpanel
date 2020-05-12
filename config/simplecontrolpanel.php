<?php

return [
    'route_prefix' => 'admin',
    'widgets_namespace' => '\App\Widgets',
    'widgets_path' => 'app/Widgets',
    'controllers' => [
        'auth' => [
            'change_password' => '\Wikichua\Simplecontrolpanel\Controllers\Auth\ChangePasswordController',
            'forgot_password' => '\Wikichua\Simplecontrolpanel\Controllers\Auth\ForgotPasswordController',
            'login' => '\Wikichua\Simplecontrolpanel\Controllers\Auth\LoginController',
            'profile' => '\Wikichua\Simplecontrolpanel\Controllers\Auth\ProfileController',
            'reset_password' => '\Wikichua\Simplecontrolpanel\Controllers\Auth\ResetPasswordController',
            'socialite' => '\Wikichua\Simplecontrolpanel\Controllers\Auth\SocialiteLoginController',
        ],
        'activity_log' => '\Wikichua\Simplecontrolpanel\Controllers\ActivityLogController',
        'backend' => '\Wikichua\Simplecontrolpanel\Controllers\BackendController',
        'frontend' => '\Wikichua\Simplecontrolpanel\Controllers\FrontendController',
        'doc' => '\Wikichua\Simplecontrolpanel\Controllers\DocController',
        'role' => '\Wikichua\Simplecontrolpanel\Controllers\RoleController',
        'permission' => '\Wikichua\Simplecontrolpanel\Controllers\PermissionController',
        'setting' => '\Wikichua\Simplecontrolpanel\Controllers\SettingController',
        'user' => '\Wikichua\Simplecontrolpanel\Controllers\UserController',
        'socialite' => '\Wikichua\Simplecontrolpanel\Controllers\SocialiteController',
        'seotool' => '\Wikichua\Simplecontrolpanel\Controllers\SeotoolController',
        'api' => '\Wikichua\Simplecontrolpanel\Controllers\ApiController',
    ],

    // models used by package
    'models' => [
        'activity_log' => '\Wikichua\Simplecontrolpanel\Models\ActivityLog',
        'doc' => '\Wikichua\Simplecontrolpanel\Models\Doc',
        'permission' => '\Wikichua\Simplecontrolpanel\Models\Permission',
        'role' => '\Wikichua\Simplecontrolpanel\Models\Role',
        'setting' => '\Wikichua\Simplecontrolpanel\Models\Setting',
        'user' => '\Wikichua\Simplecontrolpanel\Models\User',
        'socialite' => '\Wikichua\Simplecontrolpanel\Models\Socialite',
        'seotool' => '\Wikichua\Simplecontrolpanel\Models\Seotool',
    ],

    'crud_paths' => [
        'stubs' => 'vendor/wikichua/simplecontrolpanel/resources/stubs/crud/default',
        'controller' => 'app/Http/Controllers/Admin',
        'model' => 'app',
        'migrations' => 'database/migrations',
        'views' => 'resources/views/admin',
        'menu' => 'resources/views/vendor/lap/layouts/menu',
        'layout_menu' => 'resources/views/vendor/lap/layouts/menu.blade.php',
        'route' => 'routes/admin',
        'routes' => 'routes/web.php',
    ],

    'modules' => [
        'Admin Panel',
        'Roles',
        'Users',
        'Activity Logs',
        'Docs',
        'Settings',
    ],
    'init_seo' => false,

];