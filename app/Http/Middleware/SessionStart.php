<?php

namespace App\Http\Middleware;

use App\Request;
use Closure;

class SessionStart
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

}