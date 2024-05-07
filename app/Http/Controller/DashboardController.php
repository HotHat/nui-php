<?php

namespace App\Http\Controller;

use App\Auth;
use Nui\Request;

class DashboardController
{
    public function dashboard(Request $request) {
        return respSuccess(Auth::instance()->user());
    }


}