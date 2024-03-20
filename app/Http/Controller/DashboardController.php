<?php

namespace App\Http\Controller;

use App\Auth;
use App\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class DashboardController
{
    public function dashboard(Request $request) {
        return respSuccess(Auth::instance()->user());
    }


}