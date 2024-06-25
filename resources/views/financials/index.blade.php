@extends('layouts.app', ['title' => 'Financials'])
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
        @include('layouts.navtitle', ['navtitle' => 'Financials'])
        <div class="mcontent">
            <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                <form method="get" action="{{ route('financials.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="input-group inputg">
                            <input type="text" class="form-control py-2 px-3" name="searchkey" placeholder="Type keyword here..." value="{{ isset($searchkey) ? $searchkey : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3 mb-md-0">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="datefrom" placeholder="Select Date Start" value="{{ isset($datefrom) ? $datefrom : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3 mb-md-0">
                        <div class="input-group inputg">
                            <input type="text" class="form-control datepicker py-2 px-3" name="dateto" placeholder="Select Date End" value="{{ isset($dateto) ? $dateto : '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-secondary py-2 px-4 rounded-3 me-2 btn-sm-100">Submit Search</button>
                        <button type="button" class="btn btn-add py-2 px-4 rounded-3 btn-sm-100" onclick="addfinancial()"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Add Bill</button>
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
                                <th scope="col" class="align-top">Resident</th>
                                <th scope="col" class="align-top">Bill Period</th>
                                <th scope="col" class="align-top">Bill Amount</th>
                                <th scope="col" class="align-top">Balance</th>
                                <th scope="col" class="align-top tbl-d-none">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($financials as $financial)
                            <tr id="fin_{{ $financial->id }}" onclick="optfin({{ $financial->id }})">
                                <td class="tbl-d-none">{{ $financial->id }}</td>
                                <td>{{ $financial->resident->fullname }}</td>
                                <td>{{ date("m/Y", strtotime($financial->bill_period)) }}</td>
                                <td>{{ number_format($financial->bill_amount, 2, '.', ',') }}</td>
                                <td>{{ number_format($financial->balance, 2, '.', ',') }}</td>
                                <td class="tbl-d-none">{{ date("m/d/y", strtotime($financial->created_at)) }}</td>
                                <input type="hidden" class="financial" value="{{ json_encode($financial) }}">
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex"><div class="mx-auto">{{ $financials->links() }}</div></div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="refsetup" value="{{ json_encode($refsetup) }}">
</div>
<div class="modal fade" id="addfinancial" tabindex="-1" aria-labelledby="addfinancialLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="addfinancialLabel">Add Financial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="frmfinancial" action="{{ route('financials.generate_bills') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card p-3 shadow border-light rounded-4 mb-3">
                                <div class="row">
                                    <div class="col-md-8 mb-3 mb-md-0">
                                        <div class="input-group inputg">
                                            <input type="text" class="form-control py-2 px-3" id="txtresidentsearch" placeholder="Search residents here...">
                                            <div class="input-group-append">
                                                <span class="input-group-text rounded-0 rounded-end bg-white">
                                                    <i class="fa-solid fa-magnifying-glass"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-secondary py-2 px-4 rounded-3 w-100" onclick="searchresidents()">Submit Search</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card p-3 shadow border-light rounded-4 mb-3">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col" class="align-top">Resident</th>
                                                <th scope="col" class="align-top tbl-d-none">Address</th>
                                                <th scope="col" class="align-top text-end">Balance</th>
                                                <th scope="col" class="align-top text-center">As Of</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tblchkresidents">
                                        </tbody>
                                        <tbody id="tblsearchresidents">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card p-3 shadow border-light rounded-4 mb-3 mb-md-0">
                                <div class="form-data">
                                    <label class="form-label">Remarks</label>
                                    <textarea class="form-control py-2 px-3 rounded-3" name="finremarks" placeholder="Input your remarks here"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="chkreslist" id="chkreslist">
                        </div>
                        <div class="col-md-4">
                            <div class="card p-3 shadow border-light rounded-4">
                                <h4 class="msubtitle my-3">Apply Bills</h4>
                                <div class="form-data mb-3">
                                    <label class="form-label">Bill Period</label>
                                    <input type="text" class="form-control py-2 px-3 datepicker rounded-3" name="finperiod" placeholder="__/__/____" required>
                                </div>
                                @foreach($finsetup as $fin)
                                <div class="border-bottom p-3">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" value="{{ $fin->id }}" name="fin[]" checked>
                                        &nbsp;
                                        {{ $fin->bill_name }} <b class="ms-2">{{ number_format($fin->bill_amt, "2", ".", ",") }}</b>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-4 py-3 d-block">
                    <div class="row">
                        <div class="col-md-6 p-0">
                            <button type="submit" name="btngeneratebills" value="all" class="btn btn-success py-2 rounded-3 btn-sm-100"><i class="fa-solid fa-globe"></i>&nbsp;&nbsp;Generate Bill to All Residents</button>
                        </div>
                        <div class="col-md-6 p-0 text-end">
                            <button type="submit" name="btngeneratebills" value="selected" class="btn btn-add px-3 py-2 rounded-3 me-2 btn-sm-50 btn-me"><i class="fa-solid fa-receipt"></i>&nbsp;&nbsp;Generate Bill</button>
                            <button type="button" class="btn btn-light border py-2 px-3 rounded-3 btn-sm-50" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addpayment" tabindex="-1" aria-labelledby="addpaymentLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="addpaymentLabel">Add Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="frmpayment" action="{{ route('financials.add_payment') }}">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="payfinid" id="payfinid">
                    <div class="row mb-3">
                        <div class="col-6 form-data">
                            <label class="form-label">Bill Period</label>
                            <label class="fw-bold d-block fs-5" id="payfinbillperiod"></label>
                        </div>
                        <div class="col-6 form-data text-end">
                            <label class="form-label">Bill Amount</label>
                            <label class="fw-bold d-block fs-5" id="payfinbillamt"></label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-data">
                            <label class="form-label">Reference Number</label>
                            <input type="text" class="form-control py-2 px-3 rounded-3" name="payrefno" id="payrefno" placeholder="Input reference number" required>
                        </div>
                        <div class="col-md-6 form-data">
                            <label class="form-label">Mode of Payment</label>
                            <select class="form-select py-2 px-3 rounded-3" name="paymod" id="paymod" required>
                                <option value="">Select Mode of Payment</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-data">
                            <label class="form-label">Payment Amount</label>
                            <input type="text" class="form-control py-2 px-3 rounded-3" name="payamt" id="payamt" placeholder="Input payment amount" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-data">
                            <label class="form-label">Discount Type</label>
                            <select class="form-select py-2 px-3 rounded-3" name="paydisctype" id="paydisctype">
                                <option value="">Select Mode of Payment</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-data">
                            <label class="form-label">Discount Amount</label>
                            <input type="text" class="form-control py-2 px-3 rounded-3" name="paydiscamt" id="paydiscamt" placeholder="Input discount amount">
                        </div>
                    </div>
                    <div class="form-data">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control py-2 px-3 rounded-3" name="payremarks" id="payremarks"></textarea>
                    </div>
                </div>
                <div class="modal-footer p-4 py-3">
                    <button type="submit" class="btn btn-add mx-2 px-3 py-2 rounded-3"><i class="fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Add Payment</button>
                    <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="paymentlist" tabindex="-1" aria-labelledby="paymentlistLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="paymentlistLabel">Payment List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Reference Number</th>
                                <th scope="col" class="text-end">Payment Amount</th>
                                <th scope="col">Discount Type</th>
                                <th scope="col" class="text-end">Discount Amount</th>
                                <th scope="col">Posted At</th>
                            </tr>
                        </thead>
                        <tbody id="tblpaylist">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer p-4 py-3">
                <button type="button" class="btn btn-light border py-2 px-3 rounded-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="optfin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog xs-modal">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="optfinLabel">Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 py-3 m-3 text-center">
                <button class="btn btn-info text-white p-2 w-100 fs-6 mb-3" onclick="paymentlist()"><i class="fa-solid fa-list me-2 fs-5"></i>Payment List</button>
                <button class="btn btn-success p-2 w-100 fs-6" onclick="addpayment()"><i class="fa-solid fa-cash-register me-2 fs-5"></i>Add Payment</button>
            </div>
        </div>
    </div>
</div>
<script>
    let fin_id = "";

    $(function(){
        loadRefs();
        $("#txtresidentsearch").keydown(function(e){
            if(e.keyCode == 13){
                e.preventDefault();
                searchresidents();
            }
        });
    });

    function optfin(id){
        fin_id = id;
        $("#optfin").modal("show");
    }

    function loadRefs(){
        const arr = JSON.parse($("#refsetup").val());
        $.each(arr, function(i, val){
            const list = JSON.parse(val.referential.choices);
            $.each(list, function(x, xval){
                $("#pay" + val.for).append("<option value='" + xval + "'>" + xval + "</option>");
            });
        });
    }

    function addfinancial(){
        $("#addfinancial").modal("show");
    }

    function searchresidents(){
        let key = $("#txtresidentsearch").val();
        $.get("{{ route('financials.search_resident') }}?key=" + key, function(data, status){       
            if(status == "success"){
                if(data.length == 0){
                    $("#tblsearchresidents").html("<td colspan='5' class='text-center p-3'>Resident not found</td>");
                }
                else{
                    $("#tblsearchresidents").html("");
                }
                $.each(data, function(i, item){
                    if($("#tblchkresidents #res_" + item.id).length == 0){
                        $("#tblsearchresidents").append("<tr id='res_" + item.id + "'>" +
                            "<td><input class='form-check-input' type='checkbox' value='1' onchange='addresident(" + item.id + ")'></td>" +
                            "<td>" + item.last_name + ", " + item.first_name + " " + item.middle_name.charAt(0) + "</td>" +
                            "<td class='tbl-d-none'>" + item.fulladdress + "</td>" +
                            "<td class='text-end'>" + (item.balance != null ? parseFloat(item.balance.balance).toFixed(2) : "0.00") + "</td>" +
                            "<td class='text-center'>" + (item.balance != null ? formatDate(item.balance.formattedcat) : formatDate()) + "</td>" +
                        "</tr>");
                    }
                });
            }
        });
    }

    function addresident(id){
        const obj = $("#tblsearchresidents #res_" + id);
        $("#tblchkresidents").append("<tr id='res_" + id + "'>" +
            "<td><input class='form-check-input' type='checkbox' value='1' onchange='removeresident(" + id + ")' checked></td>" +
            "<td>" + obj.find("td").eq(1).text() + "</td>" +
            "<td class='tbl-d-none'>" + obj.find("td").eq(2).text() + "</td>" +
            "<td class='text-end'>" + obj.find("td").eq(3).text() + "</td>" +
            "<td class='text-center'>" + obj.find("td").eq(4).text() + "</td>" +
        "</tr>");
        obj.remove();
        listchkresidents();
    }

    function removeresident(id){
        const obj = $("#tblchkresidents #res_" + id);
        obj.remove();
        searchresidents();
        listchkresidents();
    }

    function listchkresidents(){
        let arr = [];
        $("#tblchkresidents tr").each(function(){
            const id = $(this).attr("id").split("_");
            arr.push(id[1]);
        });
        $("#chkreslist").val(JSON.stringify(arr));
    }

    function addpayment(){
        $("#optfin").modal("hide");
        id = fin_id;
        const obj = $("#fin_" + id);
        $("#payfinid").val(id);
        $("#payfinbillperiod").text(obj.find("td").eq(2).text());
        $("#payfinbillamt").text(obj.find("td").eq(3).text());
        $("#payrefno").val("");
        $("#paymod").val("");
        $("#payamt").val("");
        $("#paydisctype").val("");
        $("#paydiscamt").val("");
        $("#payremarks").val("");
        $("#addpayment").modal("show");
    }

    function paymentlist(){
        $("#optfin").modal("hide");
        id = fin_id;
        $.get("{{ route('financials.payment_list') }}?id=" + id, function(data, status){       
            if(status == "success"){
                if(data.length == 0){
                    $("#tblpaylist").html("<td colspan='5' class='text-center p-3'>No payments has been posted for this bill.</td>");
                }
                else{
                    $("#tblpaylist").html("");
                }
                $.each(data, function(i, item){
                    $("#tblpaylist").append("<tr>" +
                        "<td>" + item.reference_number + "</td>" +
                        "<td class='text-end'>" + parseFloat(item.payment).toFixed(2) + "</td>" +
                        "<td>" + (item.discount_type ? item.discount_type : 'none') + "</td>" +
                        "<td class='text-end'>" + (item.discount_amount ? parseFloat(item.discount_amount).toFixed(2) : 'none') + "</td>" +
                        "<td>" + item.formatted_date + "</td>" +
                    "</tr>");
                });
            }
        });
        $("#paymentlist").modal("show");
    }
</script>
@endsection