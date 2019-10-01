<?php

namespace Wikichua\LaravelAdminPanel\Tests\Middleware;

use Wikichua\LaravelAdminPanel\Tests\TestCase;

class IntendUrlTest extends TestCase
{
    public function test_session_has_url_intended()
    {
        $response = $this->actingAs($this->user_admin)->get(route('admin.roles'));
        $response->assertSessionHas('url.intended', route('admin.roles'));
    }
}