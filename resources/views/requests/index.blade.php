@extends('layouts.app', ['title' => 'Requests'])
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
        @include('layouts.navtitle', ['navtitle' => 'Requests'])
        <div class="mcontent">
            <div class="card m-3 mx-5 p-3 shadow border-light rounded-4">
                <form method="get" action="{{ route('requests.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group inputg">
                            <input type="text" class="form-control py-2 px-3" name="txtreqsearch" placeholder="Type keyword here..." value="{{ isset($searchkey) ? $searchkey : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="txtreqdatefrom" placeholder="Select Date Start" value="{{ isset($datefrom) ? $datefrom : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="txtreqdateto" placeholder="Select Date End" value="{{ isset($dateto) ? $dateto : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-secondary py-2 px-4 rounded-3">Submit Search</button>
                        <button type="button" class="btn btn-add py-2 px-4 rounded-3 float-end" onclick="addreq()"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Add Request</button>
                    </div>
                </div>
                </form>
            </div>
            <div class="card m-3 mx-5 p-3 shadow border-light rounded-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Request Type</th>
                            <th scope="col">Requested By</th>
                            <th scope="col">Type</th>
                            <th scope="col">Validity</th>
                            <th scope="col">Approved By</th>
                            <th scope="col">Checked By</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created At</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($reqs as $req)
                        <tr id="req_{{ $req->id }}">
                            <td class="align-middle">{{ $req->id }}</td>
                            <td class="align-middle">{{ $req->request_type }}</td>
                            <td class="align-middle">{{ $req->reqBy->fullname }}</td>
                            <td class="align-middle">{{ $req->type }}</td>
                            <td class="align-middle">{{ $req->valid_from != '' ? date("m/d/Y", strtotime($req->valid_from)) . ' to ' . date("m/d/Y", strtotime($req->valid_to)) : '' }}</td>
                            <td class="align-middle">{{ $req->appBy ? $req->appBy->name : '' }}</td>
                            <td class="align-middle">{{ $req->chkBy ? $req->chkBy->name : '' }}</td>
                            <td class="align-middle">{{ $req->request_status }}</td>
                            <td class="align-middle">{{ date("m/d/y", strtotime($req->created_at)) }}</td>
                            <td class="actions align-middle text-center">
                                <button class="btn btn-white p-1 px-2 mx-1 border-success" onclick="editreq({{ $req->id }})"><i class="fa-solid fa-pen-to-square text-success"></i></button>
                                <button class="btn btn-white p-1 px-2 mx-1 border-danger" onclick="deletereq({{ $req->id }})"><i class="fa-solid fa-trash-alt text-danger"></i></button>
                            </td>
                            <input type="hidden" class="req" value="{{ json_encode($req) }}">
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" id="refsetup" value="{{ json_encode($refsetup) }}">
</div>
<div class="modal fade" id="addreq" tabindex="-1" aria-labelledby="addreqLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-md">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="addreqLabel">Add Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="frmreq" action="#">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" id="reqid" name="reqid">
                    <div class="row mb-3">
                        <div class="col-md-6 form-data">
                            <label for="reqtype" class="form-label">Request Type</label>
                            <select class="form-select py-2 px-3 rounded-3" name="reqtype" id="reqtype" required>
                                <option value="">Select Request Type</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-data">
                            <label for="reqtranstype" class="form-label">Transaction Type</label>
                            <select class="form-select py-2 px-3 rounded-3" id="reqtranstype" name="reqtranstype" required>
                                <option value="">Select Transaction Type</option>
                                <option value="Delivery">Delivery</option>
                                <option value="Pullout">Pullout</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-data mb-3">
                        <label for="reqaddress" class="form-label">Address</label>
                        <input type="text" class="form-control py-2 px-3 rounded-3" name="reqaddress" id="reqaddress" required>
                    </div>
                    <div class="form-data mb-3">
                        <label for="reqdetails" class="form-label">Details</label>
                        <textarea class="form-control py-2 px-3 rounded-3" name="reqdetails" id="reqdetails" style="height: 100px;" required></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 form-data">
                            <label for="reqtransdate" class="form-label">Delivery/Pullout Date</label>
                            <input type="text" class="form-control py-2 px-3 datepicker rounded-3" id="reqtransdate" name="reqtransdate" placeholder="__/__/____" required>
                        </div>
                        <div class="col-md-4 form-data">
                            <label for="reqvalidfrom" class="form-label">Valid From</label>
                            <input type="text" class="form-control py-2 px-3 datepicker rounded-3" id="reqvalidfrom" name="reqvalidfrom" placeholder="__/__/____" required>
                        </div>
                        <div class="col-md-4 form-data">
                            <label for="reqvalidto" class="form-label">Valid To</label>
                            <input type="text" class="form-control py-2 px-3 datepicker rounded-3" id="reqvalidto" name="reqvalidto" placeholder="__/__/____" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-data">
                            <label for="reqrequestedby" class="form-label">Requested By</label>
                            <input type="hidden" name="reqrequestedbyid" id="reqrequestedbyid" value="{{ auth()->user()->role_id != '1' ? auth()->user()->id : '' }}">
                            <input type="text" class="form-control py-2 px-3 rounded-3" id="reqrequestedby" name="reqrequestedby" placeholder="Select Requested By" value="{{ auth()->user()->role_id != '1' ? auth()->user()->name : '' }}" onfocus="searchresident();" {{ auth()->user()->role_id != '1' ? 'readonly' : '' }} required>
                        </div>
                        <div class="col-md-6 form-data">
                            <label for="reqstatus" class="form-label">Request Status</label>
                            <select class="form-select py-2 px-3 rounded-3" name="reqstatus" id="reqstatus" required>
                                <option value="">Select Request Status</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" id="reqnew">
                        <div class="col-md-6 form-data">
                            <label for="reqcheckedby" class="form-label">Checked By</label>
                            <input type="hidden" name="reqcheckedbyid" id="reqcheckedbyid">
                            <input type="text" class="form-control py-2 px-3 rounded-3" id="reqcheckedby" name="reqcheckedby" placeholder="Select Checked By" disabled>
                        </div>
                        <div class="col-md-6 form-data">
                            <label for="reqapprovedby" class="form-label">Approved By</label>
                            <input type="hidden" name="reqapprovedbyid" id="reqapprovedbyid">
                            <input type="text" class="form-control py-2 px-3 rounded-3" id="reqapprovedby" name="reqapprovedby" placeholder="Select Approved By" disabled>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-4 py-3">
                    <button type="submit" name="btnapprove" class="btn btn-success px-3 py-2 rounded-3 btnapprove"><i class="fa-solid fa-thumbs-up"></i>&nbsp;&nbsp;Approve</button>
                    <button type="submit" name="btnsubmit" class="btn btn-add mx-2 px-3 py-2 rounded-3"><i class="fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Save Request</button>
                    <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="searchresident" tabindex="-1" aria-labelledby="searchresidentLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="searchresidentLabel">Search Complainant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="input-group inputg">
                            <input type="text" class="form-control py-2 px-3" id="txtressearch" placeholder="Type keyword here..."">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-secondary py-2 px-4 rounded-3" onclick="filterresident()">Submit</button>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Resident Name</th>
                            <th scope="col">Address</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody id="tblfilterres">
                        <tr>
                            <td colspan="3" class="text-center">Search Resident</td>
                        </tr>  
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deletereq" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content rounded-4">
            <form method="post" action="{{ route('requests.delete_request') }}">
                @csrf
                <div class="modal-body p-4 py-3 mt-3">
                    <input type="hidden" id="compdelid" name="compdelid">
                    <h5 class="text-center">Delete Request?</h5>
                </div>
                <div class="modal-footer p-5 py-3 justify-content-md-center border-0">
                    <button type="submit" class="btn btn-danger me-2 px-3 py-2 rounded-3"><i class="fa-solid fa-trash-alt"></i>&nbsp;&nbsp;Ok</button>
                    <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function(){
        loadRefs();
        $("#txtressearch").on("keyup", function(e){
            if(e.keyCode == 13){
                filterresident();
            }
        });
    });

    function loadRefs(){
        const arr = JSON.parse($("#refsetup").val());
        $.each(arr, function(i, val){
            const list = JSON.parse(val.referential.choices);
            $.each(list, function(x, xval){
                $("#" + val.for).append("<option value='" + xval + "'>" + xval + "</option>");
            });
        });
    }

    function searchresident(){
        $("#searchresidentLabel").text("Select Requested By");
        $("#tblfilterres").html("<tr><td colspan='3' class='text-center'>Search Resident</td></tr>");
        $("#searchresident").modal("show");
        $("#txtressearch").val("");
        $("#addreq").modal("hide");
        setTimeout(function(){
            $("#txtressearch").focus();
        }, 500);
    }

    function filterresident(){
        let key = $("#txtressearch").val();
        $.get("{{ route('filter_residents') }}?key=" + key, function(data, status){       
            if(status == "success"){
                if(data.length == 0){
                    $("#tblfilterres").html("<tr><td colspan='3' class='text-center'>Search Resident</td></tr>");
                }
                else{
                    $("#tblfilterres").html("");
                }
                $.each(data, function(i, item){
                    $("#tblfilterres").append("<tr id='" + item.id + "'>" +
                        "<td>" + item.last_name + ", " + item.first_name + " " + item.middle_name.charAt(0) + ".</td>" +
                        "<td>" + item.home_address + "</td>" +
                        "<td>" + (item.home_status == 1 ? "Active" : "Inactive") + "</td>" +
                    "</tr>");
                });
                $("#tblfilterres tr").each(function(){
                    $(this).click(function(){
                        $("#reqrequestedbyid").val($(this).attr("id"));
                        $("#reqrequestedby").val($(this).find("td").eq(0).text());
                        $("#searchresident").modal("hide");
                        $("#addreq").modal("show");
                    });
                });
            }
        });
    }

    function addreq(){
        $("#addreqLabel").text("Add Request");
        $("#frmreq").find("input[type='text'], select, textarea").val("");
        $("#reqid").val("");
        $("#reqnew").hide();
        $("#frmreq").attr("action", "{{ route('requests.add_request') }}");
        $("#addreq").modal("show");
    }

    function editreq(id){
        const obj = $("#req_" + id);
        const arr = JSON.parse(obj.find(".req").val());
        console.log(arr);
        $("#reqid").val(id);
        $("#reqtype").val(arr.request_type);
        $("#reqtranstype").val(arr.type);
        $("#reqaddress").val(arr.address);
        $("#reqdetails").val(arr.details);
        $("#reqtransdate").val(formatDate(arr.pullout_delivery_date));
        $("#reqvalidfrom").val(formatDate(arr.valid_from));
        $("#reqvalidto").val(formatDate(arr.valid_to));
        $("#reqrequestedbyid").val(arr.req_by.id);
        $("#reqrequestedby").val(arr.req_by.last_name + ", " + arr.req_by.first_name + " " + arr.req_by.middle_name.charAt(0) + ".");
        if(arr.chk_by || arr.app_by){
            $("#reqnew").show();
        }
        if(arr.app_by)
        { $("#reqnew .col-md-6:first-child").show(); }
        else
        { $("#reqnew .col-md-6:first-child").hide(); }
        if(arr.chk_by)
        { $("#reqnew .col-md-6:last-child").show(); }
        else
        { $("#reqnew .col-md-6:last-child").hide(); }
        $("#addreqLabel").text("Edit Request");
        $("#frmreq").attr("action", "{{ route('requests.update_request') }}");
        $("#addreq").modal("show");
    }

    function deletereq(id){
        $("#compdelid").val(id);
        $("#deletereq").modal("show");
    }
</script>
@endsection