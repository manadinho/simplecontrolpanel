<?php

return [
    'route_prefix' => 'admin',
    'controllers' => [
        'auth' => [
            'change_password' => 'Wikichua\Simplecontrolpanel\Controllers\Auth\ChangePasswordController',
            'forgot_password' => 'Wikichua\Simplecontrolpanel\Controllers\Auth\ForgotPasswordController',
            'login' => 'Wikichua\Simplecontrolpanel\Controllers\Auth\LoginController',
            'profile' => 'Wikichua\Simplecontrolpanel\Controllers\Auth\ProfileController',
            'reset_password' => 'Wikichua\Simplecontrolpanel\Controllers\Auth\ResetPasswordController',
        ],
        'activity_log' => 'Wikichua\Simplecontrolpanel\Controllers\ActivityLogController',
        'backend' => 'Wikichua\Simplecontrolpanel\Controllers\BackendController',
        'doc' => 'Wikichua\Simplecontrolpanel\Controllers\DocController',
        'role' => 'Wikichua\Simplecontrolpanel\Controllers\RoleController',
        'permission' => 'Wikichua\Simplecontrolpanel\Controllers\PermissionController',
        'setting' => 'Wikichua\Simplecontrolpanel\Controllers\SettingController',
        'user' => 'Wikichua\Simplecontrolpanel\Controllers\UserController',
    ],

    // models used by package
    'models' => [
        'activity_log' => 'Wikichua\Simplecontrolpanel\Models\ActivityLog',
        'doc' => 'Wikichua\Simplecontrolpanel\Models\Doc',
        'permission' => 'Wikichua\Simplecontrolpanel\Models\Permission',
        'role' => 'Wikichua\Simplecontrolpanel\Models\Role',
        'setting' => 'Wikichua\Simplecontrolpanel\Models\Setting',
        'user' => 'Wikichua\Simplecontrolpanel\Models\User',
    ],

    'crud_paths' => [
        'stubs' => 'vendor/wikichua/simplecontrolpanel/resources/stubs/crud/default',
        'controller' => 'app/Http/Controllers/Admin',
        'model' => 'app',
        'migrations' => 'database/migrations',
        'views' => 'resources/views/admin',
        'menu' => 'resources/views/vendor/lap/layouts/menu.blade.php',
        'routes' => 'routes/web.php',
    ],

];