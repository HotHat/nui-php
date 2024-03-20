<?php

namespace App\Http\Controller;

class LoginController
{
    public function login($req) {

        return respSuccess(
            'not html render'
        );
    }

    public function submit($request) {
        $username = $request->post('username');
        $password = $request->post('password');

        // $user = DB::instance()->fetchOne('select rowid as id, * from user where username=?', [$username]);
        //
        // if (empty($user)) {
        //     return respFail('登录失败111');
        // }
        //
        // if (!hashVerify($password, $user['password'])) {
        //     return respFail('登录失败222');
        // }

        // session login
        authLogin(['id' => 1, 'name' => 'admin']);

        return respSuccess();
    }

    public function logout() {
        sessionFlush();
        redirect('/login');
    }
}