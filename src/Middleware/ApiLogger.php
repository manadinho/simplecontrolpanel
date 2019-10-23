<?php

namespace Wikichua\Simplecontrolpanel\Middleware;

use Closure;

class ApiLogger
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        \Storage::append('../logs/api/log-'.date('Y-m').'.log', json_encode([date('Y-m-d H:i:s') => ['request' => $request->all()]]));
    }
}
