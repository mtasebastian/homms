@extends('layouts.app', ['title' => 'Dashboard'])
@section('content')
<div class="main">
    @include('layouts.navbar')
    <div class="mbody">
        @include('layouts.navtitle', ['navtitle' => 'Dashboard'])
        <div class="mcontent">

        </div>
    </div>
</div>
@endsection