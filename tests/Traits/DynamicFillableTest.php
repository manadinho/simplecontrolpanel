<?php

namespace Wikichua\LaravelAdminPanel\Tests\Traits;

use Wikichua\LaravelAdminPanel\Tests\TestCase;

class DynamicFillableTest extends TestCase
{
    public function test_has_fillable()
    {
        $fillable = app(config('lap.models.role'))->getFillable();
        $this->assertContains('id', $fillable);
        $this->assertContains('name', $fillable);
        $this->assertContains('admin', $fillable);
    }
}