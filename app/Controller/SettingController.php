<?php

namespace App\Controller;

use App\Database\DB;

class SettingController
{
    public function __construct()
    {
        checkLogin();
    }

    public function index() {
        return renderString('admin/setting.php');
    }

    public function all() {
        return respSuccess([
            "timeLocation" => "Asia/Shanghai"
        ]);
    }

    public function update() {
       return respSuccess();
    }

    public function updateUser($request) {
        $s = $request->post();

        $user = DB::instance()->fetchOne('select * from user where username=?', [$s['oldUsername'] ?? '']);
        if (empty($user)) { return respFail('没有此用户'); }
        if (!hashVerify($s['oldPassword'] ?? '', $user['password'] ?? '')) { return respFail('原密码错误'); }

        if (empty($s['newUsername']) || empty($s['newPassword'])) { return respFail('用户名和密码不能为空'); }

        DB::instance()->update(
            'UPDATE user SET username=?, password=? WHERE username=?',
            [
                $s['newUsername'],
                hashMake($s['newPassword']),
                $s['oldUsername']
            ]
        );

        return respSuccess();
    }
}