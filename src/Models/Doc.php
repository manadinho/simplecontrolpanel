<?php

namespace Wikichua\Simplecontrolpanel\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Wikichua\Simplecontrolpanel\Traits\DynamicFillable;
use Wikichua\Simplecontrolpanel\Traits\UserTimezone;
use Parsedown;

class Doc extends Model
{
    use DynamicFillable, UserTimezone, NodeTrait;

    public function markdown()
    {
        return (new Parsedown())->text($this->content);
    }
}