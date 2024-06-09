<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provinces;
use App\Models\Cities;
use App\Models\Barangays;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $url = "https://raw.githubusercontent.com/flores-jacob/philippine-regions-provinces-cities-municipalities-barangays/master/philippine_provinces_cities_municipalities_and_barangays_2019v2.json";
        $client = new \GuzzleHttp\Client();
        $response = $client->request("GET", $url);
        $arr = json_decode($response->getBody(), true);
        foreach($arr as $i => $item){
            foreach($item["province_list"] as $p => $province){
                $pitem = new Provinces();
                $pitem->name = $p;
                $pitem->save();
                foreach($province["municipality_list"] as $c => $city){
                    $citem = new Cities();
                    $citem->province_id = $pitem->id;
                    $citem->name = $c;
                    $citem->save();
                    foreach($city["barangay_list"] as $brgy){
                        $bitem = new Barangays();
                        $bitem->city_id = $citem->id;
                        $bitem->name = $brgy;
                        $bitem->save();
                    }
                }
            }
        }
    }
}
