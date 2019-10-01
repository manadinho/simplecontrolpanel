<?php

namespace Wikichua\LaravelAdminPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\LaravelAdminPanel\Traits\DynamicFillable;
use Wikichua\LaravelAdminPanel\Traits\UserTimezone;

class Setting extends Model
{
    use DynamicFillable, UserTimezone;
}