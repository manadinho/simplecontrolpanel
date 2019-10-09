<?php

namespace Wikichua\Simplecontrolpanel\Middleware;

use Closure;

class ApiConstructor
{
    public function handle($request, Closure $next)
    {
		// $api_token = explode(' ', $request->header('authorization'))[1];
		// $this->user = app(config('auth.providers.users.model'))->query()->where('api_token',$api_token)->first();
		dd('here');
        return $next($request);
    }
}
