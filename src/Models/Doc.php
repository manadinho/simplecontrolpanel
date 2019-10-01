<?php

namespace Wikichua\SimpleControlPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Wikichua\SimpleControlPanel\Traits\DynamicFillable;
use Wikichua\SimpleControlPanel\Traits\UserTimezone;
use Parsedown;

class Doc extends Model
{
    use DynamicFillable, UserTimezone, NodeTrait;

    public function markdown()
    {
        return (new Parsedown())->text($this->content);
    }
}