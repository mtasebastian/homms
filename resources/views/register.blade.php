@extends('layouts.app', ['title' => 'Register Resident'])
@section('content')
<div class="bgimg"></div>
<div class="registerbox shadow border-light rounded-4 pb-5">
    <a href="{{ route('login') }}" class="rclose"><i class="fa-solid fa-left-long"></i>Back to Login</a>
    <img class="img shadow-sm border-light rounded-4" src="{{ asset('images/logo.jpg') }}">
    <h5 class="my-t rtitle">HOA Account Registration</h5>
    <p class="rdesc mx-auto">Fill up the following information. The values given are subject for verification before approval.</p>
    <br>
    <form method="post" action="{{ route('register.save') }}" class="px-5">
        @csrf
        <div class="row">
            <div class="col-md-6 form-data mb-3">
                <label for="txtemail" class="form-label">First Name</label>
                <div class="input-group inputl">
                    <div class="input-group-prepend">
                        <span class="input-group-text rounded-0 rounded-start bg-white">
                            <i class="fa-solid fa-user"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control py-2 ps-1 pe-3" name="txtfirstname" placeholder="Enter first name">
                </div>
            </div>
            <div class="col-md-6 form-data mb-3">
                <label for="txtemail" class="form-label">Last Name</label>
                <div class="input-group inputl">
                    <div class="input-group-prepend">
                        <span class="input-group-text rounded-0 rounded-start bg-white">
                            <i class="fa-solid fa-user"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control py-2 ps-1 pe-3" name="txtlastname" placeholder="Enter last name">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-data mb-3">
                <label for="txtemail" class="form-label">Email address</label>
                <div class="input-group inputl">
                    <div class="input-group-prepend">
                        <span class="input-group-text rounded-0 rounded-start bg-white">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                    </div>
                    <input type="email" class="form-control py-2 ps-1 pe-3" name="txtemail" placeholder="Enter email address">
                </div>
            </div>
            <div class="col-md-6 form-data mb-3">
                <label for="txtemail" class="form-label">Contact Number</label>
                <div class="input-group inputl">
                    <div class="input-group-prepend">
                        <span class="input-group-text rounded-0 rounded-start bg-white">
                            <i class="fa-solid fa-address-book"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control py-2 ps-1 pe-3" name="txtcontactno" placeholder="Enter contact number">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-data mb-3">
                <label for="txtemail" class="form-label">Password</label>
                <div class="input-group inputl">
                    <div class="input-group-prepend">
                        <span class="input-group-text rounded-0 rounded-start bg-white">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                    </div>
                    <input type="password" class="form-control py-2 ps-1 pe-3" name="txtpassword" placeholder="Enter desired password">
                </div>
            </div>
            <div class="col-md-6 form-data mb-3">
                <label for="txtemail" class="form-label">Confirm Password</label>
                <div class="input-group inputl">
                    <div class="input-group-prepend">
                        <span class="input-group-text rounded-0 rounded-start bg-white">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                    </div>
                    <input type="password" class="form-control py-2 ps-1 pe-3" name="txtconfirmpassword" placeholder="Confirm password">
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col col-md-6 col-sm-6"><button class="btn w-100 btn-add py-2 shadow-sm"><b>Register</b></button></div>
            <div class="col col-md-6 col-sm-6"><button class="btn w-100 btn-light border py-2 shadow-sm"><b>Reset</b></button></div>
        </div>
    </form>
    @if(session('success'))
    <div class="alert alert-success cbox my-3 mx-5">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
    <script>
        setTimeout(function(){
            window.location = "{{ route('login') }}";
        }, 3000);
    </script>
    @endif
    @if(session('error'))
    <div class="alert alert-danger cbox my-3 mx-5">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span>{{ session('error') }}</span>
    </div>  
    @endif
</div>
@endsection