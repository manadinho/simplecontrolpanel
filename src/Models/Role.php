<?php

namespace Wikichua\LaravelAdminPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\LaravelAdminPanel\Traits\DynamicFillable;
use Wikichua\LaravelAdminPanel\Traits\UserTimezone;

class Role extends Model
{
    use DynamicFillable, UserTimezone;

    // permissions relationship
    public function permissions()
    {
        return $this->belongsToMany(config('lap.models.permission'));
    }
}