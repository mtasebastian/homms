<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportsService;
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportsController extends Controller
{
    protected $reportsService;

    public function __construct(ReportsService $reportsService)
    {
        $this->reportsService = $reportsService;
    }

    public function index(Request $request)
    {
        return view("reports.index");
    }

    public function filter(Request $request)
    {
        $reporttype = $request->reporttype ?? "Financials";
        $fields = $request->all();
        $results = $this->reportsService->filter($fields);

        return view("reports.index", compact(['results', 'reporttype']));
    }

    public function print(Request $request)
    {
        $reporttype = $request->reporttype ?? "Financials";
        $fields = $request->all();
        $results = $this->reportsService->filter($fields);
        $formatted = $this->reportsService->formatter($reporttype, $results);
        return $formatted;
    }

    public function export(Request $request)
    {
        $fields = $request->all();
        return Excel::download(new ReportsExport($fields, $this->reportsService), ($request->reporttype ?? 'Financials') . ' Report.xlsx');
    }
}
