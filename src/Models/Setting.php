<?php

namespace Wikichua\SimpleControlPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\SimpleControlPanel\Traits\DynamicFillable;
use Wikichua\SimpleControlPanel\Traits\UserTimezone;

class Setting extends Model
{
    use DynamicFillable, UserTimezone;

    public function getValueAttribute($value)
    {
    	if (json_decode($value)) {
    		return json_decode($value,1);
    	}
    	return $value;
    }
    public function setValueAttribute($value)
    {
    	if (is_array($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }
}