@extends('layouts.app', ['title' => 'Complaints'])
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
        @include('layouts.navtitle', ['navtitle' => 'Complaints'])
        <div class="mcontent">
            <div class="card m-3 mx-5 p-3 shadow border-light rounded-4">
                <form method="get" action="{{ route('complaints.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group inputg">
                            <input type="text" class="form-control py-2 px-3" name="txtcomplaintsearch" placeholder="Type keyword here..." value="{{ isset($searchkey) ? $searchkey : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="txtcomplaintdatefrom" placeholder="Select Date Start" value="{{ isset($datefrom) ? $datefrom : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="txtcomplaintdateto" placeholder="Select Date End" value="{{ isset($dateto) ? $dateto : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-secondary py-2 px-4 rounded-3">Submit Search</button>
                        <button type="button" class="btn btn-add py-2 px-4 rounded-3 float-end" onclick="addcomplaint()"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Add complaint</button>
                    </div>
                </div>
                </form>
            </div>
            <div class="card m-3 mx-5 p-3 shadow border-light rounded-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Complaint Type</th>
                            <th scope="col">Complainant</th>
                            <th scope="col">Defendant</th>
                            <th scope="col">Details</th>
                            <th scope="col">Reported To</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created At</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($complaints as $complaint)
                        <tr id="comp_{{ $complaint->id }}">
                            <td class="align-middle">{{ $complaint->id }}</td>
                            <td class="align-middle">{{ $complaint->complaint_type }}</td>
                            <td class="align-middle">{{ $complaint->resident->fullname }}</td>
                            <td class="align-middle">{{ $complaint->defendant->fullname }}</td>
                            <td class="align-middle">{{ $complaint->details }}</td>
                            <td class="align-middle">{{ $complaint->reported_to->name }}</td>
                            <td class="align-middle">{{ $complaint->status }}</td>
                            <td class="align-middle">{{ date("m/d/y", strtotime($complaint->created_at)) }}</td>
                            <td class="actions align-middle text-center">
                                <button class="btn btn-white p-1 px-2 mx-1 border-success" onclick="editcomplaint({{ $complaint->id }})"><i class="fa-solid fa-pen-to-square text-success"></i></button>
                                <button class="btn btn-white p-1 px-2 mx-1 border-danger" onclick="deletecomplaint({{ $complaint->id }})"><i class="fa-solid fa-trash-alt text-danger"></i></button>
                            </td>
                            <input type="hidden" class="complaint" value="{{ json_encode($complaint) }}">
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" id="refsetup" value="{{ json_encode($refsetup) }}">
</div>
<div class="modal fade" id="addcomplaint" tabindex="-1" aria-labelledby="addcomplaintLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-md">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="addcomplaintLabel">Add Complaint</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="frmcomplaint" action="#">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" id="compid" name="compid">
                    <div class="row mb-3">
                        <div class="col-md-6 form-data">
                            <label for="comptype" class="form-label">Complaint Type</label>
                            <select class="form-select py-2 px-3 rounded-3" name="comptype" id="comptype" required>
                                <option value="">Select Complaint Type</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-data">
                            <label for="compstatus" class="form-label">Complaint Status</label>
                            <select class="form-select py-2 px-3 rounded-3" name="compstatus" id="compstatus" required>
                                <option value="">Select Complaint Status</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-data">
                            <label for="compresname" class="form-label">Complainant</label>
                            <input type="hidden" name="compresid" id="compresid">
                            <input type="text" class="form-control py-2 px-3 rounded-3" id="compresname" placeholder="Select Complainant" onfocus="searchresident('res')">
                        </div>
                        <div class="col-md-6 form-data">
                            <label for="compdefname" class="form-label">Defendant</label>
                            <input type="hidden" name="compdefid" id="compdefid">
                            <input type="text" class="form-control py-2 px-3 rounded-3" id="compdefname" placeholder="Select Defendant" onfocus="searchresident('def')">
                        </div>
                    </div>
                    <div class="form-data mb-3">
                        <label for="comppurpose" class="form-label">Purpose</label>
                        <textarea class="form-control py-2 px-3 rounded-3" name="comppurpose" id="comppurpose" placeholder="Enter Purpose"></textarea>
                    </div>
                    <div class="form-data mb-3">
                        <label for="compremarks" class="form-label">Remarks / Comments</label>
                        <textarea class="form-control py-2 px-3 rounded-3" name="compremarks" id="compremarks" placeholder="Enter Remarks / Comments"></textarea>
                    </div>
                    <div class="form-data">
                        <label class="form-label">Reported to</label>
                        <span class="ms-2">{{ auth()->user()->name }}</span>
                    </div>
                </div>
                <div class="modal-footer p-4 py-3">
                    <button type="submit" class="btn btn-add mx-2 px-3 py-2 rounded-3"><i class="fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Save Complaint</button>
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
<div class="modal fade" id="deletecomplaint" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content rounded-4">
            <form method="post" action="{{ route('complaints.delete_complaint') }}">
                @csrf
                <div class="modal-body p-4 py-3 mt-3">
                    <input type="hidden" id="compdelid" name="compdelid">
                    <h5 class="text-center">Delete Complaint?</h5>
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
    let scomptype = "";

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

    function searchresident(type){
        scomptype = type;
        if(type == "res"){
            $("#searchresidentLabel").text("Search for Complainant");
        }
        else{
            $("#searchresidentLabel").text("Search for Defendant");
        }
        $("#txtressearch").val("");
        $("#tblfilterres").html("<tr><td colspan='3' class='text-center'>Search Resident</td></tr>");
        $("#searchresident").modal("show");
        $("#addcomplaint").modal("hide");
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
                        "<td>" + item.last_name + ", " + item.first_name + " " + item.middle_name.charAt(0) + "</td>" +
                        "<td>" + item.home_address + "</td>" +
                        "<td>" + (item.home_status == 1 ? "Active" : "Inactive") + "</td>" +
                    "</tr>");
                });
                $("#tblfilterres tr").each(function(){
                    $(this).click(function(){
                        if(scomptype == "res"){
                            $("#compresid").val($(this).attr("id"));
                            $("#compresname").val($(this).find("td").eq(0).text());
                        }
                        else{
                            $("#compdefid").val($(this).attr("id"));
                            $("#compdefname").val($(this).find("td").eq(0).text());
                        }
                        $("#addcomplaint").modal("show");
                        $("#searchresident").modal("hide");
                    });
                });
            }
        });
    }

    function addcomplaint(){
        $("#addcomplaintLabel").text("Add Complaint");
        $("#frmcomplaint").find("input[type='text'], select, textarea").val("");
        $("#compid").val("");
        $("#compstatus").val("Pending");
        $("#compstatus").find("option").each(function(){
            if($(this).val() != "Pending"){
                $(this).prop("disabled", true);
            }
        });
        $("#frmcomplaint").attr("action", "{{ route('complaints.add_complaint') }}");
        $("#addcomplaint").modal("show");
    }

    function editcomplaint(id){
        const obj = $("#comp_" + id);
        const arr = JSON.parse(obj.find(".complaint").val());
        $("#compid").val(id);
        $("#comptype").val(arr.complaint_type);
        $("#compstatus").val(arr.status);
        $("#compstatus").find("option").prop("disabled", false);
        $("#compresid").val(arr.resident_id);
        $("#compresname").val(arr.resident.fullname);
        $("#compdefid").val(arr.complaint_to);
        $("#compdefname").val(arr.defendant.fullname);
        $("#comppurpose").val(arr.purpose);
        $("#compremarks").val(arr.details);
        $("#addcomplaintLabel").text("Edit Complaint");
        $("#frmcomplaint").attr("action", "{{ route('complaints.update_complaint') }}");
        $("#addcomplaint").modal("show");
    }

    function deletecomplaint(id){
        $("#compdelid").val(id);
        $("#deletecomplaint").modal("show");
    }
</script>
@endsection