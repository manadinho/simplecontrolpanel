<?php

return [
    'controllers' => [
        'auth' => [
            'change_password' => 'Wikichua\SimpleControlPanel\Controllers\Auth\ChangePasswordController',
            'forgot_password' => 'Wikichua\SimpleControlPanel\Controllers\Auth\ForgotPasswordController',
            'login' => 'Wikichua\SimpleControlPanel\Controllers\Auth\LoginController',
            'profile' => 'Wikichua\SimpleControlPanel\Controllers\Auth\ProfileController',
            'reset_password' => 'Wikichua\SimpleControlPanel\Controllers\Auth\ResetPasswordController',
        ],
        'activity_log' => 'Wikichua\SimpleControlPanel\Controllers\ActivityLogController',
        'backend' => 'App\Http\Controllers\Admin\BackendController',
        'doc' => 'Wikichua\SimpleControlPanel\Controllers\DocController',
        'role' => 'Wikichua\SimpleControlPanel\Controllers\RoleController',
        'permission' => 'Wikichua\SimpleControlPanel\Controllers\PermissionController',
        'setting' => 'Wikichua\SimpleControlPanel\Controllers\SettingController',
        'user' => 'Wikichua\SimpleControlPanel\Controllers\UserController',
    ],

    // models used by package
    'models' => [
        'activity_log' => 'Wikichua\SimpleControlPanel\Models\ActivityLog',
        'doc' => 'Wikichua\SimpleControlPanel\Models\Doc',
        'permission' => 'Wikichua\SimpleControlPanel\Models\Permission',
        'role' => 'Wikichua\SimpleControlPanel\Models\Role',
        'setting' => 'Wikichua\SimpleControlPanel\Models\Setting',
        'user' => 'Wikichua\SimpleControlPanel\Models\User',
    ],

];