@extends('layouts.app', ['title' => 'Reports'])
@section('content')
<div class="main">
    @include('layouts.navbar')
    <div class="mbody">
        @include('layouts.navtitle', ['navtitle' => 'Reports'])
        @if($checker->routePermission('reports.index'))
            <div class="mcontent">
                <form method="get" id="frmreportsfilter">
                <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                    <input type="hidden" id="reportfor" name="reportfor" value="page">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <select class="form-select py-2 px-3" id="reporttype" name="reporttype" onchange="clearParams(); filterReport();">
                                <option value="">Select Report Type</option>
                                <option value="Financials" {{ isset($reporttype) && $reporttype == 'Financials' ? 'selected' : '' }}>Financials</option>
                                <option value="Requests" {{ isset($reporttype) && $reporttype == 'Requests' ? 'selected' : '' }}>Requests</option>
                                <option value="Residents" {{ isset($reporttype) && $reporttype == 'Residents' ? 'selected' : '' }}>Residents</option>
                                <option value="Complaints" {{ isset($reporttype) && $reporttype == 'Complaints' ? 'selected' : '' }}>Complaints</option>
                                <option value="Visitors" {{ isset($reporttype) && $reporttype == 'Visitors' ? 'selected' : '' }}>Visitors</option>
                            </select>
                        </div>
                        <div class="col-md-8 mb-3 mb-md-0 text-end">
                            <button
                                type="button"
                                class="btn btn-light py-2 px-4 rounded-3 text-dark border me-2 btn-sm-50 btn-me
                                @if(!$checker->routePermission('reports.print'))
                                disabled
                                @endif
                                "
                                onclick="printReport()"
                            >
                                <i class="fa-solid fa-print"></i>&nbsp;&nbsp;
                                Print Report
                            </button>
                            <button
                                type="button"
                                class="btn btn-info py-2 px-4 rounded-3 btn-sm-50
                                @if(!$checker->routePermission('reports.export'))
                                disabled
                                @endif
                                "
                                onclick="exportReport()"
                            >
                                <i class="fa-solid fa-file-excel"></i>&nbsp;&nbsp;
                                Export
                            </button>
                        </div>
                    </div>
                </div>
                @if(isset($reporttype))
                <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="input-group inputg">
                                <input type="text" class="form-control py-2 px-3" id="searchkey" name="searchkey" placeholder="Type keyword here..." value="{{ isset($_REQUEST['searchkey']) ? $_REQUEST['searchkey'] : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-0 rounded-end bg-white">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3 mb-md-0">
                            @if($reporttype != "Financials")
                            <div class="input-group inputg">
                                <input type="text" class="form-control datepicker py-2 px-3" id="datefrom" name="datefrom" placeholder="Select Date Start" value="{{ isset($_REQUEST['datefrom']) ? $_REQUEST['datefrom'] : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-0 rounded-end bg-white">
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </span>
                                </div>
                            </div>
                            @else
                            <div class="input-group inputg">
                                <input type="text" class="form-select py-2 px-3" id="billyear" name="billyear" placeholder="Select Year" value="{{ isset($_REQUEST['billyear']) ? $_REQUEST['billyear'] : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-0 rounded-end bg-white">
                                        <i class="fa-solid fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-2 mb-3 mb-md-0">
                            @if($reporttype != "Financials")
                            <div class="input-group inputg">
                                <input type="text" class="form-control datepicker py-2 px-3" id="dateto" name="dateto" placeholder="Select Date End" value="{{ isset($_REQUEST['dateto']) ? $_REQUEST['dateto'] : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-0 rounded-end bg-white">
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </span>
                                </div>
                            </div>
                            @else
                            <div class="input-group inputg">
                                <input type="text" class="form-select py-2 px-3" id="billmonth" name="billmonth" placeholder="Select Month" value="{{ isset($_REQUEST['billmonth']) ? $_REQUEST['billmonth'] : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-0 rounded-end bg-white">
                                        <i class="fa-solid fa-calendar-week"></i>
                                    </span>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <button type="button" onclick="filterReport()" class="btn btn-secondary py-2 px-4 rounded-3 btn-sm-100">Submit Search</button>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                    <div class="table-responsive">
                        @if($reporttype == "Financials")
                            @include("reports.types.report1")
                        @elseif($reporttype == "Requests")
                            @include("reports.types.report2")
                        @elseif($reporttype == "Residents")
                            @include("reports.types.report3")
                        @elseif($reporttype == "Complaints")
                            @include("reports.types.report4")
                        @elseif($reporttype == "Visitors")
                            @include("reports.types.report5")
                        @endif
                    </div>
                    <div id="printArea" class="d-none"></div>
                </div>
                @endif
            </div>
        @else
            <div class="mcontent">
                <div class="no-access">You don't have access to this feature!</div>
            </div>
        @endif
    </div>
</div>
<script>
    $(function(){
        $("#billyear").yearlist(5, 10);
        $("#billmonth").monthlist();
    });

    function clearParams(){
        $("#searchkey").val("");
        $("#billyear").val("");
        $("#billmonth").val("");
        $("#datefrom").val("");
        $("#dateto").val("");
    }

    function filterReport(){
        $("#reportfor").val("page");
        $("#frmreportsfilter").attr("action", "{{ route('reports.filter') }}");
        $("#frmreportsfilter").submit()
    }

    function exportReport(){
        $("#reportfor").val("export");
        $("#frmreportsfilter").attr("action", "{{ route('reports.export') }}");
        $("#frmreportsfilter").submit()
    }

    function printReport(){
        const reporttype = $("#reporttype").val();
        if(reporttype){
            let params = "?reportfor=print&reporttype=" + reporttype + '&searchkey=' + $("#searchkey").val();
            if(reporttype == "Financials"){
                params += "&billyear=" + $("#billyear").val() + "&billmonth=" + $("#billmonth").val();
            }
            else{
                params += "&datefrom=" + $("#datefrom").val() + "&dateto=" + $("#dateto").val();
            }
            $.get("{{ route('reports.print') }}" + params, function(data, status){       
                if(status.includes("success")){
                    $("#printArea").html(generatePrint(reporttype, data));

                    let w = window.open();
                    let html = "<html><head><title>Print Report</title><style>@media print { @page { size: landscape; margin: 10px; }  body { -webkit-print-color-adjust: exact; color-adjust: exact; width: 100%; } header, footer, .print-hide { display: none; } } *{ font-family: Arial; font-size: 13px; } table thead tr th{ background: #333; color: #ffffff; padding: 5px; border: solid 1px #333333; border-left: none; } table thead tr th:first-child{ border-left: solid 1px #333333; } table tbody tr td{ padding: 5px; border: solid 1px #dddddd; border-top: none; border-left: none; } table tbody tr td:first-child{ border-left: solid 1px #dddddd; }</style></head><body><div><h4 style='font-size: 20px; text-align: center;'>{{ isset($reporttype) ? $reporttype : '' }} Report</h4></div><div>" + $("#printArea").html() + "</div></body></html>";
                    w.document.write(html);
                    setTimeout(() => {
                        w.print();
                        w.close();
                    }, 500);
                }
            });
        }
    }

    function generatePrint(reporttype, data){
        let html = '<table style="width: 100%;" cellspacing="0" border="1">';
        switch(reporttype){
            case 'Financials':
                html += '<thead>' +
                        '<tr>' +
                            '<th style="text-align: left; font-size: 14px;">ID</th>' +
                            '<th style="text-align: left;">Resident</th>' +
                            '<th style="text-align: left;">Year</th>' +
                            '<th style="text-align: left;">Month</th>' +
                            '<th style="text-align: right;">Amount</th>' +
                            '<th style="text-align: right;">Payment</th>' +
                            '<th style="text-align: right;">Balance</th>' +
                            '<th style="text-align: left;">Created At</th>' +
                        '</tr>' +
                    '</thead>';
            break;
            case 'Requests':
                html += '<thead>' +
                    '<tr>' +
                        '<th style="text-align: left">ID</th>' +
                        '<th style="text-align: left">Request Type</th>' +
                        '<th style="text-align: left">Requested By</th>' +
                        '<th style="text-align: left">Type</th>' +
                        '<th style="text-align: left">Validity</th>' +
                        '<th style="text-align: left">Approved By</th>' +
                        '<th style="text-align: left">Checked By</th>' +
                        '<th style="text-align: center">Status</th>' +
                        '<th style="text-align: left">Created At</th>' +
                    '</tr>' +
                '</thead>';
            break;
            case 'Residents':
                html += '<thead>' +
                    '<tr>' +
                        '<th style="text-align: left;">ID</th>' +
                        '<th style="text-align: left;">Full Name</th>' +
                        '<th style="text-align: left;">Address</th>' +
                        '<th style="text-align: left;">Email Address</th>' +
                        '<th style="text-align: left;">Mobile Number</th>' +
                        '<th style="text-align: center;">Status</th>' +
                        '<th style="text-align: left;">Created At</th>' +
                    '</tr>' +
                '</thead>';
            break;
            case 'Complaints':
                html += '<thead>' +
                    '<tr>' +
                        '<th style="text-align: left;">ID</th>' +
                        '<th style="text-align: left;">Complaint Type</th>' +
                        '<th style="text-align: left;">Complainant</th>' +
                        '<th style="text-align: left;">Defendant</th>' +
                        '<th style="text-align: left;">Details</th>' +
                        '<th style="text-align: left;">Reported To</th>' +
                        '<th style="text-align: center;">Status</th>' +
                        '<th style="text-align: left;">Created At</th>' +
                    '</tr>' +
                '</thead>';
            break;
            case 'Visitors':
                html += '<thead>' +
                    '<tr>' +
                        '<th style="text-align: left;">ID</th>' +
                        '<th style="text-align: left;">Visitor Type</th>' +
                        '<th style="text-align: left;">Visitor Name</th>' +
                        '<th style="text-align: left;">Purpose</th>' +
                        '<th style="text-align: left;">Time In</th>' +
                        '<th style="text-align: left;">Time Out</th>' +
                        '<th style="text-align: left;">Created At</th>' +
                    '</tr>' +
                '</thead>';
            break;
        }

        html += '<tbody>';
        Object.values(data).forEach(item => {
            let cont = "";
            Object.values(item).forEach(value => {
                cont += '<td style="text-align: ' + value.align + '">' + value.value + '</td>';
            });
            html += '<tr>' + cont + '</tr>';
        });
        html += '</tbody></table>';
        return html;
    }
</script>
@endsection