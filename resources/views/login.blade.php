@extends('layouts.app', ['title' => 'Login Page'])
@section('content')
<div class="bgimg"></div>
<div class="loginbox shadow border-light rounded-4 pb-5">
    <img class="img shadow-sm border-light rounded-4" src="{{ asset('images/logo.jpg') }}">
    <h5 class="my-4 ltitle">Login to get Started</h5>
    <form method="post" action="{{ route('checklogin') }}" class="px-5">
        @csrf
        <div class="form-data mb-3">
            <label for="txtemail" class="form-label">Email address</label>
            <div class="input-group inputl">
                <div class="input-group-prepend">
                    <span class="input-group-text rounded-0 rounded-start bg-white">
                        <i class="fa-solid fa-user"></i>
                    </span>
                </div>
                <input type="email" class="form-control py-2 ps-1 pe-3" name="txtemail" value="{{ Request::old('txtemail') }}" placeholder="Enter email address">
            </div>
        </div>
        <div class="form-data mb-4">
            <label for="txtpassword" class="form-label">Password</label>
            <div class="input-group inputl">
                <div class="input-group-prepend">
                    <span class="input-group-text rounded-0 rounded-start bg-white">
                        <i class="fa-solid fa-key"></i>
                    </span>
                </div>
                <input type="password" class="form-control py-2 ps-1 pe-3 text-bold" name="txtpassword" placeholder="Enter password">
            </div>
        </div>
        <button class="btn w-100 btn-add py-2 shadow-sm"><b>Log In</b></button>
        <span class="forgot mt-2 mx-1">Forgot your password?</span>
        <br>
        <p class="mb-0 mx-1 register">Don't have an account yet? <a href="{{ route('register') }}">Register Here</a></p>
    </form>
    @if(session('error'))
        <div class="alert alert-danger mt-4 rounded-0">
            {{ session('error') }}
        </div>
    @endif
    @if(session('errors'))
        @foreach(json_decode(session('errors'), true) as $errs)
            @foreach($errs as $err)
            <div class="alert alert-danger mt-4 rounded-0">
                {{ str_replace("txtemail", "Email Address", str_replace("txtpassword", "Password", $err)),  }}
            </div>
            @endforeach
        @endforeach
    @endif
</div>
@endsection