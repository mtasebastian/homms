<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Auth;

class RouteChecker
{
    public function routePermission($routeName)
    {
        $user_permissions = Auth::user()->role->routes();
        $list = json_decode(json_encode($user_permissions), true);
        if(in_array($routeName, $list)){
            return true;
        }
        return false;
    }
}