<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Modules;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = ["Dashboard", "Financials", "Requests", "Residents", "Complaints", "Visitors"];
        foreach($arr as $item){
            $module = new Modules();
            $module->name = $item;
            $module->status = 1;
            $module->save();
        }
    }
}
