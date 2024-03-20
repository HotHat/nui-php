<?php

namespace App\Http\Controller;

class LoginController
{
    public function login($req) {
        return respSuccess();
    }

    public function submit($request) {
        $username = $request->post('username');
        $password = $request->post('password');

        // session login
        authLogin(['id' => 1, 'name' => 'admin']);

        return respSuccess();
    }

    public function logout() {

    }
}