@extends('layouts.app', ['title' => 'User Roles'])
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
        @include('layouts.navtitle', ['navtitle' => 'User Roles'])
        @if($checker->routePermission('settings.roles'))
            <div class="mcontent">
                <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                    <form method="get" action="{{ route('settings.roles') }}">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="input-group inputg">
                                <input type="text" class="form-control py-2 px-3" name="txtrolesearch" placeholder="Type keyword here..." value="{{ isset($searchkey) ? $searchkey : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-0 rounded-end bg-white">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3 mb-md-0">
                            <div class="input-group inputg">
                                <input type="text" class="form-control datepicker py-2 px-3" name="txtroledatefrom" placeholder="Select Date Start" value="{{ isset($datefrom) ? $datefrom : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-0 rounded-end bg-white">
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3 mb-md-0">
                            <div class="input-group inputg">
                                <input type="text" class="form-control datepicker py-2 px-3" name="txtroledateto" placeholder="Select Date End" value="{{ isset($dateto) ? $dateto : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-0 rounded-end bg-white">
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-secondary py-2 px-4 rounded-3 me-2 btn-sm-100">Submit Search</button>
                            <button
                                type="button"
                                class="btn btn-add py-2 px-4 rounded-3 btn-sm-100
                                @if(!$checker->routePermission('settings.add_role'))
                                disabled
                                @endif
                                "
                                onclick="addrole()"
                            >
                                <i class="fa-solid fa-plus"></i>&nbsp;&nbsp;
                                Add Role
                            </button>
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
                                    <th scope="col" class="align-top">Role</th>
                                    <th scope="col" class="align-top tbl-d-none">Description</th>
                                    <th scope="col" class="align-top text-center">Status</th>
                                    <th scope="col" class="align-top">Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $role)
                                <tr id="{{ $role->id }}" onclick="optrole({{ $role->id }})">
                                    <td class="tbl-d-none">{{ $role->id }}</td>
                                    <td>{{ $role->role }}</td>
                                    <td class="tbl-d-none">{{ $role->description }}</td>
                                    <td class="text-center"><label class="badge {{ $role->roleStatus() }} p-2 px-3">{{ $role->status() }}</label></td>
                                    <td>{{ date("m/d/Y", strtotime($role->created_at)) }}</td>
                                    @php
                                        $rpers = "";
                                        $rolepermissions = $role->permissions;
                                        foreach($rolepermissions as $rolepermission){
                                            $rpers .= "|" . $rolepermission->route;
                                        }
                                    @endphp
                                    <input type="hidden" class="routelist" value="{{ substr($rpers, 1) }}">
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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
<div class="modal fade" id="addrole" tabindex="-1" aria-labelledby="addroleLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="addroleLabel">Add Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="frmrole" action="{{ route('settings.add_role') }}">
            @csrf
            <div class="modal-body p-4 py-3">
                <input type="hidden" id="txtroleid" name="txtroleid">
                <div class="form-data mb-3">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" value="1" name="chkrolestatus" id="chkrolestatus" checked>
                        &nbsp;
                        Enabled
                    </label>
                </div>
                <div class="form-data mb-3">
                    <label for="txtrole" class="form-label">Role</label>
                    <input type="text" class="form-control py-2 px-3 rounded-3" name="txtrole" id="txtrole" placeholder="Enter Role Name" required>
                </div>
                <div class="form-data mb-3">
                    <label for="txtroledesc" class="form-label">Description</label>
                    <textarea class="form-control py-2 px-3 rounded-3" name="txtroledesc" id="txtroledesc" placeholder="Enter Role Description"></textarea>
                </div>
                <h5 class="text-center mt-5 mb-3 msubtitle">Role Permissions</h5>
                <div class="card rounded-4 rpermissioncont" id="rpermissioncont">
                    <div class="permcont" id="rpermissioncont">
                        @php
                            $not_included = ['count', 'get_settings', 'get_account', 'filter'];
                        @endphp
                        @foreach($routes as $i => $route_group)
                        @if($i != "chat")
                        <div class="rpermission">
                            <div class="card border-0 rounded-0">
                                <div class="card-header collapsed rounded-0" id="heading{{ $i }}" data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}">
                                    <h5>{{ ucfirst($i) }}</h5>
                                    <i class="fa-solid fa-chevron-down indi"></i>
                                </div>
                                <div id="collapse{{ $i }}" class="collapse" data-parent="#rpermissioncont">
                                    <div class="card-body">
                                        <ul class="mb-0">
                                            @foreach($route_group as $route)
                                            @php
                                                $rname = str_replace($i . ".", "", $route);
                                                if(!in_array($rname, $not_included)){
                                            @endphp
                                            <li>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="checkbox" value="{{ $route }}" id="chk_{{ str_replace('.', '_', $route) }}" name="chkrolepermission[]">
                                                        &nbsp;
                                                        @php
                                                            $cname = ucwords(str_replace("_", " ", $rname));
                                                            if($cname == "Index"){
                                                                if($i == "reports"){
                                                                    echo "Reports";
                                                                }
                                                                else{
                                                                    echo ucfirst($i) . " List";
                                                                }
                                                            }
                                                            else{
                                                                echo $cname;
                                                            }
                                                        @endphp
                                                    </label>
                                                </div>
                                            </li>
                                            @php } @endphp
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer p-4 py-3">
                <button type="submit" class="btn btn-add mx-2 px-3 py-2 rounded-3"><i class="fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Save</button>
                <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="deleterole" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog xs-modal">
        <div class="modal-content rounded-4">
            <form method="post" action="{{ route('settings.delete_role') }}">
            @csrf
            <div class="modal-body p-4 py-3 mt-3">
                <input type="hidden" id="txtroledelid" name="txtroledelid">
                <h5 class="text-center">Delete Role?</h5>
            </div>
            <div class="modal-footer p-4 pb-4 pt-2 d-block text-center border-0">
                <button type="submit" class="btn btn-danger me-2 px-3 py-2 rounded-3"><i class="fa-solid fa-trash-alt"></i>&nbsp;&nbsp;Ok</button>
                <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="optrole" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog xs-modal">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="optroleLabel">Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 py-3 m-3 text-center">
                <div class="row">
                    <div class="col-6 p-2">
                        <button
                            class="btn btn-info text-white p-2 w-100 fs-6
                            @if(!$checker->routePermission('settings.update_role'))
                            disabled
                            @endif
                            "
                            onclick="editrole()"
                        >
                            <i class="fa-solid fa-pen-to-square me-2 fs-5"></i>
                            Edit
                        </button>
                    </div>
                    <div class="col-6 p-2">
                        <button
                            class="btn btn-danger p-2 w-100 fs-6
                            @if(!$checker->routePermission('settings.delete_role'))
                            disabled
                            @endif
                            "
                            onclick="deleterole()"
                        >
                            <i class="fa-solid fa-trash-alt me-2 fs-5"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let role_id = "";

    function optrole(id){
        role_id = id;
        $("#optrole").modal("show");
    }

    function addrole(){
        $("#addroleLabel").text("Add Role");
        $("#frmrole").attr("action", "{{ route('settings.add_role') }}");
        $("#txtroleid").val("");
        $("#txtrole").val("");
        $("#txtroledesc").val("");
        $("#chkrolestatus").attr("checked", true);
        $("#addrole .collapse").removeClass("show");
        $("#addrole").modal("show");
    }

    function editrole(id){
        $("#optrole").modal("hide");
        id = role_id;
        const obj = $("#" + id);
        $("#addroleLabel").text("Edit Role");
        $("#frmrole").attr("action", "{{ route('settings.update_role') }}");
        $("#txtroleid").val(id);
        $("#txtrole").val(obj.find("td").eq(1).text());
        $("#txtroledesc").val(obj.find("td").eq(2).text());
        $("#chkrolestatus").attr("checked", (obj.find("td").eq(3).text() == "ENABLED" ? true : false));
        $("#rpermissioncont .form-check-input").attr("checked", false);
        $("#rpermissioncont .collapse").removeClass("show");
        const arr = obj.find(".routelist").val().split("|");
        $.each(arr, function(i, item){
            $("#chk_" + item.replaceAll('.', '_')).attr("checked", true);
        });
        $("#addrole .collapse").removeClass("show");
        $("#addrole").modal("show");
    }

    function deleterole(id){
        $("#optrole").modal("hide");
        role_id = id;
        $("#txtroledelid").val(id);
        $("#deleterole").modal("show");
    }
</script>
@endsection