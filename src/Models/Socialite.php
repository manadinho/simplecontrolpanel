<?php

namespace Wikichua\Simplecontrolpanel\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Socialite extends Authenticatable
{
    use Notifiable;

    protected $guard = 'social';

    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'provider_id', 'provider', 'access_token',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
