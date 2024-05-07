<?php

namespace App;

use app\Repository\UserRepository;
use Nui\Exception\AuthenticationException;
use Nui\InstanceTrait;

class Auth
{
    use InstanceTrait;
    private mixed $loginUser;
    public function user() {
        return $this->loginUser;
    }

    public function loginById($id) {
        // fetch user from db by $id
        $user = UserRepository::instance()->findById($id);
        throw_if(empty($user), AuthenticationException::class);
        $this->loginUser = $id;
    }


}