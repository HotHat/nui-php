<?php

namespace App\Http\Controller;

use App\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class LoginController
{
    public function login(Request $request): bool|string
    {
        $username = $request->post('username');
        $password = $request->post('password');

        $cfg = config('jwt');
        $payload = [
            'iat' => time(),
            'exp' => time() + $cfg['jwt_expired'],
            'id' => 1,
        ];

        $jwt = JWT::encode($payload, $cfg['jwt_key'], 'HS256');
        return respSuccess([
            'token' => $jwt
        ]);
    }

    public function submit($request) {

        // session login
        // authLogin(['id' => 1, 'name' => 'admin']);

        return respSuccess();
    }

    public function logout() {

    }
}