@extends('layouts.app', ['title' => 'Requests'])
@section('content')
<div class="main">
    @include('layouts.navbar')
    <div class="mbody">
        @if(session('success'))
            @include('layouts.toast', ['type' => 'success', 'message' => session('success')])
        @elseif(session('error'))
            @include('layouts.toast', ['type' => 'danger', 'message' => session('error')])
        @else
            @include('layouts.toast', ['type' => '', 'message' => ''])
        @endif
        @include('layouts.navtitle', ['navtitle' => 'Requests'])
        <div class="mcontent">
            <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                <form method="get" action="{{ route('requests.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="input-group inputg">
                            <input type="text" class="form-control py-2 px-3" name="txtreqsearch" placeholder="Type keyword here..." value="{{ isset($searchkey) ? $searchkey : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3 mb-md-0">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="txtreqdatefrom" placeholder="Select Date Start" value="{{ isset($datefrom) ? $datefrom : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3 mb-md-0">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="txtreqdateto" placeholder="Select Date End" value="{{ isset($dateto) ? $dateto : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 text-end">
                        <button class="btn btn-secondary py-2 px-4 rounded-3 me-2 btn-sm-100">Submit Search</button>
                        <button type="button" class="btn btn-add py-2 px-4 rounded-3 me-2 btn-sm-50 btn-me" onclick="addreq()"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Add Request</button>
                        <button type="button" class="btn btn-warning py-2 px-4 rounded-3 btn-sm-50" onclick="scanQr()"><i class="fa-solid fa-expand"></i>&nbsp;&nbsp;Scan QR</button>
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
                                <th scope="col" class="align-top">Request Type</th>
                                <th scope="col" class="align-top">Requested By</th>
                                <th scope="col" class="align-top tbl-d-none">Type</th>
                                <th scope="col" class="align-top tbl-d-none">Validity</th>
                                <th scope="col" class="align-top tbl-d-none">Approved By</th>
                                <th scope="col" class="align-top tbl-d-none">Checked By</th>
                                <th scope="col" class="align-top text-center">Status</th>
                                <th scope="col" class="align-top">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($reqs as $req)
                            <tr id="req_{{ $req->id }}" onclick="optreq({{ $req->id }}, '{{ $req->qr_code }}');">
                                <td class="tbl-d-none">{{ $req->id }}</td>
                                <td>{{ $req->request_type }}</td>
                                <td>{{ $req->reqBy->fullname }}</td>
                                <td class="tbl-d-none">{{ $req->type }}</td>
                                <td class="tbl-d-none">{{ $req->valid_from != '' ? date("m/d/Y", strtotime($req->valid_from)) . ' to ' . date("m/d/Y", strtotime($req->valid_to)) : '' }}</td>
                                <td class="tbl-d-none">{{ $req->appBy ? $req->appBy->name : '' }}</td>
                                <td class="tbl-d-none">{{ $req->chkBy ? $req->chkBy->name : '' }}</td>
                                <td class="text-center"><label class="badge {{ $req->requestStatus() }} p-2 px-3">{{ $req->request_status }}</span></td>
                                <td>{{ date("m/d/y", strtotime($req->created_at)) }}</td>
                                <input type="hidden" class="req" value="{{ json_encode($req) }}">
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex"><div class="mx-auto">{{ $reqs->links() }}</div></div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="refsetup" value="{{ json_encode($refsetup) }}">
</div>
<div class="modal fade" id="addreq" tabindex="-1" aria-labelledby="addreqLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
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
                    <div class="row" id="reqnew">
                        <div class="col-md-6 form-data chk_by">
                            <label for="reqcheckedby" class="form-label">Checked By</label>
                            <input type="hidden" name="reqcheckedbyid" id="reqcheckedbyid">
                            <input type="text" class="form-control py-2 px-3 rounded-3" id="reqcheckedby" name="reqcheckedby" placeholder="Select Checked By" disabled>
                        </div>
                        <div class="col-md-6 form-data app_by">
                            <label for="reqapprovedby" class="form-label">Approved By</label>
                            <input type="hidden" name="reqapprovedbyid" id="reqapprovedbyid">
                            <input type="text" class="form-control py-2 px-3 rounded-3" id="reqapprovedby" name="reqapprovedby" placeholder="Select Approved By" disabled>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-4 py-3">
                    <button type="submit" id="btnapprove" name="btnapprove" value="approve" class="btn btn-success px-3 py-2 rounded-3 btnapprove"><i class="fa-solid fa-thumbs-up"></i>&nbsp;&nbsp;Approve</button>
                    <button type="submit" name="btnsubmit" value="submit" class="btn btn-add mx-2 px-3 py-2 rounded-3"><i class="fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Save</button>
                    <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="searchresident" tabindex="-1" aria-labelledby="searchresidentLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="searchresidentLabel">Search Complainant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-8">
                        <div class="input-group inputg">
                            <input type="text" class="form-control py-2 px-3" id="txtressearch" placeholder="Type keyword here..."">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-secondary py-2 px-4 rounded-3" onclick="filterresident()">Submit</button>
                    </div>
                </div>
                <div class="table-responsive">
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
</div>
<div class="modal fade" id="deletereq" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog xs-modal">
        <div class="modal-content rounded-4">
            <form method="post" action="{{ route('requests.delete_request') }}">
                @csrf
                <div class="modal-body p-4 py-3 mt-3">
                    <input type="hidden" id="compdelid" name="compdelid">
                    <h5 class="text-center">Delete Request?</h5>
                </div>
                <div class="modal-footer p-4 pb-4 pt-2 d-block text-center border-0">
                    <button type="submit" class="btn btn-danger me-2 px-3 py-2 rounded-3"><i class="fa-solid fa-trash-alt"></i>&nbsp;&nbsp;Ok</button>
                    <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="viewreqqr" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog xs-modal">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="viewqrLabel">QR Code Scanner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <iframe class="iframe" id="reqqrcode" src="#"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="scanqr" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog qr-modal">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="viewqrLabel">Scan QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <video id="qr-video" class="d-none"></video>
                <canvas id="qr-canvas"></canvas>
                <div id="qr-result" class="text-center"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="viewreq" tabindex="-1" aria-labelledby="addreqLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="addreqLabel">View Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="lblid">
                <div class="row mb-3">
                    <div class="col-md-6 form-data">
                        <label class="form-label">Request Type</label>
                        <p id="lblreqtype"></p>
                    </div>
                    <div class="col-md-6 form-data">
                        <label class="form-label">Transaction Type</label>
                        <p id="lbltranstype">Transaction Type</p>
                    </div>
                </div>
                <div class="form-data mb-3">
                    <label class="form-label">Address</label>
                    <p id="lbladdress"></p>
                </div>
                <div class="form-data mb-3">
                    <label class="form-label">Details</label>
                    <p id="lbldetails"></p>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 form-data">
                        <label class="form-label">Delivery/Pullout Date</label>
                        <p id="lbltransdate"></p>
                    </div>
                    <div class="col-md-4 form-data">
                        <label class="form-label">Valid From</label>
                        <p id="lblvalidfrom"></p>
                    </div>
                    <div class="col-md-4 form-data">
                        <label class="form-label">Valid To</label>
                        <p id="lblvalidto"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 form-data">
                        <label class="form-label">Requested By</label>
                        <p id="lblrequestedby"></p>
                    </div>
                    <div class="col-md-6 form-data">
                        <label for="reqstatus" class="form-label">Request Status</label>
                        <p id="lblrequestedstatus"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-4 py-3">
                <button type="button" name="btnsubmit" class="btn btn-warning mx-2 px-3 py-2 rounded-3" onclick="markaschk()"><i class="fa-solid fa-check"></i>&nbsp;&nbsp;Mark as Checked</button>
                <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="optreq" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog xs-modal">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="optreqLabel">Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 py-3 m-3 text-center">
                <div class="row mb-3">
                    <div class="col-6 p-2"><button class="btn btn-info text-white p-2 w-100 fs-6" onclick="editreq()"><i class="fa-solid fa-pen-to-square me-2 fs-5"></i>Edit</button></div>
                    <div class="col-6 p-2"><button class="btn btn-danger p-2 w-100 fs-6" onclick="deletereq()"><i class="fa-solid fa-trash-alt me-2 fs-5"></i>Delete</button></div>
                </div>
                <button class="btn btn-warning text-white p-2 w-100 fs-6" onclick="viewqrcode()"><i class="fa-solid fa-pen-to-square me-2 fs-5"></i>View QR</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js') }}/jsQR.js"></script>
<script type="text/javascript" src="{{ asset('js') }}/qrScan.js"></script>
<script>
    let req_id = "";
    let req_qrcode = "";

    $(function(){
        loadRefs();
        $("#txtressearch").on("keyup", function(e){
            if(e.keyCode == 13){
                filterresident();
            }
        });
    });

    function optreq(id, qrcode){
        req_id = id;
        req_qrcode = qrcode;
        $("#optreq").modal("show");
    }

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
                        "<input type='hidden' class='fulladdress' value='" + item.fulladdress + "'>" +
                    "</tr>");
                });
                $("#tblfilterres tr").each(function(){
                    $(this).click(function(){
                        $("#reqrequestedbyid").val($(this).attr("id"));
                        $("#reqrequestedby").val($(this).find("td").eq(0).text());
                        $("#reqaddress").val($(this).find(".fulladdress").val());
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
        $("#btnapprove").hide();
    }

    function editreq(){
        $("#optreq").modal("hide");
        id = req_id;
        const obj = $("#req_" + id);
        const arr = JSON.parse(obj.find(".req").val());
        $("#reqid").val(id);
        $("#reqtype").val(arr.request_type);
        $("#reqtranstype").val(arr.type);
        $("#reqaddress").val(arr.address);
        $("#reqdetails").val(arr.details);
        $("#reqtransdate").val(formatDate(arr.pullout_delivery_date));
        $("#reqvalidfrom").val(formatDate(arr.valid_from));
        $("#reqvalidto").val(formatDate(arr.valid_to));
        $("#reqrequestedbyid").val(arr.req_by.id);
        $("#reqstatus").val(arr.request_status);
        $("#reqrequestedby").val(arr.req_by.last_name + ", " + arr.req_by.first_name + " " + arr.req_by.middle_name.charAt(0) + ".");
        if(arr.chk_by || arr.app_by){
            $("#reqnew").show();
        }
        if(arr.app_by)
        {
            $("#reqapprovedbyid").val(arr.app_by.id);
            $("#reqapprovedby").val(arr.app_by.name);
            $("#reqnew .app_by").show();
        }
        else
        {
            $("#reqapprovedbyid").val("");
            $("#reqapprovedby").val("");
            $("#reqnew .app_by").hide();
        }
        if(arr.chk_by)
        {
            $("#reqcheckedbyid").val(arr.chk_by.id);
            $("#reqcheckedby").val(arr.chk_by.name);
            $("#reqnew .chk_by").show();
        }
        else
        {
            $("#reqcheckedbyid").val("");
            $("#reqcheckedby").val("");
            $("#reqnew .chk_by").hide();
        }
        if(arr.request_status != "Approved"){     
            $("#btnapprove").show();
        }
        else{
            $("#btnapprove").hide();
        }
        $("#addreqLabel").text("Edit Request");
        $("#frmreq").attr("action", "{{ route('requests.update_request') }}");
        $("#addreq").modal("show");
    }

    function deletereq(id){
        $("#optreq").modal("hide");
        id = req_id;
        $("#compdelid").val(id);
        $("#deletereq").modal("show");
    }


    function viewqrcode(){
        $("#optreq").modal("hide");
        $("#reqqrcode").attr("src", "{{ route('getqrcode') }}?qrcode=" + req_qrcode);
        $("#viewreqqr").modal("show");
    }

    function scanQr(){
        $("#scanqr").modal("show");
        qrScan();
    }

    function openRequest(qrcode){
        $.get("{{ route('find_request') }}?qrcode=" + qrcode, function(data, status){
            if(status == "success"){
                $("#lblid").val(data.id);
                $("#lblreqtype").text(data.request_type);
                $("#lbltranstype").text(data.type);
                $("#lbladdress").text(data.address);
                $("#lbldetails").text(data.details);
                $("#lbltransdate").text(formatDate(data.pullout_delivery_date));
                $("#lblvalidfrom").text(formatDate(data.valid_from));
                $("#lblvalidto").text(formatDate(data.valid_to));
                $("#lblrequestedby").text(data.req_by.last_name + ", " + data.req_by.first_name + " " + data.req_by.middle_name.charAt(0) + ".");
                $("#lblrequestedstatus").text(data.request_status);
                $("#viewreq").modal("show");
                $("#scanqr").modal("hide");
            }
        });
    }

    function markaschk(){
        let params = {
            _token: "{{ csrf_token() }}",
            id: $("#lblid").val()
        };
        $.post("{{ route('requests.check_request') }}", params).done(function(res){
            showtoast("Success", "Request has been Checked.");
            setTimeout(function(){
                location.reload();
            }, 2000);
        });
    }
</script>
@endsection