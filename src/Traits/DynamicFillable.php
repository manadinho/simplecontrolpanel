<?php

namespace Wikichua\Simplecontrolpanel\Traits;

use Illuminate\Support\Facades\Schema;

trait DynamicFillable
{
    // set fillable using db table columns
    public function getFillable()
    {
        return Schema::getColumnListing($this->getTable());
    }
}