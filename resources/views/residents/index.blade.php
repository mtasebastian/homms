@extends('layouts.app', ['title' => 'Residents'])
@section('content')
<div class="main">
    @include('layouts.navbar')
    <div class="mbody">
        @if(session('success'))
            @include('layouts.toast', ['type' => 'success', 'message' => session('success')])
        @endif
        @if(session('error'))
            @include('layouts.toast', ['type' => 'danger', 'message' => session('error')])
        @endif
        @include('layouts.navtitle', ['navtitle' => 'Residents'])
        <div class="mcontent">
            <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                <form method="get" action="{{ route('residents.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="input-group inputg">
                            <input type="text" class="form-control py-2 px-3" name="txtresidentsearch" placeholder="Type keyword here..." value="{{ isset($searchkey) ? $searchkey : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3 mb-md-0">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="txtresidentdatefrom" placeholder="Select Date Start" value="{{ isset($datefrom) ? $datefrom : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3 mb-md-0">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="txtresidentdateto" placeholder="Select Date End" value="{{ isset($dateto) ? $dateto : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-secondary py-2 px-4 rounded-3 me-2 btn-sm-100">Submit Search</button>
                        <button type="button" class="btn btn-add py-2 px-4 rounded-3 btn-sm-100" onclick="addresident()"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Add Resident</button>
                    </div>
                </div>
                </form>
            </div>
            <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                <i class="idetail">Note: Click a row to view options</i>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="align-top tbl-d-none">ID</th>
                                <th scope="col" class="align-top">Full Name</th>
                                <th scope="col" class="align-top tbl-d-none">Address</th>
                                <th scope="col" class="align-top tbl-d-none">Email Address</th>
                                <th scope="col" class="align-top tbl-d-none">Mobile Number</th>
                                <th scope="col" class="align-top text-center">Status</th>
                                <th scope="col" class="align-top">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($residents as $resident)
                            <tr id="res_{{ $resident->id }}" onclick="optres({{ $resident->id }})">
                                <td class="tbl-d-none">{{ $resident->id }}</td>
                                <td>{{ $resident->fullname }}</td>
                                <td class="w-25 tbl-d-none">{{ 'Brgy. ' . ucwords(strtolower($resident->barangay->name)) . " " . $resident->hoaaddress . ", " . ucwords(strtolower($resident->city->name)) . (str_contains(strtolower($resident->city->name), 'city') ? ', ' : ' City, ') . ucwords(strtolower($resident->province->name)) }}</td>
                                <td class="tbl-d-none">{{ $resident->email_address }}</td>
                                <td class="tbl-d-none">{{ $resident->mobile_number }}</td>
                                <td class="text-center"><label class="badge {{ $resident->residentStatus() }} p-2 px-3">{{ $resident->status() }}</span></td>
                                <td>{{ date("m/d/y", strtotime($resident->created_at)) }}</td>
                                <input type="hidden" class="resident" value="{{ json_encode($resident) }}">
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex"><div class="mx-auto">{{ $residents->links() }}</div></div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="refsetup" value="{{ json_encode($refsetup) }}">
</div>
<div class="modal fade" id="addresident" tabindex="-1" aria-labelledby="addresidentLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="addresidentLabel">Add resident</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="frmresident" action="#">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" id="resid" name="resid">
                    <div class="xsteps card p-2 shadow border-light rounded-4">
                        <ul>
                            <li id="dstep1" class="active">
                                <div class="ocircle">
                                    <i class="fa-solid fa-1 numstep"></i>
                                </div>
                                <span class="numtext">House Info</span>
                            </li>
                            <li><i class="fa-solid fa-chevron-right aright"></i></li>
                            <li id="dstep2">
                                <div class="ocircle">
                                    <i class="fa-solid fa-2 numstep"></i>
                                </div>
                                <span class="numtext">Homeowner's Info</span>
                            </li>
                            <li><i class="fa-solid fa-chevron-right aright"></i></li>
                            <li id="dstep3">
                                <div class="ocircle">
                                    <i class="fa-solid fa-3 numstep"></i>
                                </div>
                                <span class="numtext">Occupants</span>
                            </li>
                            <li><i class="fa-solid fa-chevron-right aright"></i></li>
                            <li id="dstep4">
                                <div class="ocircle">
                                    <i class="fa-solid fa-4 numstep"></i>
                                </div>
                                <span class="numtext">Vehicles</span>
                            </li>
                            <li><i class="fa-solid fa-chevron-right aright"></i></li>
                            <li id="dstep5">
                                <div class="ocircle">
                                    <i class="fa-solid fa-5 numstep"></i>
                                </div>
                                <span class="numtext">Pets</span>
                            </li>
                        </ul>
                    </div>
                    <div id="xbody1" class="xbody card shadow border-light rounded-4 p-4">
                        @include("residents.add.part1")
                    </div>
                    <div id="xbody2" class="xbody card shadow border-light rounded-4 p-4">
                        @include("residents.add.part2")
                    </div>
                    <div id="xbody3" class="xbody card shadow border-light rounded-4 p-4">
                        @include("residents.add.part3")
                    </div>
                    <div id="xbody4" class="xbody card shadow border-light rounded-4 p-4">
                        @include("residents.add.part4")
                    </div>
                    <div id="xbody5" class="xbody card shadow border-light rounded-4 p-4">
                        @include("residents.add.part5")
                    </div>
                </div>
                <div class="modal-footer p-4 py-3 rjusty">
                    <button type="button" class="btn btn-light mx-2 px-3 py-2 rounded-3 float-start" id="btnrprev" onclick="nextprev('prev');"><i class="fa-solid fa-arrow-left"></i>&nbsp;&nbsp;Previous Step</button>
                    <button type="button" class="btn btn-add px-3 py-2 rounded-3" id="btnrnext" onclick="nextprev('next');">Next Step&nbsp;&nbsp;<i class="fa-solid fa-arrow-right"></i></button>
                    <button type="submit" class="btn btn-add ms-2 px-3 py-2 rounded-3" id="btnrsubmit"><i class="fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteresident" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog xs-modal">
        <div class="modal-content rounded-4">
            <form method="post" action="{{ route('residents.delete_resident') }}">
            @csrf
                <div class="modal-body p-4 py-3 mt-3">
                    <input type="hidden" id="resdelid" name="resdelid">
                    <h5 class="text-center">Delete resident?</h5>
                </div>
                <div class="modal-footer p-5 py-3 d-block text-center border-0">
                    <button type="submit" class="btn btn-danger me-2 px-3 py-2 rounded-3"><i class="fa-solid fa-trash-alt"></i>&nbsp;&nbsp;Ok</button>
                    <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="optres" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog xs-modal">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="optresLabel">Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 py-3 m-3 text-center">
                <div class="row">
                    <div class="col-6 p-2"><button class="btn btn-info text-white p-2 w-100 fs-6" onclick="editresident()"><i class="fa-solid fa-pen-to-square me-2 fs-5"></i>Edit</button></div>
                    <div class="col-6 p-2"><button class="btn btn-danger p-2 w-100 fs-6" onclick="deleteresident()"><i class="fa-solid fa-trash-alt me-2 fs-5"></i>Delete</button></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let cnt = 1;
    let res_id = "";
    let params = {};

    $(function(){
        loadRefs();
    });

    function optres(id){
        res_id = id;
        $("#optres").modal("show");
    }

    function loadRefs(){
        const arr = JSON.parse($("#refsetup").val());
        $.each(arr, function(i, val){
            const list = JSON.parse(val.referential.choices);
            $.each(list, function(x, xval){
                $("#res" + val.for).append("<option value='" + xval + "'>" + xval + "</option>");
            });
        });
    }

    function initresident(){
        params = {};
        $(".xsteps li").removeClass("active");
        $("#dstep1").addClass("active");
        $(".xbody").hide();
        $("#xbody1").show();
        $("#btnrprev").hide();
        $("#btnrnext").attr("disabled", true);
        $("#btnrsubmit").hide();
        $("#resid").val("");
        initrespart1();
        initrespart2();
        resetFields("respart3");
        resetFields("respart4");
        resetFields("respart5");
    }

    function nextprev(type){
        if(type == "next"){
            cnt += 1;
            if(cnt > 5){
                cnt = 5;
            }
        }
        else{
            cnt -= 1;
            if(cnt < 1){
                cnt = 1;
            }
        }
        switch(cnt){
            case 1:
                $("#btnrprev").hide();
                if($("#txtdrfname").val() == "" || $("#txtdrlname").val() == "" || $("#txtdremailadd").val() == "" || $("#txtdrpass").val() == ""){
                    $("#btnrnext").attr("disabled", true);
                }
                $("#btnrsubmit").hide();
            break;
            case 2:
                $("#btnrprev").show();
                $("#btnrnext").show();
                chkrespart2();
                $("#btnrsubmit").hide();
            break;
            case 3 || 4:
                $("#btnrprev").show();
                $("#btnrnext").show();
                $("#btnrsubmit").hide();
            break;
            case 5:
                $("#btnrnext").hide();
                $("#btnrsubmit").show();
            break;
        }
        $(".xsteps li").removeClass("active");
        $("#dstep" + cnt).addClass("active");
        $(".xbody").hide();
        $("#xbody" + cnt).show();
    }
    
    function addresident(){
        initresident();
        $("#addresidentLabel").text("Add Resident");
        $("#frmresident").attr("action", "{{ route('residents.add_resident') }}");
        $("#addresident").modal("show");
    }

    function editresident(){
        $("#optres").modal("hide");
        id = res_id;
        const obj = $("#res_" + id);
        initresident();
        params = JSON.parse(obj.find(".resident").val());
        $("#resid").val(id);
        $("#resstatus").prop("checked", params.home_status == 1 ? true : false);
        $("#resrenovated").prop("checked", params.renovated == 1 ? true : false);
        $("#resphase").val(params.phase);
        $("#reshouseno").val(params.house_number);
        $("#resblock").val(params.block);
        $("#reslot").val(params.lot);
        $("#resprovince").val(params.province_id);
        loadcities(params.province_id);
        loadbarangays(params.city_id);
        $("#resstreet").val(params.street);
        $("#resunitarea").val(params.unit_area);
        $("#resmoveindate").val(params.move_in_date);
        $("#reshousecolor").val(params.house_color);
        $("#btnrnext").attr("disabled", false);
        $("#reslastname").val(params.last_name);
        $("#resfirstname").val(params.first_name);
        $("#resmiddlename").val(params.middle_name);
        $("#reshomeaddress").val(params.home_address);
        $("#resotheraddress").val(params.other_address);
        $("#resmobilenumber").val(params.mobile_number);
        $("#resemailaddress").val(params.email_address);
        $("#rescitizenship").val(params.citizenship);
        $("#resdateofbirth").val(formatDate(params.date_of_birth));
        $("#resage").val(params.age);
        $("#resplaceofbirth").val(params.place_of_birth);
        $("#rescivilstatus").val(params.civil_status);
        $("#resgender").val(params.gender);
        $("#resoccupation").val(params.occupation);
        $("#rescompanyname").val(params.company_name);
        $("#rescompanyaddress").val(params.company_address);
        $("#rescontactperson").val(params.contact_person);
        $("#rescontactpersonno").val(params.contact_person_number);

        // Occupants
        occupants = [];
        $.each(params.occupants, function(i, item){
            let occupant = {
                lastname: item.last_name,
                firstname: item.first_name,
                middlename: item.middle_name,
                emailaddress: item.email_address,
                mobilenumber: item.mobile_number,
                age: item.age,
                gender: item.gender,
                relation: item.relationship_to_homeowner
            };
            occupants.push(occupant);
        });
        $("#resoccupantlist").val(JSON.stringify(occupants));
        displayoccupants();

        // Vehicles
        vehicles = [];
        $.each(params.vehicles, function(i, item){
            let vehicle = {
                vehicletype: item.type,
                brand: item.brand,
                model: item.model,
                platenumber: item.plate_number
            };
            vehicles.push(vehicle);
        });
        $("#resvehiclelist").val(JSON.stringify(vehicles));
        displayvehicles();

        // Pets
        pets = [];
        $.each(params.pets, function(i, item){
            let pet = {
                pettype: item.type,
                breed: item.breed,
                petname: item.name,
                birthdate: item.birth_date
            };
            pets.push(pet);
        });
        $("#respetlist").val(JSON.stringify(pets));
        displaypets();
        
        $("#addresidentLabel").text("Edit Resident");
        $("#frmresident").attr("action", "{{ route('residents.update_resident') }}");
        $("#addresident").modal("show");
    }

    function deleteresident(id){
        $("#optres").modal("hide");
        id = res_id;
        $("#resdelid").val(id);
        $("#deleteresident").modal("show");
    }
</script>
@endsection