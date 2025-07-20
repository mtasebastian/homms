<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Residents;
use App\Models\Requests;
use App\Models\Visitors;
use App\Models\Complaints;
use App\Models\Financials;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function counts()
    {
        // Total count of Active Resident (Today)
        $cnt1 = Residents::where('home_status', 1)->count();

        // Total count of Active Request (Today)
        $cnt2 = Requests::where('created_at', Carbon::now()->format('Y-m-d H:i:s'))->where('request_status', 'Approved')->count();

        // Total count of Visitors (Today)
        $cnt3 = Visitors::where('created_at', Carbon::now()->format('Y-m-d H:i:s'))->count();

        // Total count of Request Today
        $cnt4 = Requests::where('created_at', Carbon::now()->format("Y-m-d H:i:s"))->count();

        // Total count of Complaint Today
        $cnt5 = Complaints::where('created_at', Carbon::now()->format("Y-m-d H:i:s"))->count();

        return response()->json([
            'cnt1' => $cnt1,
            'cnt2' => $cnt2,
            'cnt3' => $cnt3,
            'cnt4' => $cnt4,
            'cnt5' => $cnt5 
        ]);
    }
}
