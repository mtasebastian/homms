@extends('layouts.app', ['title' => 'Referentials'])
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
        @include('layouts.navtitle', ['navtitle' => 'Referentials'])
        @if($checker->routePermission('settings.referentials'))
            <div class="mcontent">
                <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                    <form method="get" action="{{ route('settings.referentials') }}">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="input-group inputg">
                                <input type="text" class="form-control py-2 px-3" name="txtreferentialsearch" placeholder="Type keyword here..." value="{{ isset($searchkey) ? $searchkey : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-0 rounded-end bg-white">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3 mb-md-0">
                            <div class="input-group inputg">
                                <input type="text" class="form-control datepicker py-2 px-3" name="txtreferentialdatefrom" placeholder="Select Date Start" value="{{ isset($datefrom) ? $datefrom : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-0 rounded-end bg-white">
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3 mb-md-0">
                            <div class="input-group inputg">
                                <input type="text" class="form-control datepicker py-2 px-3" name="txtreferentialdateto" placeholder="Select Date End" value="{{ isset($dateto) ? $dateto : '' }}">
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
                                @if(!$checker->routePermission('settings.add_referential'))
                                disabled
                                @endif
                                "
                                onclick="addreferential()"
                            >
                                <i class="fa-solid fa-plus"></i>&nbsp;&nbsp;
                                Add Referential
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                    <i class="idetail">Note: Click a row to view options</i>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-secondary">
                                <tr>
                                    <th scope="col" class="align-top tbl-d-none">ID</th>
                                    <th scope="col" class="align-top">Name</th>
                                    <th scope="col" class="align-top">Description</th>
                                    <th scope="col" class="align-top">Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($referentials as $referential)
                                <tr id="{{ $referential->id }}" onclick="optref({{ $referential->id }})">
                                    <td class="tbl-d-none">{{ $referential->id }}</td>
                                    <td>{{ $referential->name }}</td>
                                    <td>{{ $referential->description }}</td>
                                    <td>{{ date("m/d/Y", strtotime($referential->created_at)) }}</td>
                                    <input type="hidden" class="referential" value="{{ json_encode($referential) }}">
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex"><div class="mx-auto">{{ $referentials->links() }}</div></div>
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
<div class="modal fade" id="addreferential" tabindex="-1" aria-labelledby="addreferentialLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="addreferentialLabel">Add Referential</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="frmreferential" action="{{ route('settings.add_referential') }}">
            @csrf
            <div class="modal-body p-4 py-3">
                <input type="hidden" id="txtreferentialid" name="txtreferentialid">
                <div class="form-data mb-3">
                    <label for="txtreferentialmod" class="form-label">Module</label>
                    <select class="form-select py-2 px-3 rounded-3" name="txtreferentialmod" id="txtreferentialmod" required>
                        <option value="">Select Module</option>
                        @foreach($modules as $module)
                        <option value="{{ $module->id }}">{{ $module->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-data mb-3">
                    <label for="txtreferential" class="form-label">Name</label>
                    <input type="text" class="form-control py-2 px-3 rounded-3" name="txtreferential" id="txtreferential" placeholder="Enter Referential Name" required>
                </div>
                <div class="form-data mb-3">
                    <label for="txtreferentialdesc" class="form-label">Description</label>
                    <textarea class="form-control py-2 px-3 rounded-3" name="txtreferentialdesc" id="txtreferentialdesc" placeholder="Enter Referential Description"></textarea>
                </div>
                <h5 class="text-center mb-3 msubtitle">Choices</h5>
                <div class="list_cont">
                    <ul id="refchoices"></ul>
                </div>
                <input type="hidden" name="txtchoices" id="txtchoices">
            </div>
            <div class="modal-footer p-4 py-3">
                <button type="submit" class="btn btn-add mx-2 px-3 py-2 rounded-3"><i class="fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Save</button>
                <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="optref" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog xs-modal">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="optrefLabel">Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 py-3 m-3 text-center">
                <button
                    class="btn btn-info text-white p-2 w-100 fs-6
                    @if(!$checker->routePermission('settings.update_referential'))
                    disabled
                    @endif
                    "
                    onclick="editreferential()"
                >
                    <i class="fa-solid fa-pen-to-square me-2 fs-5"></i>
                    Edit
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    let ref_id = "";
    let list = [];

    function optref(id){
        ref_id = id;
        $("#optref").modal("show");
    }

    function addreferential(){
        $("#addreferentialLabel").text("Add Referential");
        $("#frmreferential").attr("action", "{{ route('settings.add_referential') }}");
        $("#txtreferentialid").val("");
        $("#txtreferentialmod").val("");
        $("#txtreferential").val("");
        $("#txtreferentialdesc").val("");
        $("#refchoices").html("<li class='mb-2 add'>" +
            "<div class='row mx-0'>" +
                "<div class='col-9 col-md-10 ps-0'><input type='text' class='form-control py-2 px-3 rounded-3 new' placeholder='Please enter a choice'></div>" +
                "<div class='col-3 col-sm-2 pe-0'><button type='button' class='btn btn-add w-100 py-2 px-4 rounded-3'><i class='fa-solid fa-plus'></i></button></div>" +
            "</div>" +
        "</li>");
        refaction();
        list = [];
        $("#txtchoices").val("");
        $("#addreferential").modal("show");
    }

    function genreflist(){
        let html = "";
        $.each(list, function(x, item){
            html += "<li class='mb-2 minus'>" +
                "<div class='row mx-0'>" +
                    "<div class='col-9 col-md-10 ps-0'><input type='text' class='form-control py-2 px-3 rounded-3 border-0 item' value='" + item + "' readonly></div>" +
                    "<div class='col-3 col-md-2 pe-0'><button type='button' class='btn btn-white w-100 py-2 px-4 rounded-3 text-danger border-danger'><i class='fa-solid fa-trash-alt'></i></button></div>" +
                "</div>" +
            "</li>";
        });
        html += "<li class='mb-2 add'>" +
            "<div class='row mx-0'>" +
                "<div class='col-9 col-md-10 ps-0'><input type='text' class='form-control py-2 px-3 rounded-3 new' placeholder='Please enter a choice'></div>" +
                "<div class='col-3 col-md-2 pe-0'><button type='button' class='btn btn-add w-100 py-2 px-4 rounded-3'><i class='fa-solid fa-plus'></i></button></div>" +
            "</div>" +
        "</li>";
        $("#refchoices").html(html);
        $("#refchoices").find(".add .new").focus();
        refaction();
    }

    function refaction(){
        $("#txtchoices").val(JSON.stringify(list));
        $("#refchoices li").each(function(i){
            const obj = $(this);
            if(obj.hasClass("add")){
                obj.find("button").click(function(){
                    if(obj.find(".new").val() != ""){
                        list.push(obj.find(".new").val());
                        genreflist();
                    }
                });
                obj.find("input:text").on("keydown", function(e){
                    if(e.key === 'Enter' || e.keyCode === 13){
                        e.preventDefault();
                        if(obj.find(".new").val() != ""){
                            list.push(obj.find(".new").val());
                            genreflist();
                        }
                    }
                })
            }
            else if(obj.hasClass("minus")){
                obj.find("button").click(function(){
                    list.splice(i, 1);
                    genreflist();
                });
            }
        });
    }

    function editreferential(id){
        $("#optref").modal("hide");
        id = ref_id;
        const obj = $("#" + id);
        $("#addreferentialLabel").text("Edit Referential");
        $("#frmreferential").attr("action", "{{ route('settings.update_referential') }}");
        $("#txtreferentialid").val(id);
        const arr = JSON.parse(obj.find(".referential").val());
        $("#txtreferentialmod").val(arr.module_id);
        $("#txtreferential").val(arr.name);
        $("#txtreferentialdesc").val(arr.description);
        list = JSON.parse(arr.choices);
        genreflist();
        $("#addreferential").modal("show");
    }
</script>
@endsection