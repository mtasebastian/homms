@extends('layouts.app', ['title' => 'System Setup'])
@section('content')
<div class="main">
    @include('layouts.navbar')
    <div class="mbody">
        @include('layouts.toast', ['type' => '', 'message' => ''])
        @include('layouts.navtitle', ['navtitle' => 'System Setup'])
        @if($checker->routePermission('settings.system_setup'))
            <div class="mcontent">
                <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                    <ul class="nav nav-tabs sys_setup">
                        <li class="nav-item" id="sys_1">
                            <a class="nav-link active">General Settings</a>
                        </li>
                        @if($checker->routePermission('settings.financials'))
                        <li class="nav-item" id="sys_2">
                            <a class="nav-link">Financials Setup</a>
                        </li>
                        @endif
                        @if($checker->routePermission('settings.save_referential_setup'))
                        <li class="nav-item" id="sys_3">
                            <a class="nav-link">Assign Referencials</a>
                        </li>
                        @endif
                        <li class="nav-item" id="sys_4">
                            <a class="nav-link">Account Settings</a>
                        </li>
                    </ul>
                    <div class="syscont" id="syscont1">
                        @include("settings.setup.index")
                    </div>
                    <div class="syscont" id="syscont2">
                        @include("settings.setup.financials")
                    </div>
                    <div class="syscont p-3 py-4" id="syscont3">
                        @include("settings.setup.referentials")
                    </div>
                    <div class="syscont" id="syscont4">
                        @include("settings.setup.account")
                    </div>
                </div>
            </div>
        @else
            <div class="mcontent">
                <div class="no-access">You don't have access to this feature!</div>
            </div>
        @endif
    </div>
</div>
@endsection