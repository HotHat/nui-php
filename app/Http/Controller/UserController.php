<?php

namespace App\Http\Controller;

use App\Database\DB;
use function App\Controller\checkLogin;
use function App\Controller\render;

class UserController
{
    public function __construct()
    {
        checkLogin();
    }

    public function index()
    {
        render('admin/user.php');
    }

    public function list()
    {
        $data = DB::instance()->query('select rowid as id, * from user order by id desc');
        $result = [];
        foreach ($data as $it) {
            $result[] = [
                'id' => $it['id'],
                'username' => $it['username'],
                'role' => $it['role'],
                'remark' => $it['remark'],
                'created_at' => $it['created_at'],
                'status' => $it['status'],

            ];
        }
        return respSuccess($result, true);
    }

    public function add($request)
    {
        $s = $request->post();

        DB::beginTransaction();

        $bind = [
            'username' => $s['username'],
            'password' => hashMake($s['password']),
            'role' => $s['role'],
            'remark' => $s['remark'],
            'status' => $s['status'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        DB::instance()->insert(
            'INSERT INTO user (username, password, role, remark, status, created_at) values (?, ?, ?, ?, ?, ?)',
            array_values($bind)
        );

        DB::commit();

        return respSuccess();
    }

    public function update($request)
    {
        $s = $request->post();

        $bind = [
            'username' => $s['username'],
            'role' => $s['role'],
            'remark' => $s['remark'],
            'status' => ($s['status'] == 'true' ? 1 : 0),
            'created_at' => date('Y-m-d H:i:s')
        ];

        if (!empty($s['password'])) {
            $bind['password'] = $s['password'];
        }

        $keys = array_keys($bind);
        $setP = implode(', ', array_map(function ($it) { return $it . '=?'; }, $keys));

        DB::beginTransaction();

        $bind['rowid'] = intval($_GET['id']);

        DB::instance()->update(
            "UPDATE user SET $setP WHERE rowid=?",
            array_values($bind)
        );

        DB::commit();;

        return respSuccess();
    }

    public function del()
    {
        $id = intval($_GET['id']);

        if ($id == 1) {
            return respFail('超级管理员不能删除');
        }

        DB::beginTransaction();

        DB::instance()->update('DELETE FROM user WHERE rowid=?', [$id]);

        DB::commit();

        return respSuccess();
    }

}