<?php

namespace App\Http\Controller;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class DashboardController
{
    public function dashboard() {
        return respSuccess();
    }


}