<?php

namespace App\Http\Middleware;

use Closure;
use Niu\Request;

class SessionStart
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

}