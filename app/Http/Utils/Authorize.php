<?php

namespace App\Http\Utils;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class Authorize
{

    public function checkAdmin() {

        $is_admin = (new Roles)->AdminRoles();

        if (Auth::guard('admin')->user() && in_array(Auth::guard('admin')->user()->role, $is_admin))
            return true;
        return false;
    }

    public function checkUser() {
    
        $is_user = (new Roles)->UserRoles();
        
        if (in_array(Auth::guard('user')->user()->role, $is_user))
            return true;
        return false;
    }

    public function checkNotLogin() {
     
        if (!Auth::guard('user')->user())
            return true;
        return false;
    }


}
