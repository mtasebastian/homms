@extends('layouts.app', ['title' => 'System Setup'])
@section('content')
<div class="main">
    @include('layouts.navbar')
    <div class="mbody">
        @include('layouts.toast', ['type' => '', 'message' => ''])
        @include('layouts.navtitle', ['navtitle' => 'System Setup'])
        <div class="mcontent">
            <div class="card m-3 mx-5 p-3 shadow border-light rounded-4">
                <ul class="nav nav-tabs sys_setup">
                    <li class="nav-item" id="sys_1">
                        <a class="nav-link active text-" href="#">General Settings</a>
                    </li>
                    <li class="nav-item" id="sys_2">
                        <a class="nav-link" href="#">Financials Setup</a>
                    </li>
                    <li class="nav-item" id="sys_3">
                        <a class="nav-link" href="#">Assign Referencials</a>
                    </li>
                    <li class="nav-item" id="sys_4">
                        <a class="nav-link" href="#">Account Settings</a>
                    </li>
                </ul>
                <div class="syscont" id="syscont1">

                </div>
                <div class="syscont" id="syscont2">
                    @include("settings.setup.financials")
                </div>
                <div class="syscont p-3 py-4" id="syscont3">
                    @include("settings.setup.referentials")
                </div>
                <div class="syscont" id="syscont4">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection