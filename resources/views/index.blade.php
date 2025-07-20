@extends('layouts.app', ['title' => 'Dashboard'])
@section('content')
<div class="main">
    @include('layouts.navbar')
    <div class="mbody">
        @include('layouts.navtitle', ['navtitle' => 'Dashboard'])
        <div class="mcontent dashboard">
            <div class="cont">
                <div class="box1 shadow-sm rounded">
                    <div class="count cnt1 shadow-sm rounded">
                        <h1 id="cnt1"></h1>
                        <label>Active Resident</label>
                    </div>
                    <div class="count cnt2 rounded">
                        <h1 id="cnt2"></h1>
                        <label>Active Request</label>
                    </div>
                    <div class="count cnt3 rounded">
                        <h1 id="cnt3"></h1>
                        <label>Visitors</label>
                    </div>
                    <div class="count cnt4 rounded">
                        <h1 id="cnt4"></h1>
                        <label>Requests</label>
                    </div>
                    <div class="count cnt5 rounded">
                        <h1 id="cnt5"></h1>
                        <label>Complaints</label>
                    </div>
                </div>
                <div class="box2 shadow-sm rounded">
                    asdasd
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        getCounts();
    });

    function getCounts(){
        $.get("{{ route('dashboard.count') }}").done(function(res){
            $("#cnt1").text(res.cnt1);
            $("#cnt2").text(res.cnt2);
            $("#cnt3").text(res.cnt3);
            $("#cnt4").text(res.cnt4);
            $("#cnt5").text(res.cnt5);
        });
    }
</script>
@endsection