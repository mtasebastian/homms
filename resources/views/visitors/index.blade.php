@extends('layouts.app', ['title' => 'Visitors'])
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
        @include('layouts.navtitle', ['navtitle' => 'Visitors'])
        <div class="mcontent">
            <div class="card m-3 mx-5 p-3 shadow border-light rounded-4">
                <form method="get" action="{{ route('visitors.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group inputg">
                            <input type="text" class="form-control py-2 px-3" name="txtvisitorsearch" placeholder="Type keyword here..." value="{{ isset($searchkey) ? $searchkey : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="txtvisitordatefrom" placeholder="Select Date Start" value="{{ isset($datefrom) ? $datefrom : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="txtvisitordateto" placeholder="Select Date End" value="{{ isset($dateto) ? $dateto : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-secondary py-2 px-4 rounded-3">Submit Search</button>
                        <button type="button" class="btn btn-add py-2 px-4 rounded-3 float-end" onclick="addvisitor()"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Add visitor</button>
                    </div>
                </div>
                </form>
            </div>
            <div class="card m-3 mx-5 p-3 shadow border-light rounded-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Visitor Type</th>
                            <th scope="col">Visitor Name</th>
                            <th scope="col">Purpose</th>
                            <th scope="col">Time In</th>
                            <th scope="col">Time Out</th>
                            <th scope="col">Created At</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($visitors as $visitor)
                        <tr id="res_{{ $visitor->id }}">
                            <td class="align-middle">{{ $visitor->id }}</td>
                            <td class="align-middle">{{ $visitor->visitor_type }}</td>
                            <td class="align-middle">{{ $visitor->name }}</td>
                            <td class="align-middle w-25">{{ $visitor->purpose }}</td>
                            <td class="align-middle">{{ date("m/d/Y h:i A", strtotime($visitor->time_in)) }}</td>
                            <td class="align-middle">{{ $visitor->time_out != '' ? date("m/d/Y h:i A", strtotime($visitor->time_out)) : '' }}</td>
                            <td class="align-middle">{{ date("m/d/y", strtotime($visitor->created_at)) }}</td>
                            <td class="actions align-middle text-center">
                                <button class="btn btn-white p-1 px-2 mx-1 border-success {{ $visitor->time_out != '' ? 'invisible' : '' }}" onclick="timeoutvisitor({{ $visitor->id }})"><i class="fa-solid fa-right-from-bracket text-success me-1"></i></button>
                            </td>
                            <input type="hidden" class="visitor" value="{{ json_encode($visitor) }}">
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" id="refsetup" value="{{ json_encode($refsetup) }}">
</div>
<div class="modal fade" id="addvisitor" tabindex="-1" aria-labelledby="addvisitorLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-md">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="addresidentLabel">Add visitor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="frmvisitor" action="#">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" id="visid" name="visid">
                    <div class="row mb-3">
                        <div class="col-md-6 form-data">
                            <label for="visvisitortype" class="form-label">Visitor Type</label>
                            <select class="form-select py-2 px-3 rounded-3" name="visvisitortype" id="visvisitortype" required>
                                <option value="">Select Visitor Type</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-data">
                            <label for="vispresentedid" class="form-label">Presented ID</label>
                            <select class="form-select py-2 px-3 rounded-3" name="vispresentedid" id="vispresentedid" required>
                                <option value="">Select Presented ID</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-data mb-3">
                        <label for="visname" class="form-label">Visitor Name</label>
                        <input type="text" class="form-control py-2 px-3 rounded-3" name="visname" id="visname" placeholder="Enter Visitor Name">
                    </div>
                    <div class="form-data mb-3">
                        <label for="vispurpose" class="form-label">Purpose</label>
                        <textarea class="form-control py-2 px-3 rounded-3" name="vispurpose" id="vispurpose" placeholder="Enter Purpose"></textarea>
                    </div>
                    <div class="form-data">
                        <label for="visremarks" class="form-label">Remarks / Comments</label>
                        <textarea class="form-control py-2 px-3 rounded-3" name="visremarks" id="visremarks" placeholder="Enter Remarks / Comments"></textarea>
                    </div>
                </div>
                <div class="modal-footer p-4 py-3">
                    <button type="submit" class="btn btn-add mx-2 px-3 py-2 rounded-3"><i class="fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Save Visitor</button>
                    <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="timeoutvisitor" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content rounded-4">
            <form method="post" action="{{ route('visitors.timeout_visitor') }}">
                @csrf
                <div class="modal-body p-4 py-3 mt-3">
                    <input type="hidden" id="visoutid" name="visoutid">
                    <h5 class="text-center">Time Out visitor?</h5>
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
    });

    function loadRefs(){
        const arr = JSON.parse($("#refsetup").val());
        $.each(arr, function(i, val){
            const list = JSON.parse(val.referential.choices);
            $.each(list, function(x, xval){
                $("#vis" + val.for).append("<option value='" + xval + "'>" + xval + "</option>");
            });
        });
    }

    function addvisitor(){
        $("#addvisitorLabel").text("Add Visitor");
        $("#frmvisitor").find("input[type='text'], select, textarea").val("");
        $("#visid").val("");
        $("#frmvisitor").attr("action", "{{ route('visitors.add_visitor') }}");
        $("#addvisitor").modal("show");
    }

    function timeoutvisitor(id){
        $("#visoutid").val(id);
        $("#timeoutvisitor").modal("show");
    }
</script>
@endsection