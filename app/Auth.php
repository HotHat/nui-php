<?php

namespace App;

class Auth
{
    use InstanceTrait;
    private mixed $loginUser;
    public function user() {
        return $this->loginUser;
    }

    public function loginById($id) {
        $this->loginUser = $id;
    }


}