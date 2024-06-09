<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Residents;
use App\Models\User;

class RegisterController extends Controller
{

    public function index()
    {
        return view("register");
    }

    public function register(Request $request)
    {
        $resident = Residents::where("email_address", $request->txtemail)->where("mobile_number", $request->txtcontactno)->get();
        if($resident->count() > 0){
            if($request->txtpassword != $request->txtconfirmpassword){
                return redirect()->back()->with("error", "Passwords do not match.");
            }
            $user = new User();
            $user->role_id = 5;
            $user->name = $request->txtfirstname . " " . $request->txtlastname;
            $user->email = $request->txtemail;
            $user->password = bcrypt($request->txtpassword);
            $user->save();
            return redirect()->back()->with("success", "Registration successful. You will be redirected to the login page.");
        }
        else{
            return redirect()->back()->with("error", "Record not found.");
        }
    }
}
