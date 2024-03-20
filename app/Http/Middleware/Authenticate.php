<?php

namespace App\Http\Middleware;

use App\Auth;
use App\Request;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Authenticate
{
    private array $whitelist = [];
    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->getUri(), $this->whitelist)) {
            $token = $request->bearerToken();
            $cfg = config('jwt');
            try {
                $data = JWT::decode($token, new Key($cfg['jwt_key'], 'HS256'));

                // login
                Auth::instance()->loginById($data);

            } catch (\Exception $e) {
                return respFail('auth fail', [], 401);
            }
        }

        return $next($request);
    }
}
