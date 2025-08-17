<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\RouteChecker;
use App\Models\User;
use Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function checklogin(Request $request)
    {
        $rules = array(
            "txtemail" => "required|email",
            "txtpassword" => "required|min:8"
        );
        $validator = Validator::make($request->all() , $rules);

        if($validator->fails())
        { return redirect()->back()->withErrors($validator)->withInput($request->except('txtpassword')); }
        else
        {
            $userdata = array(
                "email" => $request->txtemail,
                "password" => $request->txtpassword
            );

            $chkuser = User::where("email", $request->txtemail)->where("status", "1")->first();
            if($chkuser){
                if(Auth::attempt($userdata)){
                    return redirect()->route("dashboard");
                }
                else
                { return redirect()->back()->withError("Wrong email address or password")->withInput($request->except('txtpassword')); }
            }
            else{
                return redirect()->back()->with("error", "User not found")->withInput($request->except('txtpassword'));
            }
        }
    }
    
    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('login');
    }
}
