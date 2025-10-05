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
        @if($checker->routePermission('financials.index'))
            <div class="mcontent">
                <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                    <form method="get" id="frmSearch" action="{{ route('financials.index') }}">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="input-group inputg">
                                <input type="text" class="form-control py-2 px-3" name="searchkey" id="searchkey" placeholder="Type keyword here..." value="{{ isset($searchkey) ? $searchkey : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-0 rounded-end bg-white">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3 mb-md-0">
                            <div class="input-group inputg">
                                <input type="text" class="form-select py-2 px-3" name="billyear" id="billyear" placeholder="Select Year" value="{{ isset($year) ? $year : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-0 rounded-end bg-white">
                                        <i class="fa-solid fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3 mb-md-0">
                            <div class="input-group inputg">
                                <input type="text" class="form-select py-2 px-3" name="billmonth" id="billmonth" placeholder="Select Month" value="{{ isset($month) ? $month : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-0 rounded-end bg-white">
                                        <i class="fa-solid fa-calendar-week"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 mb-3 mb-md-0"><button onclick="clearSearch()" class="btn btn-info py-2 px-4 rounded-3 me-2 btn-sm-100"><i class="fa-solid fa-rotate-right"></i></button></div>
                        <div class="col-md-3 text-end">
                            <button class="btn btn-secondary py-2 px-4 rounded-3 me-2 btn-sm-100">Submit Search</button>
                            <button
                                type="button"
                                class="btn btn-add py-2 px-4 rounded-3 btn-sm-100
                                @if(!$checker->routePermission('financials.generate_bills'))
                                disabled
                                @endif
                                "
                                onclick="addfinancial()"
                            >
                                <i class="fa-solid fa-plus"></i>
                                &nbsp;&nbsp;Add Bill
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
                                    <th scope="col" class="align-top">Resident</th>
                                    <th scope="col" class="align-top">Year</th>
                                    <th scope="col" class="align-top">Month</th>
                                    <th scope="col" class="align-top">Amount</th>
                                    <th scope="col" class="align-top">Payment</th>
                                    <th scope="col" class="align-top">Balance</th>
                                    <th scope="col" class="align-top tbl-d-none">Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($financials as $financial)
                                <tr id="fin_{{ $financial->id }}" onclick="optfin({{ $financial->id }})">
                                    <td class="tbl-d-none">{{ $financial->id }}</td>
                                    <td>{{ $financial->resident->fullname }}</td>
                                    <td>{{ $financial->bill_year }}</td>
                                    <td>{{ $financial->monthname }}</td>
                                    <td>{{ number_format($financial->bill_amount, 2, '.', ',') }}</td>
                                    <td>{{ number_format($financial->payments()->sum('payment'), 2, '.', ',') }}</td>
                                    <td>{{ number_format($financial->balances()->sum('balance'), 2, '.', ',') }}</td>
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
        @else
            <div class="mcontent">
                <div class="no-access">You don't have access to this feature!</div>
            </div>
        @endif
    </div>
    <input type="hidden" id="refsetup" value="{{ json_encode($refsetup) }}">
</div>
<div class="modal fade" id="addfinancial" tabindex="-1" aria-labelledby="addfinancialLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
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
                                            <tr style="width: 100%; display: grid; grid-template-columns: repeat(10, 1fr); gap: 0.25rem;">
                                                <th scope="col"></th>
                                                <th scope="col" class="align-top" style="grid-column: span 2">Resident</th>
                                                <th scope="col" class="align-top tbl-d-none" style="grid-column: span 3">Address</th>
                                                <th scope="col" class="align-top text-end" style="grid-column: span 2">Balance</th>
                                                <th scope="col" class="align-top text-center" style="grid-column: span 2">As Of</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tblchkresidents" style="display: block; max-height: 200px; overflow: auto;">
                                        </tbody>
                                        <tbody id="tblsearchresidents" style="display: block; max-height: 200px; overflow: auto;">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card p-3 shadow border-light rounded-4 mb-3 mb-md-0">
                                <div class="form-data">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control py-2 px-3 rounded-3" name="finremarks" placeholder="Input your remarks here"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="chkreslist" id="chkreslist">
                        </div>
                        <div class="col-md-4">
                            <div class="card p-3 shadow border-light rounded-4">
                                <h4 class="msubtitle my-3">Apply Bills</h4>
                                <div class="form-data mb-3">
                                    <label class="form-label req">Bill Period</label>
                                    <div class="d-flex" style="gap: 0.75rem;">
                                        <div>
                                            <span>Year</span>
                                            <div class="input-group inputg">
                                                <input type="text" class="form-select py-2 px-3" name="finbillyear" id="addbillyear" placeholder="Select Year">
                                                <div class="input-group-append">
                                                    <span class="input-group-text rounded-0 rounded-end bg-white">
                                                        <i class="fa-solid fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <span>Month</span>
                                            <div class="input-group inputg">
                                                <input type="text" class="form-select py-2 px-3" name="finbillmonth" id="addbillmonth" placeholder="Select Month">
                                                <div class="input-group-append">
                                                    <span class="input-group-text rounded-0 rounded-end bg-white">
                                                        <i class="fa-solid fa-calendar-week"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-data mb-3">
                                    <label class="form-label req">Due Date</label>
                                    <div class="input-group inputg">
                                        <input type="text" class="form-control datepicker py-2 px-3" name="finduedate" placeholder="Select Due Date" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text rounded-0 rounded-end bg-white">
                                                <i class="fa-solid fa-calendar-days"></i>
                                            </span>
                                        </div>
                                    </div>
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
                            <button type="submit" name="btngeneratebills" id="btngeneratebillsall" value="all" class="btn btn-success py-2 rounded-3 btn-sm-100" disabled><i class="fa-solid fa-globe"></i>&nbsp;&nbsp;Generate Bill to All Residents</button>
                        </div>
                        <div class="col-md-6 p-0 text-end">
                            <button type="submit" name="btngeneratebills" id="btngeneratebills" value="selected" class="btn btn-add px-3 py-2 rounded-3 me-2 btn-sm-50 btn-me" disabled><i class="fa-solid fa-receipt"></i>&nbsp;&nbsp;Generate Bill</button>
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
                <button type="button" class="btn-close" onclick="closeaddpayment()"></button>
            </div>
            <form method="post" id="frmpayment" action="{{ route('financials.add_payment') }}">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="payfinid" id="payfinid">
                    <div class="row mb-3 text-white">
                        <div class="col-4 form-data text-center">
                            <div class="bg-secondary p-2 rounded">
                                <label class="form-label mb-0">Bill Period</label>
                                <label class="fw-bold d-block fs-5" id="payfinbillperiod"></label>
                            </div>
                        </div>
                        <div class="col-4 form-data text-center">
                            <div class="bg-success p-2 rounded">
                                <label class="form-label mb-0">Bill Amount</label>
                                <label class="fw-bold d-block fs-5" id="payfinbillamt"></label>
                            </div>
                        </div>
                        <div class="col-4 form-data text-center">
                            <div class="bg-primary p-2 rounded">
                                <label class="form-label mb-0">Remaining Balance</label>
                                <label class="fw-bold d-block fs-5" id="payfinbillbal"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-data">
                            <label class="form-label req">Reference Number</label>
                            <input type="text" class="form-control py-2 px-3 rounded-3" name="payrefno" id="payrefno" placeholder="Input reference number" required>
                        </div>
                        <div class="col-md-6 form-data">
                            <label class="form-label req">Mode of Payment</label>
                            <select class="form-select py-2 px-3 rounded-3" name="paymod" id="paymod" required>
                                <option value="">Select Mode of Payment</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-data">
                            <label class="form-label req">Payment Amount</label>
                            <input type="number" class="form-control py-2 px-3 rounded-3" name="payamt" id="payamt" step="0.01" placeholder="Input payment amount" required>
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
                    <button type="button" class="btn btn-light border py-2 px-3 rounded-3" onclick="closeaddpayment()">Close</button>
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
                <button type="button" class="btn-close" onclick="closepaymentlist()"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col"></th>
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
                <button type="button" class="btn btn-light border py-2 px-3 rounded-3" onclick="closepaymentlist()">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="optfin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog xs-modal">
        <div class="modal-content rounded-4">
            <div class="modal-header p-4 py-3">
                <h5 class="modal-title" id="optfinLabel">Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 py-3 m-3 text-center">
                <button
                    class="btn btn-info text-white p-2 w-100 fs-6 mb-3
                    @if(!$checker->routePermission('financials.payment_list'))
                    disabled
                    @endif
                    "
                    onclick="paymentlist()"
                >
                    <i class="fa-solid fa-list me-2 fs-5"></i>
                    Payment List
                </button>
                <button
                    class="btn btn-success p-2 w-100 fs-6 mb-3
                    @if(!$checker->routePermission('financials.add_payment'))
                    disabled
                    @endif
                    "
                    id="btnaddpayment"
                    onclick="addpayment()"
                >
                    <i class="fa-solid fa-cash-register me-2 fs-5"></i>
                    Add Payment
                </button>
                <button
                    class="btn btn-secondary p-2 w-100 fs-6
                    @if(!$checker->routePermission('financials.billing_statement'))
                    disabled
                    @endif
                    "
                    id="btnaddpayment"
                    onclick="generateBillingStatement()"
                >
                    <i class="fa-solid fa-file-invoice me-2 fs-5"></i>
                    Billing Statement
                </button>
            </div>
        </div>
    </div>
</div>
<div id="print">
<script>
    let fin_id = "";
    let clickedSubmitBtn = null;
    let maxPay = 0;

    $(function(){
        loadRefs();
        $("#txtresidentsearch").keydown(function(e){
            if(e.keyCode == 13){
                e.preventDefault();
                searchresidents();
            }
        });
        $("#billyear").yearlist(5, 10);
        $("#addbillyear").yearlist(5, 10, "enableSubmitFin");
        $("#billmonth").monthlist();
        $("#addbillmonth").monthlist("enableSubmitFin");
        $("#payamt").allowDecimalOnly();
        $("#payamt").change(function(){
            if(parseFloat($(this).val()) > maxPay){
                $(this).val("")
                alert("Inputted amount must be smaller or equals the remaining balance for this Billing.");
            }
        });

        $('#frmfinancial button[type=submit]').on('click', function(){
            clickedSubmitBtn = $(this);
        });

        $("#frmfinancial").on("submit", function(e){
            e.preventDefault();
            $("#btngeneratebillsall").prop("disabled", true);
            $("#btngeneratebills").prop("disabled", true);
            const confirmed = confirm("Are you sure do you want to proceed?");
            if(confirmed){
                if(clickedSubmitBtn.attr("id") == "btngeneratebills" && $("#tblchkresidents tr").length == 0){
                    alert("Please select residents to bill.");
                }
                else{
                    const hiddenInput = $("<input>")
                        .attr("type", "hidden")
                        .attr("name", clickedSubmitBtn.attr("name"))
                        .val(clickedSubmitBtn.val());
                    $(this).append(hiddenInput);
                    $(this).off("submit").submit();
                }
            }
        });
    });

    function clearSearch(){
        $("#searchkey").val("");
        $("#billyear").val("");
        $("#billmonth").val("");
        $("#frmSearch").submit();
    }
    
    function enableSubmitFin(){
        if($("#addbillyear").val() == "" || $("#addbillmonth").val() == ""){
            $("#btngeneratebillsall").attr("disabled", true);
            $("#btngeneratebills").attr("disabled", true);
        }
        else{
            $("#btngeneratebillsall").attr("disabled", false);
            $("#btngeneratebills").attr("disabled", false);
        }
    }

    function optfin(id){
        fin_id = id;
        const obj = $("#fin_" + id);
        const fin = JSON.parse(obj.find(".financial").val());
        if(parseFloat(fin.balance) <= 0){
            $("#btnaddpayment").hide();
        }
        else{
            $("#btnaddpayment").show();
        }
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
            if(status.includes("success")){
                if(data.length == 0){
                    $("#tblsearchresidents").html("<tr style='width: 100%; display: grid; grid-template-columns: repeat(10, 1fr); gap: 0.25rem;'><td class='text-center p-3' style='grid-column: span 10'>Resident not found</td></tr>");
                }
                else{
                    $("#tblsearchresidents").html("");
                }
                $.each(data, function(i, item){
                    if($("#tblchkresidents #res_" + item.id).length == 0){
                        $("#tblsearchresidents").append("<tr id='res_" + item.id + "' style='width: 100%; display: grid; grid-template-columns: repeat(10, 1fr); gap: 0.25rem;'>" +
                            "<td><input class='form-check-input' type='checkbox' value='1' onchange='addresident(" + item.id + ")'></td>" +
                            "<td style='grid-column: span 2'>" + item.last_name + ", " + item.first_name + " " + item.middle_name.charAt(0) + "</td>" +
                            "<td class='tbl-d-none' style='grid-column: span 3'>" + item.fulladdress + "</td>" +
                            "<td class='text-end' style='grid-column: span 2'>" + (item.balance != null ? parseFloat(item.balance.balance).toFixed(2) : "0.00") + "</td>" +
                            "<td class='text-center' style='grid-column: span 2'>" + (item.balance != null ? formatDate(item.balance.formattedcat) : formatDate()) + "</td>" +
                        "</tr>");
                    }
                });
            }
        });
    }

    function addresident(id){
        const obj = $("#tblsearchresidents #res_" + id);
        $("#tblchkresidents").append("<tr id='res_" + id + "'' style='width: 100%; display: grid; grid-template-columns: repeat(10, 1fr); gap: 0.25rem;'>" +
            "<td><input class='form-check-input' type='checkbox' value='1' onchange='removeresident(" + id + ")' checked></td>" +
            "<td style='grid-column: span 2'>" + obj.find("td").eq(1).text() + "</td>" +
            "<td class='tbl-d-none' style='grid-column: span 3'>" + obj.find("td").eq(2).text() + "</td>" +
            "<td class='text-end' style='grid-column: span 2'>" + obj.find("td").eq(3).text() + "</td>" +
            "<td class='text-center' style='grid-column: span 2'>" + obj.find("td").eq(4).text() + "</td>" +
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
        $("#payfinbillperiod").text(obj.find("td").eq(3).text() + ", " + obj.find("td").eq(2).text());
        $("#payfinbillamt").text(obj.find("td").eq(4).text());
        const fin = JSON.parse(obj.find(".financial").val());
        $("#payfinbillbal").text(fin.formattedbal);
        $("#payrefno").val("");
        $("#paymod").val("");
        maxPay = parseFloat(fin.balance)
        $("#payamt").val("");
        $("#paydisctype").val("");
        $("#paydiscamt").val("");
        $("#payremarks").val("");
        $("#addpayment").modal("show");
    }

    function closeaddpayment(){
        $("#addpayment").modal("hide");
        $("#optfin").modal("show");
    }

    function paymentlist(){
        $("#optfin").modal("hide");
        id = fin_id;
        $.get("{{ route('financials.payment_list') }}?id=" + id, function(data, status){       
            if(status.includes("success")){
                if(data.length == 0){
                    $("#tblpaylist").html("<td colspan='6' class='text-center p-3'>No payments has been posted for this bill.</td>");
                }
                else{
                    $("#tblpaylist").html("");
                }
                $.each(data, function(i, item){
                    $("#tblpaylist").append("<tr>" +
                        "<td>" +
                            "<button " +
                               "class='btn btn-info text-white " +
                                @if(!$checker->routePermission('financials.receipt'))
                                "disabled" +
                                @endif
                               "'" +
                               "onclick='printReceipt(\"" + item.id + "\")'" +
                            ">" +
                                "<i class='fa-solid fa-print'></i>" +
                            "</button>" +
                        "</td>" +
                        "<td>" + item.reference_number + "</td>" +
                        "<td class='text-end'>" + parseFloat(item.payment).toFixed(2) + "</td>" +
                        "<td>" + (item.discount_type ? item.discount_type : 'none') + "</td>" +
                        "<td class='text-end'>" + (item.discount_amount ? parseFloat(item.discount_amount).toFixed(2) : 'none') + "</td>" +
                        "<td>" + item.formatteddate + "</td>" +
                    "</tr>");
                });
            }
        });
        $("#paymentlist").modal("show");
    }

    function closepaymentlist(){
        $("#paymentlist").modal("hide");
        $("#optfin").modal("show");
        
    }

    function generateBillingStatement(){
        $.get("{{ route('financials.billing_statement') }}?id=" + fin_id, function(data, status){       
            if(status.includes("success")){
                let w = window.open();
                let bills = '';
                Object.entries(data.financials.bills).forEach(([i, item]) => {
                    bills += '<tr>' +
                        '<td style="padding: 10px;' + (i == Object.entries(data.financials.bills).length-1 ? '' : ' border-bottom: solid 1px #dddddd;') + '">' + (i + 1) + '</td>' +
                        '<td colspan="2" style="padding: 10px;' + (i == Object.entries(data.financials.bills).length-1 ? '' : ' border-bottom: solid 1px #dddddd;') + '">' + item.billname + '</td>' +
                        '<td style="padding: 10px;' + (i == Object.entries(data.financials.bills).length-1 ? '' : ' border-bottom: solid 1px #dddddd;') + ' text-align: right;">' + item.formattedamt + '</td>' +
                    '</tr>';
                });       
                let html = '<html>' +
                    '<head>' +
                        '<title>Billing Statement</title>' +
                        '<style>@media print { @page { size: portrait; margin: 10px; }  body { -webkit-print-color-adjust: exact; color-adjust: exact; width: 100%; } header, footer, .print-hide { display: none; } } *{ font-family: Arial; font-size: 13px; }</style>' +
                    '</head>' +
                    '<body>' +
                        '<div style="margin: 100px 5%;">' +
                        '<table width="100%" cellspacing="0">' +
                            '<tr>' +
                                '<td colspan="3" style="font-size: 20px;">' + data.systemname + '</td>' +
                                '<td rowspan="5" style="text-align: right;"><img src="' + 'data:' + data.systemlogo.mime + ';base64,' + data.systemlogo.content + '" style="width: 120px; height: auto;"><td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="2">NON-VAT Reg.</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="2">TIN: ' + data.systemtin + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="2">CONTACT #: ' + data.systemcontact + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="2">' + data.systemaddress + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="4">&nbsp;</td>' +
                            '</tr>' +
                            '<tr style="background: blue; color: #ffffff;">' +
                                '<td colspan="2" style="padding: 10px;">Billing Statement: ' + data.financials.formattedid + '</td>' +
                                '<td></td>' +
                                '<td style="padding: 10px; text-align: right;">Billing Period: ' + (data.financials.bill_month < 10 ? '0' : '') + data.financials.bill_month + '/' + data.financials.bill_year + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="4">&nbsp;</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td style="padding: 10px; border-bottom: solid 1px blue;">BILL TO</td>' +
                                '<td style="padding: 10px; border-bottom: solid 1px blue;">ACCOUNT #</td>' +
                                '<td colspan="2" style="padding: 10px; border-bottom: solid 1px blue;">ADDRESS</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td style="padding: 10px;">' + data.financials.resident.fullname + '</td>' +
                                '<td style="padding: 10px;">' + data.financials.resident.formattedid + '</td>' +
                                '<td colspan="2" style="padding: 10px;">' + data.financials.resident.fulladdress + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="4">&nbsp;</td>' +
                            '</tr>' +
                            '<tr style="background: blue; color: #ffffff;">' +
                                '<td style="padding: 10px;">NO.</td>' +
                                '<td colspan="2" style="padding: 10px;">DESCRIPTION</td>' +
                                '<td style="padding: 10px; text-align: right;">TOTAL</td>' +
                            '</tr>' +
                            bills +
                            '<tr>' +
                                '<td colspan="4" style="border-bottom: solid 1px blue;">&nbsp;</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="4">&nbsp;</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="2" style="padding: 10px;"></td>' +
                                '<td style="padding: 10px; color: blue;">SUBTOTAL</td>' +
                                '<td style="padding: 10px; color: blue; text-align: right;">' + data.financials.formattedamt + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="2" style="padding: 10px;"></td>' +
                                '<td style="padding: 10px; color: blue;">TAX</td>' +
                                '<td style="padding: 10px; color: blue; text-align: right;">0.00</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="2" style="padding: 10px;"></td>' +
                                '<td style="padding: 10px; color: blue; font-weight: 800;">TOTAL(DUE DATE BY 08/30/2025)</td>' +
                                '<td style="padding: 10px; color: blue; font-weight: 800; text-align: right;">' + data.financials.formattedamt + '</td>' +
                            '</tr>' +
                        '</table>' +
                        '</div>' +
                    '</body>' +
                '</html>';
                w.document.write(html);
                setTimeout(() => {
                    w.print();
                    w.close();
                }, 500);
            }
        });
    }

    function printReceipt(id){
        $.get("{{ route('financials.receipt') }}?id=" + id, function(data, status){       
            if(status.includes("success")){
                let w = window.open();
                let html = '<html>' +
                    '<head>' +
                        '<title>Billing Statement</title>' +
                        '<style>@media print { @page { size: portrait; margin: 10px; }  body { -webkit-print-color-adjust: exact; color-adjust: exact; width: 100%; } header, footer, .print-hide { display: none; } } *{ font-family: Arial; font-size: 13px; } table td{ vertical-align: top; }</style>' +
                    '</head>' +
                    '<body>' +
                        '<div style="margin: 100px 5%;">' +
                        '<table width="100%" cellspacing="0" cellpadding="5">' +
                            '<tr>' +
                                '<td colspan="4" style="font-size: 20px;">' + data.systemname + '</td>' +
                                '<td colspan="2" rowspan="5" style="text-align: right;"><img src="' + 'data:' + data.systemlogo.mime + ';base64,' + data.systemlogo.content + '" style="width: 120px; height: auto;"><td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="4">NON-VAT Reg.</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="4">TIN: ' + data.systemtin + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="4">CONTACT #: ' + data.systemcontact + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="4">' + data.systemaddress + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="6">&nbsp;</td>' +
                            '</tr>' +
                            '<tr style="background: gray; color: #ffffff;">' +
                                '<td colspan="3" style="padding: 10px;">ACKNOWLEDGEMENT RECEIPT: ' + data.financial_payments.formattedid + '</td>' +
                                '<td colspan="3" style="padding: 10px; text-align: right;">DATE: ' + data.financial_payments.formatteddate + '</td>' +
                            '</tr>' +
                            '<tbody id="details">' +
                            '<tr>' +
                                '<td style="width: 20%; border: solid 1px gray; border-top: none;">Reference Number</td>' +
                                '<td colspan="2" style="width: 40%; border: solid 1px gray; border-left: none; border-top: none;">' + data.financial_payments.reference_number + '</td>' +
                                '<td colspan="2" style="width: 20%;  border: solid 1px gray; border-left: none; border-top: none;">Payment Amount</td>' +
                                '<td style="width: 20%; text-align: right; border: solid 1px gray; border-left: none; border-top: none;">' + data.financial_payments.formattedamount + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td style="border: solid 1px gray; border-top: none;">Account #</td>' +
                                '<td colspan="2" style="border: solid 1px gray; border-left: none; border-top: none;">' + data.financials.resident.formattedid + '</td>' +
                                '<td colspan="2" style="border: solid 1px gray; border-left: none; border-top: none;">Mode of Payment</td>' +
                                '<td style="text-align: right; border: solid 1px gray; border-left: none; border-top: none;">' + data.financial_payments.mode_of_payment + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td style="border: solid 1px gray; border-top: none;">Resident Name</td>' +
                                '<td colspan="2" style="border: solid 1px gray; border-left: none; border-top: none;">' + data.financials.resident.fullname + '</td>' +
                                '<td colspan="2" style="border: solid 1px gray; border-left: none; border-top: none;">Discount</td>' +
                                '<td style="text-align: right; border: solid 1px gray; border-left: none; border-top: none;">' + data.financial_payments.formatteddiscount + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td style="border: solid 1px gray; border-top: none;">Address</td>' +
                                '<td colspan="2" style="border: solid 1px gray; border-left: none; border-top: none;">' + data.financials.resident.fulladdress + '</td>' +
                                '<td colspan="2" style="border: solid 1px gray; border-left: none; border-top: none;">Total Amount</td>' +
                                '<td style="text-align: right; border: solid 1px gray; border-left: none; border-top: none;">' + data.financial_payments.formattedtotal + '</td>' +
                            '</tr>' +
                            '</tbody>' +
                            '<tr>' +
                                '<td colspan="6">&nbsp;</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="6" style="border-bottom: dashed 1px #999;">Remarks</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="6">' + data.financial_payments.remarks + '</td>'
                            '</tr>' +
                        '</table>' +
                        '</div>' +
                    '</body>' +
                '</html>';
                w.document.write(html);
                setTimeout(() => {
                    w.print();
                    w.close();
                }, 500);
            }
        });
    }
</script>
@endsection