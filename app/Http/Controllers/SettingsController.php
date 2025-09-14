<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Roles;
use App\Models\RolePermissions;
use App\Models\Referentials;
use App\Models\Modules;
use App\Models\RefSetup;
use App\Models\Cities;
use App\Models\Barangays;
use App\Models\Residents;
use App\Models\FinancialSetup;
use App\Models\SystemSetup;
use App\Helpers\RouteChecker;
use Carbon\Carbon;
use DB;

class SettingsController extends Controller
{
    public function users(Request $request)
    {
        $params = ['users', 'roles'];
        $users = User::paginate(10);
        if($request->txtusersearch != null){
            $users = User::where("name", "like", "%" . $request->txtusersearch . "%")->paginate(10);
            $searchkey = $request->txtusersearch;
            array_push($params, ['searchkey']);

            if($request->txtuserdatefrom != null && $request->txtuserdateto == null){
                $users = User::where("name", "like", "%" . $request->txtusersearch . "%")->whereDate("created_at", Carbon::parse($request->txtuserdatefrom)->format("Y-m-d"))->paginate(10);
                $datefrom = $request->txtuserdatefrom;
                array_push($params, ['datefrom']);
            }
            elseif($request->txtuserdatefrom == null && $request->txtuserdateto != null){
                $users = User::where("name", "like", "%" . $request->txtusersearch . "%")->whereDate("created_at", Carbon::parse($request->txtuserdateto)->format("Y-m-d"))->paginate(10);
                $dateto = $request->txtuserdateto;
                array_push($params, ['dateto']);
            }
            elseif($request->txtuserdatefrom != null && $request->txtuserdateto != null){
                $users = User::where("name", "like", "%" . $request->txtusersearch . "%")->whereBetween(DB::raw("DATE(created_at)"), [Carbon::parse($request->txtuserdatefrom)->format("Y-m-d"), Carbon::parse($request->txtuserdateto)->format("Y-m-d")])->paginate(10);
                $datefrom = $request->txtuserdatefrom;
                $dateto = $request->txtuserdateto;
                array_push($params, ['datefrom', 'dateto']);
            }
        }
        elseif($request->txtusersearch == null){
            if($request->txtuserdatefrom != null && $request->txtuserdateto == null){
                $users = User::whereDate("created_at", Carbon::parse($request->txtuserdatefrom)->format("Y-m-d"))->paginate(10);
                $datefrom = $request->txtuserdatefrom;
                array_push($params, ['datefrom']);
            }
            elseif($request->txtuserdatefrom == null && $request->txtuserdateto != null){
                $users = User::whereDate("created_at", Carbon::parse($request->txtuserdateto)->format("Y-m-d"))->paginate(10);
                $dateto = $request->txtuserdateto;
                array_push($params, ['dateto']);
            }
            elseif($request->txtuserdatefrom != null && $request->txtuserdateto != null){
                $users = User::whereDate("created_at", ">=", Carbon::parse($request->txtuserdatefrom)->format("Y-m-d"))->whereDate("created_at", "<=", Carbon::parse($request->txtuserdateto)->format("Y-m-d"))->paginate(10);
                $datefrom = $request->txtuserdatefrom;
                $dateto = $request->txtuserdateto;
                array_push($params, ['datefrom', 'dateto']);
            }
        }
        $roles = Roles::get();
        return view("settings.users", compact($params));
    }

    public function adduser(Request $request)
    {
        $rules = array(
            "txtname" => "required",
            "txtrole" => "required",
            "txtemail" => "required|email",
            "txtmobileno" => "required|numeric",
            "txtpassword" => "required|alphaNum|min:5"
        );
        $validator = Validator::make($request->all() , $rules);

        if($validator->fails())
        { return redirect()->back()->withErrors($validator)->withInput($request->except('txtpassword')); }
        else
        {
            $user = new User();
            $user->name = $request->txtname;
            $user->role_id = $request->txtrole;
            $user->email = $request->txtemail;
            $user->mobileno = $request->txtmobileno;
            $user->password = bcrypt($request->txtpassword);
            $user->save();

            return redirect()->back()->with("success", "User has been added.");
        }
    }

    public function updateuser(Request $request)
    {
        $rules = array(
            "txtuserid" => "required",
            "txtname" => "required",
            "txtrole" => "required",
            "txtemail" => "required|email",
            "txtmobileno" => "required|numeric"
        );
        $validator = Validator::make($request->all() , $rules);

        if($validator->fails())
        { return redirect()->back()->withErrors($validator); }
        else
        {
            $user = User::find($request->txtuserid);
            $user->name = $request->txtname;
            $user->role_id = $request->txtrole;
            $user->email = $request->txtemail;
            $user->mobileno = $request->txtmobileno;
            if($request->txtpassword){
                $user->password = bcrypt($request->txtpassword);
            }
            $user->save();

            return redirect()->back()->with("success", "User has been updated.");
        }
    }

    public function deleteuser(Request $request)
    {
        try{
            User::find($request->txtuserdelid)->delete();
            return redirect()->back()->with("success", "User has been deleted.");
        }
        catch(Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function roles(Request $request)
    {
        $params = ['roles', 'routes'];
        $roles = Roles::get();
        if($request->txtrolesearch != null){
            $roles = Roles::where("role", "like", "%" . $request->txtrolesearch . "%")->paginate(10);
            $searchkey = $request->txtrolesearch;
            array_push($params, ['searchkey']);

            if($request->txtroledatefrom != null && $request->txtroledateto == null){
                $roles = Roles::where("role", "like", "%" . $request->txtrolesearch . "%")->whereDate("created_at", Carbon::parse($request->txtroledatefrom)->format("Y-m-d"))->paginate(10);
                $datefrom = $request->txtroledatefrom;
                array_push($params, ['datefrom']);
            }
            elseif($request->txtroledatefrom == null && $request->txtroledateto != null){
                $roles = Roles::where("role", "like", "%" . $request->txtrolesearch . "%")->whereDate("created_at", Carbon::parse($request->txtroledateto)->format("Y-m-d"))->paginate(10);
                $dateto = $request->txtroledateto;
                array_push($params, ['dateto']);
            }
            elseif($request->txtroledatefrom != null && $request->txtroledateto != null){
                $roles = Roles::where("role", "like", "%" . $request->txtrolesearch . "%")->whereBetween(DB::raw("DATE(created_at)"), [Carbon::parse($request->txtroledatefrom)->format("Y-m-d"), Carbon::parse($request->txtroledateto)->format("Y-m-d")])->paginate(10);
                $datefrom = $request->txtroledatefrom;
                $dateto = $request->txtroledateto;
                array_push($params, ['datefrom', 'dateto']);
            }
        }
        elseif($request->txtrolesearch == null){
            if($request->txtroledatefrom != null && $request->txtroledateto == null){
                $roles = Roles::whereDate("created_at", Carbon::parse($request->txtroledatefrom)->format("Y-m-d"))->paginate(10);
                $datefrom = $request->txtroledatefrom;
                array_push($params, ['datefrom']);
            }
            elseif($request->txtroledatefrom == null && $request->txtroledateto != null){
                $roles = Roles::whereDate("created_at", Carbon::parse($request->txtroledateto)->format("Y-m-d"))->paginate(10);
                $dateto = $request->txtroledateto;
                array_push($params, ['dateto']);
            }
            elseif($request->txtroledatefrom != null && $request->txtroledateto != null){
                $roles = Roles::whereBetween(DB::raw("DATE(created_at)"), [Carbon::parse($request->txtroledatefrom)->format("Y-m-d"), Carbon::parse($request->txtroledateto)->format("Y-m-d")])->paginate(10);
                $datefrom = $request->txtroledatefrom;
                $dateto = $request->txtroledateto;
                array_push($params, ['datefrom', 'dateto']);
            }
        }
        $routes = RouteChecker::routeList();
        return view("settings.roles", compact($params));
    }

    public function addrole(Request $request)
    {
        $rules = array(
            "txtrole" => "required"
        );
        $validator = Validator::make($request->all() , $rules);

        if($validator->fails())
        { return redirect()->back()->withErrors($validator); }
        else
        {
            $role = New Roles();
            $role->role = $request->txtrole;
            $role->description = $request->txtroledesc;
            $role->status = isset($request->chkrolestatus) ? 1 : 0;
            $role->save();

            foreach($request->chkrolepermission as $route){
                $rolepermission = New RolePermissions();
                $rolepermission->role_id = $role->id;
                $rolepermission->route = $route;
                $rolepermission->save();
            }

            return redirect()->back()->with("success", "User Role has been added.");
        }
    }

    public function updaterole(Request $request)
    {
        $rules = array(
            "txtroleid" => "required",
            "txtrole" => "required"
        );
        $validator = Validator::make($request->all() , $rules);

        if($validator->fails())
        { return redirect()->back()->withErrors($validator); }
        else
        {
            $role = Roles::find($request->txtroleid);
            $role->role = $request->txtrole;
            $role->description = $request->txtroledesc;
            $role->status = isset($request->chkrolestatus) ? 1 : 0;
            $role->save();

            RolePermissions::where("role_id", $role->id)->delete();
            foreach($request->chkrolepermission as $route){
                $rolepermission = New RolePermissions();
                $rolepermission->role_id = $role->id;
                $rolepermission->route = $route;
                $rolepermission->save();
            }

            return redirect()->back()->with("success", "User Role has been updated.");
        }
    }

    public function deleterole(Request $request)
    {
        try{
            Roles::find($request->txtroledelid)->delete();
            $rolepermissions = RolePermissions::where("role_id", $request->txtroledelid)->update(["status" => 0]);
            return redirect()->back()->with("success", "Role has been deleted.");
        }
        catch(Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function referentials(Request $request)
    {
        $params = ['referentials', 'modules'];
        $search = $request->txtreferentialsearch;
        $datefrom = $request->txtreferentialdatefrom;
        $dateto = $request->txtreferentialdateto;

        $query = Referentials::query();
        if($search){
            $query->where("name", "LIKE", "%" . $search . "%");
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

        $referentials = $query->paginate(10);
        $modules = Modules::get();
        return view("settings.referentials", compact($params));
    }

    public function addreferential(Request $request)
    {
        $rules = array(
            "txtreferential" => "required",
            "txtchoices" => "required"
        );
        $validator = Validator::make($request->all() , $rules);

        if($validator->fails())
        { return redirect()->back()->withErrors($validator); }
        else
        {
            $referential = New Referentials();
            $referential->module_id = $request->txtreferentialmod;
            $referential->name = $request->txtreferential;
            $referential->description = $request->txtreferentialdesc;
            $referential->choices = $request->txtchoices;
            $referential->save();

            return redirect()->back()->with("success", "Referential has been added.");
        }
    }

    public function updatereferential(Request $request)
    {
        $rules = array(
            "txtreferential" => "required",
            "txtchoices" => "required"
        );
        $validator = Validator::make($request->all() , $rules);

        if($validator->fails())
        { return redirect()->back()->withErrors($validator); }
        else
        {
            $referential = Referentials::find($request->txtreferentialid);
            $referential->module_id = $request->txtreferentialmod;
            $referential->name = $request->txtreferential;
            $referential->description = $request->txtreferentialdesc;
            $referential->choices = $request->txtchoices;
            $referential->save();

            return redirect()->back()->with("success", "Referential has been updated.");
        }
    }

    public function setup()
    {
        $referentials = Referentials::get();
        $refsetup = RefSetup::get();
        return view("settings.setup", compact(['referentials', 'refsetup']));
    }

    public function saverefsetup(Request $request){
        RefSetup::truncate();
        foreach($request->all() as $i => $param){
            if($i != "_token" && $param != ""){
                $refsetup = New RefSetup();
                $refsetup->for = $i;
                $refsetup->ref_id = $param;
                $refsetup->save();
            }
        }
        return "success";
    }

    public function loadcities(Request $request)
    {
        $cities = Cities::where('province_id', $request->province)->get();
        return $cities;
    }

    public function loadbrgys(Request $request)
    {
        $brgys = Barangays::where('city_id', $request->city)->get();
        return $brgys;
    }

    public function filterresidents(Request $request)
    {
        $residents = Residents::where('last_name', 'like', '%' . $request->key . '%')->orWhere('first_name', 'like', '%' . $request->key . '%')->orWhere('middle_name', 'like', '%' . $request->key . '%')->limit(10)->get();
        return $residents;
    }

    public function financials()
    {
        $financials = FinancialSetup::get();
        return $financials;
    }

    public function savefinancial(Request $request)
    {
        $financial = new FinancialSetup();
        if($request->id != ""){
            $financial = FinancialSetup::find($request->id);
        }
        $financial->bill_name = $request->name;
        $financial->bill_amt = $request->amt;
        $financial->save();
        
        return "success";
    }

    public function deletefinancial(Request $request)
    {
        try{
            FinancialSetup::find($request->id)->delete();
            return "success";
        }
        catch(Exception $e){
            return "failed";
        }
    }

    public function getsettings()
    {
        $arr = [];
        $settings = SystemSetup::get();
        foreach($settings as $setting){
            if($setting->setting_type == "file" && !empty($setting->content)){
                $setting->content = base64_encode($setting->content);
            }
            $arr[$setting->setting_name] = $setting;
        }
        return $arr;
    }

    public function systemsettings(Request $request)
    {
        try{
            foreach($request->all() as $key => $value){
                if($key !== "_token"){
                    $sys = SystemSetup::where("setting_name", $key)->first();
                    if(!$sys){
                        $sys = new SystemSetup();
                    }
                    if($request->hasFile($key) && $request->file($key)->isValid()){
                        $uploadedFile = $request->file($key);
                        $sys->setting_name = $key;
                        $sys->setting_type = 'file';
                        $sys->text = $uploadedFile->getClientOriginalName();
                        $sys->mime = $uploadedFile->getMimeType();
                        $sys->content = file_get_contents($uploadedFile->getRealPath());
                        $sys->save();

                    }
                    else{
                        $sys->setting_name = $key;
                        $sys->setting_type = 'text';
                        $sys->text = $value;
                        $sys->save();
                    }
                }
            }
            return "success";
        }
        catch(Exception $e){
            return "failed";
        }
    }

    public function getaccount()
    {
        return auth()->user();
    }

    public function updateaccount(Request $request)
    {
        try{
            $user = auth()->user();
            $user->name = $request->name;
            $user->mobileno = $request->mobileno;
            $user->email = $request->email;
            if(!empty($request->password)){
                $user->password = bcrypt($request->password);
            }
            $user->save();
            return "success";
        }
        catch(Exception $e){
            return "failed";
        }
    }
}
