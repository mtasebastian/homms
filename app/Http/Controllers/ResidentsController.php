<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Residents;
use App\Models\ResidentOccupants;
use App\Models\ResidentVehicles;
use App\Models\ResidentPets;
use App\Models\Provinces;
use App\Models\RefSetup;
use Carbon\Carbon;

class ResidentsController extends Controller
{
    public function index(Request $request)
    {
        $params = ['residents', 'provinces', 'refsetup'];
        $refsetup = RefSetup::whereIn("for", ["phase", "housecolor", "citizenship", "relation", "vehicletype", "pettype"])->with("referential")->get();
        $provinces = Provinces::orderBy("name", "ASC")->get();
        $query = Residents::with(['occupants', 'vehicles', 'pets']);
        $search = $request->txtresidentsearch;
        $datefrom = $request->txtresidentdatefrom;
        $dateto = $request->txtresidentdateto;

        if($search){
            $query->where(function ($q) use ($search) {
                $q->where('last_name', 'like', "%$search%")
                ->orWhere('first_name', 'like', "%$search%");
            });
            $searchkey = $search;
            array_push($params, ['searchkey']);
        }

        if($datefrom && !$dateto){
            $query->whereDate('created_at', Carbon::parse($datefrom)->format('Y-m-d'));
            array_push($params, ['datefrom']);
        }
        elseif(!$datefrom && $dateto){
            $query->whereDate('created_at', Carbon::parse($dateto)->format('Y-m-d'));
            array_push($params, ['dateto']);
        }
        elseif($datefrom && $dateto){
            $query->whereBetween(DB::raw("DATE(created_at)"), [
                Carbon::parse($datefrom)->format('Y-m-d'),
                Carbon::parse($dateto)->format('Y-m-d')
            ]);
            array_push($params, ['datefrom', 'dateto']);
        }

        $residents = $query->paginate(15);
        $residents->appends($request->except('page')); 
        return view("residents.index", compact($params));
    }

    public function addresident(Request $request)
    {
        $resident = New Residents();
        $resident->home_status = isset($request->resstatus) ? 1 : 0;
        $resident->renovated = isset($request->resrenovated) ? 1 : 0;
        $resident->phase = $request->resphase;
        $resident->house_number = $request->reshouseno;
        $resident->block = $request->resblock;
        $resident->lot = $request->reslot;
        $resident->province_id = $request->resprovince;
        $resident->city_id = $request->rescity;
        $resident->barangay_id = $request->resbarangay;
        $resident->street = $request->resstreet;
        $resident->unit_area = $request->resunitarea;
        $resident->move_in_date = Carbon::parse($request->resmoveindate)->format("Y-m-d");
        $resident->house_color = $request->reshousecolor;
        $resident->last_name = $request->reslastname;
        $resident->first_name = $request->resfirstname;
        $resident->middle_name = $request->resmiddlename;
        $resident->home_address = $request->reshomeaddress;
        $resident->other_address = $request->resotheraddress;
        $resident->mobile_number = $request->resmobilenumber;
        $resident->email_address = $request->resemailaddress;
        $resident->citizenship = $request->rescitizenship;
        $resident->date_of_birth = Carbon::parse($request->resdateofbirth)->format("Y-m-d");
        $resident->age = $request->resage;
        $resident->place_of_birth = $request->resplaceofbirth;
        $resident->civil_status = $request->rescivilstatus;
        $resident->gender = $request->resgender;
        $resident->occupation = $request->resoccupation;
        $resident->company_name = $request->rescompanyname;
        $resident->company_address = $request->rescompanyaddress;
        $resident->contact_person = $request->rescontactperson;
        $resident->contact_person_number = $request->rescontactpersonno;
        $resident->save();

        // Occupants
        $occupants = json_decode($request->resoccupantlist, true);
        if($occupants != null){
            foreach($occupants as $occupant){
                $tosave = new ResidentOccupants([
                    "last_name" => $occupant["lastname"],
                    "first_name" => $occupant["firstname"],
                    "middle_name" => $occupant["middlename"],
                    "age" => $occupant["age"],
                    "gender" => $occupant["gender"],
                    "relationship_to_homeowner" => $occupant["relation"],
                    "email_address" => $occupant["emailaddress"],
                    "mobile_number" => $occupant["mobilenumber"]
                ]);
                $resident->occupants()->save($tosave);
            }
        }

        // Vehicles
        $vehicles = json_decode($request->resvehiclelist, true);
        if($vehicles != null){
            foreach($vehicles as $vehicle){
                $tosave = new ResidentVehicles([
                    "type" => $vehicle["vehicletype"],
                    "brand" => $vehicle["brand"],
                    "model" => $vehicle["model"],
                    "plate_number" => $vehicle["platenumber"]
                ]);
                $resident->vehicles()->save($tosave);
            }
        }

        // Pets
        $pets = json_decode($request->respetlist, true);
        if($pets != null){
            foreach($pets as $pet){
                $tosave = new ResidentPets([
                    "type" => $pet["pettype"],
                    "breed" => $pet["breed"],
                    "name" => $pet["petname"],
                    "birth_date" => Carbon::parse($pet["birthdate"])->format("Y-m-d")
                ]);
                $resident->pets()->save($tosave);
            }
        }
        
        return redirect()->back()->with("success", "Resident has been added.");
    }

    public function updateresident(Request $request)
    {
        $resident = Residents::find($request->resid);
        $resident->home_status = isset($request->resstatus) ? 1 : 0;
        $resident->renovated = isset($request->resrenovated) ? 1 : 0;
        $resident->phase = $request->resphase;
        $resident->house_number = $request->reshouseno;
        $resident->block = $request->resblock;
        $resident->lot = $request->reslot;
        $resident->province_id = $request->resprovince;
        $resident->city_id = $request->rescity;
        $resident->barangay_id = $request->resbarangay;
        $resident->street = $request->resstreet;
        $resident->unit_area = $request->resunitarea;
        $resident->move_in_date = Carbon::parse($request->resmoveindate)->format("Y-m-d");
        $resident->house_color = $request->reshousecolor;
        $resident->last_name = $request->reslastname;
        $resident->first_name = $request->resfirstname;
        $resident->middle_name = $request->resmiddlename;
        $resident->home_address = $request->reshomeaddress;
        $resident->other_address = $request->resotheraddress;
        $resident->mobile_number = $request->resmobilenumber;
        $resident->email_address = $request->resemailaddress;
        $resident->citizenship = $request->rescitizenship;
        $resident->date_of_birth = Carbon::parse($request->resdateofbirth)->format("Y-m-d");
        $resident->age = $request->resage;
        $resident->place_of_birth = $request->resplaceofbirth;
        $resident->civil_status = $request->rescivilstatus;
        $resident->gender = $request->resgender;
        $resident->occupation = $request->resoccupation;
        $resident->company_name = $request->rescompanyname;
        $resident->company_address = $request->rescompanyaddress;
        $resident->contact_person = $request->rescontactperson;
        $resident->contact_person_number = $request->rescontactpersonno;
        $resident->save();

        $resident->occupants()->delete();
        $resident->vehicles()->delete();
        $resident->pets()->delete();

        // Occupants
        $occupants = json_decode($request->resoccupantlist, true);
        if($occupants != null){
            foreach($occupants as $occupant){
                $tosave = new ResidentOccupants([
                    "last_name" => $occupant["lastname"],
                    "first_name" => $occupant["firstname"],
                    "middle_name" => $occupant["middlename"],
                    "age" => $occupant["age"],
                    "gender" => $occupant["gender"],
                    "relationship_to_homeowner" => $occupant["relation"],
                    "email_address" => $occupant["emailaddress"],
                    "mobile_number" => $occupant["mobilenumber"]
                ]);
                $resident->occupants()->save($tosave);
            }
        }

        // Vehicles
        $vehicles = json_decode($request->resvehiclelist, true);
        if($vehicles != null){
            foreach($vehicles as $vehicle){
                $tosave = new ResidentVehicles([
                    "type" => $vehicle["vehicletype"],
                    "brand" => $vehicle["brand"],
                    "model" => $vehicle["model"],
                    "plate_number" => $vehicle["platenumber"]
                ]);
                $resident->vehicles()->save($tosave);
            }
        }

        // Pets
        $pets = json_decode($request->respetlist, true);
        if($pets != null){
            foreach($pets as $pet){
                $tosave = new ResidentPets([
                    "type" => $pet["pettype"],
                    "breed" => $pet["breed"],
                    "name" => $pet["petname"],
                    "birth_date" => Carbon::parse($pet["birthdate"])->format("Y-m-d")
                ]);
                $resident->pets()->save($tosave);
            }
        }
        
        return redirect()->back()->with("success", "Resident has been updated.");
    }

    public function deleteresident(Request $request)
    {
        try{
            Residents::find($request->resdelid)->delete();
            return redirect()->back()->with("success", "Resident has been deleted.");
        }
        catch(Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
}
