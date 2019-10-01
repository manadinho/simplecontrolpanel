<?php

namespace Wikichua\SimpleControlPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\SimpleControlPanel\Traits\DynamicFillable;
use Wikichua\SimpleControlPanel\Traits\UserTimezone;

class Role extends Model
{
    use DynamicFillable, UserTimezone;

    // permissions relationship
    public function permissions()
    {
        return $this->belongsToMany(config('lap.models.permission'));
    }
}