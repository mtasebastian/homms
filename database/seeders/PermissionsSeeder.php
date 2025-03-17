<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Helpers\RouteChecker;
use App\Models\RolePermissions;
use Carbon\Carbon;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $routes = RouteChecker::routeList();
        foreach($routes as $i => $route_group){
            if($i != "chat"){
                foreach($route_group as $route){
                    RolePermissions::insert([
                        'role_id' => '1',
                        'route' => $route,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }
        }
    }
}
