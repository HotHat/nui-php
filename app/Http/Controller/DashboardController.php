<?php

namespace App\Http\Controller;

use App\Auth;
use Niu\Request;

class DashboardController
{
    public function dashboard(Request $request) {
        return respSuccess(Auth::instance()->user());
    }


}