<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

    public static function routeList(){
        $notr = [
            'financials.search_resident',
        ];
        $routeCol = Route::getRoutes();
        $middleware = 'auth';
        $routelist = [];
        foreach($routeCol as $route){
            if(in_array($middleware, $route->middleware())){
                $routelist[] = $route;
            }
        }
        $routes = [];
        foreach($routelist as $key => $route){
            if(!in_array($route->getName(), $notr) && $route->getName() != ""){
                $rname = explode(".", $route->getName());
                $routes[$rname[0]][$key] = $route->getName();
            }
        }
        return $routes;
    }
}