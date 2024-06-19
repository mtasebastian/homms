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
        <div class="mcontent">
            <div class="card m-3 mx-5 p-3 shadow border-light rounded-4">
                <form method="get" action="{{ route('settings.roles') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group inputg">
                            <input type="text" class="form-control py-2 px-3" name="txtrolesearch" placeholder="Type keyword here..." value="{{ isset($searchkey) ? $searchkey : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="txtroledatefrom" placeholder="Select Date Start" value="{{ isset($datefrom) ? $datefrom : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="txtroledateto" placeholder="Select Date End" value="{{ isset($dateto) ? $dateto : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-secondary py-2 px-4 rounded-3">Submit Search</button>
                        <button type="button" class="btn btn-add py-2 px-4 rounded-3 float-end" onclick="addrole()"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Add Role</button>
                    </div>
                </div>
                </form>
            </div>
            <div class="card m-3 mx-5 p-3 shadow border-light rounded-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Role</th>
                            <th scope="col">Description</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col">Created At</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        <tr id="{{ $role->id }}">
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->role }}</td>
                            <td>{{ $role->description }}</td>
                            <td class="text-center"><label class="badge {{ $role->roleStatus() }} p-2 px-3">{{ $role->status() }}</label></td>
                            <td>{{ date("m/d/Y", strtotime($role->created_at)) }}</td>
                            <td class="actions text-center">
                                <button class="btn btn-white border-success p-1 px-2 mx-1" onclick="editrole({{ $role->id }})"><i class="fa-solid fa-pen-to-square text-success"></i></button>
                                <button class="btn btn-white border-danger p-1 px-2 mx-1" onclick="deleterole({{ $role->id }})"><i class="fa-solid fa-trash-alt text-danger"></i></button>
                            </td>
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
</div>
<div class="modal fade" id="addrole" tabindex="-1" aria-labelledby="addroleLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
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
                                            <li>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="checkbox" value="{{ $route }}" id="chk_{{ str_replace('.', '_', $route) }}" name="chkrolepermission[]">
                                                        &nbsp;
                                                        @php
                                                            $cname = ucwords(str_replace("_", " ", str_replace($i . ".", "", $route)));
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
                <button type="submit" class="btn btn-add mx-2 px-3 py-2 rounded-3"><i class="fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Save Role</button>
                <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="deleterole" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content rounded-4">
            <form method="post" action="{{ route('settings.delete_role') }}">
            @csrf
            <div class="modal-body p-4 py-3 mt-3">
                <input type="hidden" id="txtroledelid" name="txtroledelid">
                <h5 class="text-center">Delete Role?</h5>
            </div>
            <div class="modal-footer p-4 pb-4 pt-2 justify-content-md-center border-0">
                <button type="submit" class="btn btn-danger me-2 px-3 py-2 rounded-3"><i class="fa-solid fa-trash-alt"></i>&nbsp;&nbsp;Ok</button>
                <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
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
        const obj = $("#" + id);
        $("#addroleLabel").text("Edit Role");
        $("#frmrole").attr("action", "{{ route('settings.update_role') }}");
        $("#txtroleid").val(id);
        $("#txtrole").val(obj.find("td").eq(1).text());
        $("#txtroledesc").val(obj.find("td").eq(2).text());
        $("#chkrolestatus").attr("checked", (obj.find("td").eq(3).text() == "Enabled" ? true : false));
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
        $("#txtroledelid").val(id);
        $("#deleterole").modal("show");
    }
</script>
@endsection