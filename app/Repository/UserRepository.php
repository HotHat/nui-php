<?php
namespace app\Repository;


use Nui\Facade\DB;

class UserRepository
{
    use \Nui\InstanceTrait;
    public function findById($id) {
        $user = DB::table('user')->find($id);
        return $user ? (array)$user : null;
        // return DB::instance()->fetchOne('select * from user where id=?', [$id]);
    }

    public function findByName($username) {
        $user =  DB::table('user')->where('username', $username)->first();
        return $user ? (array)$user : null;
        // return DB::instance()->fetchOne('select * from user where username=?', [$username]);
    }

}