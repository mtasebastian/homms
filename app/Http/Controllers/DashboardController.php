<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Residents;
use App\Models\Requests;
use App\Models\Visitors;
use App\Models\Complaints;
use App\Models\Financials;
use App\Models\FinancialPayments;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function counts()
    {
        $top = $this->top();
        $billing = $this->billing();
        $complaints = $this->complaints();
        $visitors = $this->visitors();

        return response()->json([
            'top' => $top,
            'billing' => $billing,
            'complaints' => $complaints,
            'visitors' => $visitors
        ]);
    }

    protected function top()
    {
        // Total count of Active Resident (Today)
        $cnt1 = Residents::where('home_status', 1)->count();

        $year = Carbon::now()->year;
        $month = Carbon::now()->month - 1;
        if($month < 1){
            $year = Carbon::now()->year - 1;
            $month = 12;
        }

        // Overdue Payments
        $cnt2 = Financials::where('bill_year', $year)->where('bill_month', $month)->where('balance', '>', 0)
                ->distinct('resident_id')
                ->count('resident_id');

        // Total count of Visitors (Today)
        $cnt3 = Complaints::whereIn('status', ['Pending', 'On Going'])->count();

        // Total count of Request Today
        $cnt4 = Requests::whereIn('request_status', ['Submitted', 'Checked'])->count();

        // Total count of Complaint Today
        $cnt5 = Visitors::where('created_at', Carbon::now()->format("Y-m-d H:i:s"))->count();

        return [
            'cnt1' => $cnt1,
            'cnt2' => $cnt2,
            'cnt3' => $cnt3,
            'cnt4' => $cnt4,
            'cnt5' => $cnt5 
        ];
    }

    protected function billing()
    {
        $latestYear = Financials::max("bill_year");
        $latestMonth = Financials::where('bill_year', $latestYear)->max("bill_month");

        $arr = [[$latestYear, $latestMonth]];
        for($i = 1; $i < 6; $i++){
            $year = $latestYear;
            $month = $latestMonth - $i;
            if($month < 1){
                $year = $latestYear - 1;
                $month = 12;
            }
            array_push($arr, [$year, $month]);
        }

        $billCat = [];
        $totals = [];
        $payments = [];
        $unpaids = [];
        foreach($arr as $bill){
            array_push($billCat, date("M Y", strtotime('+1 month', strtotime($bill[0] . '-' . str_pad($bill[1], 2, '0', STR_PAD_LEFT) . '-01'))));

            $total = Financials::where('bill_year', $bill[0])->where('bill_month', $bill[1])->sum('bill_amount');
            $ids = Financials::where('bill_year', $bill[0])->where('bill_month', $bill[1])->pluck('id');
            $payment = FinancialPayments::whereIn('financial_id', $ids)->sum('payment');

            array_push($totals, (float) $total);
            array_push($payments, (float) $payment);
            array_push($unpaids, $total - $payment);
        }

        return [
            "cat" => $billCat,
            "totals" => $totals,
            "payments" => $payments,
            "unpaids" => $unpaids
        ];
    }

    protected function complaints()
    {
        $types = [];
        $values = [];
        $complaints = Complaints::select('complaint_type', DB::raw('COUNT(complaint_type) as total'))->groupBy('complaint_type')->get();
        foreach($complaints as $complaint){
            array_push($types, $complaint->complaint_type);
            array_push($values, $complaint->total);
        }
        return [
            "types" => $types,
            "values" => $values
        ];
    }

    protected function visitors()
    {
        // $date = Carbon::now()->format("Y-m-d");
        $date = "2025-05-04";
        $visitors = Visitors::select(
                DB::raw("HOUR(time_in) as hour"),
                DB::raw("COUNT(id) as count")
            )
            ->whereDate('time_in', $date)
            ->groupBy(DB::raw("HOUR(time_in)"))
            ->orderBy('hour')
            ->get()
            ->map(function ($item) {
                $hour = (int) $item->hour;
                $item->formal_time = date("g A", mktime($hour));
                return $item;
            });
        
        $hours = [];
        $counts = [];
        foreach($visitors as $visitor){
            array_push($hours, $visitor->formal_time);
            array_push($counts, $visitor->count);
        }
        return [
            "hours" => $hours,
            "counts" => $counts
        ];
    }
}
