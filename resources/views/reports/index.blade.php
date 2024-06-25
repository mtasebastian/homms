@extends('layouts.app', ['title' => 'Reports'])
@section('content')
<div class="main">
    @include('layouts.navbar')
    <div class="mbody">
        @include('layouts.navtitle', ['navtitle' => 'Reports'])
        <div class="mcontent">
            <form method="get" action="{{ route('reports.filter') }}" id="frmreportsfilter">
            <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <select class="form-select py-2 px-3" name="reporttype" onchange="$('#frmreportsfilter').submit()">
                            <option value="">Select Report Type</option>
                            <option value="Financials" {{ isset($reporttype) && $reporttype == 'Financials' ? 'selected' : '' }}>Financials</option>
                            <option value="Requests" {{ isset($reporttype) && $reporttype == 'Requests' ? 'selected' : '' }}>Requests</option>
                            <option value="Residents" {{ isset($reporttype) && $reporttype == 'Residents' ? 'selected' : '' }}>Residents</option>
                            <option value="Complaints" {{ isset($reporttype) && $reporttype == 'Complaints' ? 'selected' : '' }}>Complaints</option>
                            <option value="Visitors" {{ isset($reporttype) && $reporttype == 'Visitors' ? 'selected' : '' }}>Visitors</option>
                        </select>
                    </div>
                    <div class="col-md-8 mb-3 mb-md-0 text-end">
                        <button type="button" class="btn btn-light py-2 px-4 rounded-3 text-dark border me-2 btn-sm-50 btn-me" onclick="printreport()"><i class="fa-solid fa-print"></i>&nbsp;&nbsp;Print Report</button>
                        <button type="button" class="btn btn-info py-2 px-4 rounded-3 btn-sm-50" onclick="exportreport()"><i class="fa-solid fa-file-excel"></i>&nbsp;&nbsp;Export</button>
                    </div>
                </div>
            </div>
            @if(isset($reporttype))
            <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
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
                    <div class="col-md-2">
                        <button class="btn btn-secondary py-2 px-4 rounded-3 btn-sm-100">Submit Search</button>
                    </div>
                </div>
                </form>
            </div>
            <div class="card m-3 mx-md-5 p-3 shadow border-light rounded-4">
                <div class="table-responsive" id="printArea">
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
            </div>
            @endif
        </div>
    </div>
</div>
<script>
    function printreport(){
        let w = window.open();
        let html = "<html><head><title>Print Report</title><link rel='stylesheet' type='text/css' href='{{ asset('css') }}/bootstrap_5.2.3.min.css'><link href='{{ asset('css') }}/style.css' rel='stylesheet'><style>@media print { @page { size: landscape; margin: 10px; }  body { -webkit-print-color-adjust: exact; color-adjust: exact; width: 100%; } header, footer, .print-hide { display: none; } } *{ font-size: 11px; }</style></head><body><div class='card m-1 p-3 bg-dark rounded-0 text-center'><h4 class='mb-0 text-white'>{{ isset($reporttype) ? $reporttype : '' }} Report</h4></div><div class='card m-1 p-3 border-light rounded-0'>" + $("#printArea").html() + "</div></body></html>";
        w.document.write(html);
        w.print();
        w.close();
    }
</script>
@endsection