<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Residents;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{

    public function index()
    {
        return view("register");
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'required',
            'email_address' => 'required|email',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ]);

        if($validator->fails()){
            return redirect()->back()->with("errors", $validator->errors())->withInput();
        }
        else{
            if($request->password != $request->confirm_password){
                return redirect()->back()->with("error", "Passwords do not match.")->withInput();;
            }

            $resident = Residents::where("email_address", $request->email_address)->where("mobile_number", $request->contact_number)->first();
            if($resident){
                $auth = User::where("email", $request->email_address)->where("mobileno", $request->contact_number)->first();
                if($auth){
                    return redirect()->back()->with("error", "User already exists.")->withInput();;
                }
                else{
                    $user = new User();
                    $user->role_id = 5;
                    $user->resident_id = $resident->id;
                    $user->name = $request->first_name . " " . $request->last_name;
                    $user->email = $request->email_address;
                    $user->mobileno = $request->contact_number;
                    $user->password = bcrypt($request->password);
                    $user->save();

                    return redirect()->back()->with("success", "Registration successful. You will be redirected to the login page.");
                }
            }
            else{
                return redirect()->back()->with("error", "Resident not found.")->withInput();;
            }
        }
    }
}
