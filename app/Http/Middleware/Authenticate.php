<?php

namespace App\Http\Middleware;

use App\Request;
use Closure;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        // if (!in_array($request->uri(), ['/login'])) {
        //     $user = $request->session()->get('auth_user');
        //     if (!$user) {
        //         return respFail('Not Auth', [], 401);
        //     }
        // }

        return $next($request);
    }
}
