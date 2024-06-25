@extends('layouts.app', ['title' => 'Users'])
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
        @include('layouts.navtitle', ['navtitle' => 'Users'])
        <div class="mcontent">
            <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                <form method="get" action="{{ route('settings.users') }}">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="input-group inputg">
                            <input type="text" class="form-control py-2 px-3" name="txtusersearch" placeholder="Type keyword here..." value="{{ isset($searchkey) ? $searchkey : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3 mb-md-0">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="txtuserdatefrom" placeholder="Select Date Start" value="{{ isset($datefrom) ? $datefrom : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3 mb-md-0">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="txtuserdateto" placeholder="Select Date End" value="{{ isset($dateto) ? $dateto : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-secondary py-2 px-4 rounded-3 me-2 btn-sm-100">Submit Search</button>
                        <button type="button" class="btn btn-add py-2 px-4 rounded-3 btn-sm-100" onclick="adduser()"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Add User</button>
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
                                <th scope="col" class="align-top">Name</th>
                                <th scope="col" class="align-top">Role</th>
                                <th scope="col" class="align-top tbl-d-none">Email Address</th>
                                <th scope="col" class="align-top tbl-d-none">Mobile #</th>
                                <th scope="col" class="align-top text-center">Status</th>
                                <th scope="col" class="align-top">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr id="{{ $user->id }}" onclick="optuser({{ $user->id }})">
                                <td class="tbl-d-none">{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td id="{{ $user->role->id }}">{{ $user->role->role }}</td>
                                <td class="tbl-d-none">{{ $user->email }}</td>
                                <td class="tbl-d-none">{{ $user->mobileno }}</td>
                                <td class="text-center"><label class="badge {{ $user->userStatus() }} p-2 px-3">{{ $user->status() }}</span></td>
                                <td>{{ date("m/d/y", strtotime($user->created_at)) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex"><div class="mx-auto">{{ $users->links() }}</div></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="adduser" tabindex="-1" aria-labelledby="adduserLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="adduserLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="frmuser" action="#">
            @csrf
            <div class="modal-body p-4 py-3">
                <input type="hidden" id="txtuserid" name="txtuserid">
                <div class="form-data mb-3">
                    <label for="txtrole" class="form-label">Role</label>
                    <select class="form-select py-2 px-3 rounded-3" name="txtrole" id="txtrole" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->role }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-data mb-3">
                    <label for="txtname" class="form-label">Full Name</label>
                    <input type="text" class="form-control py-2 px-3 rounded-3" name="txtname" id="txtname" placeholder="Enter Full Name" required>
                </div>
                <div class="form-data mb-3">
                    <label for="txtemail" class="form-label">Email Address</label>
                    <input type="email" class="form-control py-2 px-3 rounded-3" name="txtemail" id="txtemail" placeholder="Enter Email Address" required>
                </div>
                <div class="form-data mb-3">
                    <label for="txtmobileno" class="form-label">Mobile Number</label>
                    <input type="text" class="form-control py-2 px-3 rounded-3" name="txtmobileno" id="txtmobileno" placeholder="Enter Mobile Number" required>
                </div>
                <div class="form-data mb-3">
                    <label for="txtpassword" class="form-label">Default Password</label>
                    <input type="password" class="form-control py-2 px-3 rounded-3" name="txtpassword" id="txtpassword" placeholder="Enter Default Password" autocomplete="false" required>
                </div>
            </div>
            <div class="modal-footer px-4 py-3">
                <button type="submit" class="btn btn-add mx-2 px-3 py-2 rounded-3"><i class="fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Save</button>
                <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteuser" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog xs-modal">
        <div class="modal-content rounded-4">
            <form method="post" action="{{ route('settings.delete_user') }}">
            @csrf
            <div class="modal-body p-4 py-3 mt-3">
                <input type="hidden" id="txtuserdelid" name="txtuserdelid">
                <h5 class="text-center">Delete User?</h5>
            </div>
            <div class="modal-footer p-4 pb-4 pt-2 d-block text-center border-0">
                <button type="submit" class="btn btn-danger me-2 px-3 py-2 rounded-3"><i class="fa-solid fa-trash-alt"></i>&nbsp;&nbsp;Ok</button>
                <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="optuser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog xs-modal">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="optuserLabel">Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 py-3 m-3 text-center">
                <div class="row">
                    <div class="col-6 p-2"><button class="btn btn-info text-white p-2 w-100 fs-6" onclick="edituser()"><i class="fa-solid fa-pen-to-square me-2 fs-5"></i>Edit</button></div>
                    <div class="col-6 p-2"><button class="btn btn-danger p-2 w-100 fs-6" onclick="deleteuser()"><i class="fa-solid fa-trash-alt me-2 fs-5"></i>Delete</button></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let user_id = "";

    function optuser(id){
        user_id = id;
        $("#optuser").modal("show");
    }

    function adduser(){
        $("#adduserLabel").text("Add User");
        $("#frmuser").attr("action", "{{ route('settings.add_user') }}");
        $("#txtuserid").val("");
        $("#txtname").val("");
        $("#txtrole").val("");
        $("#txtemail").val("");
        $("#txtmobileno").val("");
        $("#txtpassword").val("");
        $("#adduser").modal("show");
    }

    function edituser(){
        $("#optuser").modal("hide");
        let id = user_id;
        const obj = $("#" + id);
        $("#adduserLabel").text("Edit User");
        $("#frmuser").attr("action", "{{ route('settings.update_user') }}");
        $("#txtuserid").val(id);
        $("#txtname").val(obj.find("td").eq(1).text());
        $("#txtrole").val(obj.find("td").eq(2).attr("id"));
        $("#txtemail").val(obj.find("td").eq(3).text());
        $("#txtmobileno").val(obj.find("td").eq(4).text());
        $("#adduser").modal("show");
    }

    function deleteuser(){
        $("#optuser").modal("hide");
        let id = user_id;
        $("#txtuserdelid").val(id);
        $("#deleteuser").modal("show");
    }
</script>
@endsection