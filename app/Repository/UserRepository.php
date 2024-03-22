<?php
namespace app\Repository;

use Niu\Database\DB;

class UserRepository
{
    use \Niu\InstanceTrait;
    public function findById($id) {
        return DB::instance()->fetchOne('select * from user where id=?', [$id]);
    }

    public function findByName($username) {
        return DB::instance()->fetchOne('select * from user where username=?', [$username]);
    }

}