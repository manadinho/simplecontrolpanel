<?php

namespace Wikichua\Simplecontrolpanel\Traits;

use Illuminate\Auth\Notifications\ResetPassword;
use Wikichua\Simplecontrolpanel\Notifications\ResetAdminPassword;

trait AdminUser
{
    // roles relationship
    public function roles()
    {
        return $this->belongsToMany(config('lap.models.role'));
    }

    // permissions relationship
    public function permissions()
    {
        return $this->belongsToMany(config('lap.models.permission'));
    }

    // activity logs relationship
    public function activity_logs()
    {
        return $this->hasMany(config('lap.models.activity_log'));
    }

    // combined user + role permissions
    public function flatPermissions()
    {
        return $this->permissions->merge($this->roles->flatMap(function ($role) {
            return $role->permissions;
        }));
    }

    // check if user has permission
    public function hasPermission($name)
    {
        return $this->roles->contains('admin', true) || $this->flatPermissions()->contains('name', $name);
    }

    // use admin url in password reset email link
    public function sendPasswordResetNotification($token)
    {
        if (request()->route()->getName('admin.password.email')) {
            $this->notify(new ResetAdminPassword($token));
        }
        else {
            $this->notify(new ResetPassword($token));
        }
    }
}