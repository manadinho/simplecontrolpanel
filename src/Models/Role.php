<?php

namespace Wikichua\Simplecontrolpanel\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\Simplecontrolpanel\Traits\DynamicFillable;
use Wikichua\Simplecontrolpanel\Traits\UserTimezone;

class Role extends Model
{
    use DynamicFillable, UserTimezone;

    // permissions relationship
    public function permissions()
    {
        return $this->belongsToMany(config('lap.models.permission'));
    }
}