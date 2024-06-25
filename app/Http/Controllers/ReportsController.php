<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Financials;
use App\Models\Requests;
use App\Models\Residents;
use App\Models\Complaints;
use App\Models\Visitors;

class ReportsController extends Controller
{
    protected $financialsService;
    protected $requestsService;
    protected $residentsService;
    protected $complaintsService;
    protected $visitorsService;

    public function __construct(Financials $financials, Requests $requests, Residents $residents, Complaints $complaints, Visitors $visitors)
    {
        $this->financialsService = $financials;
        $this->requestsService = $requests;
        $this->residentsService = $residents;
        $this->complaintsService = $complaints;
        $this->visitorsService = $visitors;
    }

    public function index(Request $request)
    {
        return view("reports.index");
    }

    public function filter(Request $request)
    {
        $results;
        $reporttype = $request->reporttype;
        if($request->reporttype){
            switch ($request->reporttype){
                case "Financials":
                    $results = Financials::with("resident")->where("created_at", Financials::max("created_at"))->get();
                break;
                case "Requests":
                    $results = Requests::with(["reqBy", "appBy", "chkBy"])->get();
                break;
                case "Residents":
                    $results = Residents::with(["occupants", "vehicles", "pets"])->get();
                break;
                case "Complaints":
                    $results = Complaints::get();
                break;
                case "Visitors":
                    $results = Visitors::get();
                break;
            }
        }
        return view("reports.index", compact(['results', 'reporttype']));
    }
}
