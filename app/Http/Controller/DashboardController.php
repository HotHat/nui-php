<?php

namespace App\Http\Controller;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class DashboardController
{
    public function dashboard() {
        // $top = shell_exec('cat /proc/uptime');
        // var_dump($top);
        // print_r($this->getDiskUsage());
        // die();
        // $data = auth_user();
        // $data = DB::instance()->fetchOne('select * from exclusive_carts limit 1');
        // $data = DB::instance()->fetchAll('select * from exclusive_carts ');
        // $data = DB::instance()->fetchAll('select * from exclusive_carts where id=? and store_id=?', [65, 4]);
        return respSuccess();
    }


}