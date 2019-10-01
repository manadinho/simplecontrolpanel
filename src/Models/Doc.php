<?php

namespace Wikichua\LaravelAdminPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Wikichua\LaravelAdminPanel\Traits\DynamicFillable;
use Wikichua\LaravelAdminPanel\Traits\UserTimezone;
use Parsedown;

class Doc extends Model
{
    use DynamicFillable, UserTimezone, NodeTrait;

    public function markdown()
    {
        return (new Parsedown())->text($this->content);
    }
}