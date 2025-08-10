<?php

namespace App\Services;

use App\Models\Financials;
use App\Models\Requests;
use App\Models\Residents;
use App\Models\Complaints;
use App\Models\Visitors;
use Illuminate\Support\Facades\DB;

class ReportsService
{
    public function filter($fields)
    {
        $results;
        $reporttype = $fields["reporttype"] ?? "Financials";
        $for = $fields["reportfor"] ?? "page";
        if($reporttype){
            switch($reporttype){
                case "Financials":
                    $results = self::financialsReport($fields, $for);
                break;
                case "Requests":
                    $results = self::requestsReport($fields, $for);
                break;
                case "Residents":
                    $results = self::residentsReport($fields, $for);
                break;
                case "Complaints":
                    $results = self::complaintsReport($fields, $for);
                break;
                case "Visitors":
                    $results = self::visitorsReport($fields, $for);
                break;
            }
        }
        return $results;
    }

    private function financialsReport($fields, $for)
    {
        $searchkey = $fields["searchkey"] ?? null;
        $year = $fields["billyear"] ?? null;
        $month = $fields["billmonth"] ?? null;

        $columns = \Schema::getColumnListing('residents');
        $selectColumns = ['financials.*'];
        foreach ($columns as $col) {
            if ($col === 'id') {
                $selectColumns[] = "residents.id as resident_id";
            } else {
                $selectColumns[] = "residents.$col";
            }
        }

        $query = Financials::select($selectColumns)
                ->join('residents', 'financials.resident_id', '=', 'residents.id')
                ->with(["resident"]);
        if($searchkey){
            $query->where(DB::raw("CONCAT(residents.last_name, ' ', residents.first_name, ' ', residents.middle_name)"), "like", "%$searchkey%");
            $params[] = 'searchkey';
        }
        if($year && !$month){
            $query->where("bill_year", $year);
        }
        elseif(!$year && $month){
            $query->where("bill_month", self::getMonthNum($month));
        }
        elseif($year && $month){
            $query->where("bill_year", $year)
                ->where("bill_month", self::getMonthNum($month));
        }
        if($for == "page"){
            $financials = $query->orderBy('bill_year', 'desc')
                ->orderBy('bill_month', 'desc')
                ->paginate(20);
        }
        else{
            $financials = $query->orderBy('bill_year', 'desc')
                ->orderBy('bill_month', 'desc')
                ->get();
        }

        return $financials;
    }

    private function requestsReport($fields, $for)
    {
        $search = $fields["searchkey"] ?? null;
        $datefrom = $fields["datefrom"] ?? null;
        $dateto = $fields["dateto"] ?? null;

        $columns = \Schema::getColumnListing('residents');
        $selectColumns = ['requests.*'];
        foreach ($columns as $col) {
            if ($col === 'id') {
                $selectColumns[] = "residents.id as resident_id";
            } else {
                $selectColumns[] = "residents.$col";
            }
        }

        $query = Requests::select($selectColumns)
                ->join('residents', 'requests.requested_by', '=', 'residents.id')
                ->with(["reqBy", "appBy", "chkBy"]);
        if($search){
            $query->where(function ($q) use ($search) {
                $q->where('request_type', 'like', "%$search%")
                ->orWhere('details', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%")
                ->orWhere(DB::raw("CONCAT(residents.last_name, ' ', residents.first_name, ' ', residents.middle_name)"), 'like', "%$search%");
            });
            $searchkey = $search;
        }
        if($datefrom && !$dateto){
            $query->whereDate('created_at', Carbon::parse($datefrom)->format('Y-m-d'));
        }
        elseif(!$datefrom && $dateto){
            $query->whereDate('created_at', Carbon::parse($dateto)->format('Y-m-d'));
        }
        elseif($datefrom && $dateto){
            $query->whereBetween(DB::raw("DATE(created_at)"), [
                Carbon::parse($datefrom)->format('Y-m-d'),
                Carbon::parse($dateto)->format('Y-m-d')
            ]);
        }
        if($for == "page"){
            $reqs = $query->paginate(20);
        }
        else{
            $reqs = $query->get();
        }

        return $reqs;
    }

    private function residentsReport($fields, $for)
    {
        $search = $fields["searchkey"] ?? null;
        $datefrom = $fields["datefrom"] ?? null;
        $dateto = $fields["dateto"] ?? null;

        $query = Residents::with(['occupants', 'vehicles', 'pets']);
        if($search){
            $query->where(function ($q) use ($search) {
                $q->where('last_name', 'like', "%$search%")
                ->orWhere('first_name', 'like', "%$search%");
            });
            $searchkey = $search;
        }
        if($datefrom && !$dateto){
            $query->whereDate('created_at', Carbon::parse($datefrom)->format('Y-m-d'));
        }
        elseif(!$datefrom && $dateto){
            $query->whereDate('created_at', Carbon::parse($dateto)->format('Y-m-d'));
        }
        elseif($datefrom && $dateto){
            $query->whereBetween(DB::raw("DATE(created_at)"), [
                Carbon::parse($datefrom)->format('Y-m-d'),
                Carbon::parse($dateto)->format('Y-m-d')
            ]);
        }
        if($for == "page"){
            $residents = $query->paginate(20);
        }
        else{
            $residents = $query->get();
        }
        
        return $residents;
    }

    private function complaintsReport($fields, $for)
    {
        $search = $fields["searchkey"] ?? null;
        $datefrom = $fields["datefrom"] ?? null;
        $dateto = $fields["dateto"] ?? null;

        $columns = \Schema::getColumnListing('residents');
        $selectColumns = ['complaints.*'];
        foreach ($columns as $col) {
            if ($col === 'id') {
                $selectColumns[] = "residents.id as resident_id";
            } else {
                $selectColumns[] = "residents.$col";
            }
        }

        $query = Complaints::select($selectColumns)
                ->join('residents', 'complaints.resident_id', '=', 'residents.id');
        if($search){
            $query->where(function ($q) use ($search) {
                $q->where('complaint_type', 'like', "%$search%")
                ->orWhere('purpose', 'like', "%$search%")
                ->orWhere('details', 'like', "%$search%")
                ->orWhere(DB::raw("CONCAT(residents.last_name, ' ', residents.first_name, ' ', residents.middle_name)"), 'like', "%$search%");
            });
            $searchkey = $search;
        }
        if($datefrom && !$dateto){
            $query->whereDate('created_at', Carbon::parse($datefrom)->format('Y-m-d'));
        }
        elseif(!$datefrom && $dateto){
            $query->whereDate('created_at', Carbon::parse($dateto)->format('Y-m-d'));
        }
        elseif($datefrom && $dateto){
            $query->whereBetween(DB::raw("DATE(created_at)"), [
                Carbon::parse($datefrom)->format('Y-m-d'),
                Carbon::parse($dateto)->format('Y-m-d')
            ]);
        }
        if($for == "page"){
            $complaints = $query->paginate(20);
        }
        else{
            $complaints = $query->get();
        }

        return $complaints;
    }

    private function visitorsReport($fields, $for)
    {
        $search = $fields["searchkey"] ?? null;
        $datefrom = $fields["datefrom"] ?? null;
        $dateto = $fields["dateto"] ?? null;

        $query = Visitors::query();
        if($search){
            $query->where(function ($q) use ($search) {
                $q->where('visitor_type', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%")
                ->orWhere('purpose', 'like', "%$search%");
            });
            $searchkey = $search;
        }

        if($datefrom && !$dateto){
            $query->whereDate('created_at', Carbon::parse($datefrom)->format('Y-m-d'));
        }
        elseif(!$datefrom && $dateto){
            $query->whereDate('created_at', Carbon::parse($dateto)->format('Y-m-d'));
        }
        elseif($datefrom && $dateto){
            $query->whereBetween(DB::raw("DATE(created_at)"), [
                Carbon::parse($datefrom)->format('Y-m-d'),
                Carbon::parse($dateto)->format('Y-m-d')
            ]);
        }
        if($for == "page"){
            $visitors = $query->paginate(20);
        }
        else{
            $visitors = $query->get();
        }
        
        return $visitors;
    }

    protected function getMonthNum($str)
    {
        $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        return array_search($str, $months);
    }

    public function formatter($reporttype, $list)
    {
        $data = [];
        switch($reporttype){
            case "Financials":
                foreach($list as $item){
                    $data[] = [
                        ['align' => 'left', 'value' => $item->id],
                        ['align' => 'left', 'value' => $item->resident->fullname],
                        ['align' => 'left', 'value' => $item->bill_year],
                        ['align' => 'left', 'value' => $item->monthname],
                        ['align' => 'right', 'value' => number_format($item->bill_amount, 2, '.', ',')],
                        ['align' => 'right', 'value' => number_format($item->payments()->sum('payment'), 2, '.', ',')],
                        ['align' => 'right', 'value' => number_format($item->balances()->sum('balance'), 2, '.', ',')],
                        ['align' => 'left', 'value' => date("m/d/y", strtotime($item->created_at))]
                    ];
                }
            break;
            case "Requests":
                foreach($list as $item){
                    $data[] = [
                        ['align' => 'left', 'value' => $item->id],
                        ['align' => 'left', 'value' => $item->request_type],
                        ['align' => 'left', 'value' => $item->reqBy->fullname],
                        ['align' => 'left', 'value' => $item->type],
                        ['align' => 'left', 'value' => $item->valid_from != '' ? date("m/d/Y", strtotime($item->valid_from)) . ' to ' . date("m/d/Y", strtotime($item->valid_to)) : ''],
                        ['align' => 'left', 'value' => $item->appBy ? $item->appBy->name : ''],
                        ['align' => 'left', 'value' => $item->chkBy ? $item->chkBy->name : ''],
                        ['align' => 'center', 'value' => $item->request_status],
                        ['align' => 'left', 'value' => date("m/d/y", strtotime($item->created_at))]
                    ];
                }
            break;
            case "Residents":
                foreach($list as $item){
                    $data[] = [
                        ['align' => 'left', 'value' => $item->id],
                        ['align' => 'left', 'value' => $item->fullname],
                        ['align' => 'left', 'value' => 'Brgy. ' . ucwords(strtolower($item->barangay->name)) . " " . $item->hoaaddress . ", " . ucwords(strtolower($item->city->name)) . (str_contains(strtolower($item->city->name), 'city') ? ', ' : ' City, ') . ucwords(strtolower($item->province->name))],
                        ['align' => 'left', 'value' => $item->email_address],
                        ['align' => 'left', 'value' => $item->mobile_number],
                        ['align' => 'center', 'value' => $item->status()],
                        ['align' => 'left', 'value' => date("m/d/y", strtotime($item->created_at))]
                    ];
                }
            break;
            case "Complaints":
                foreach($list as $item){
                    $data[] = [
                        ['align' => 'left', 'value' => $item->id],
                        ['align' => 'left', 'value' => $item->complaint_type],
                        ['align' => 'left', 'value' => $item->resident->fullname],
                        ['align' => 'left', 'value' => $item->complaint_to ? $item->defendant->fullname : ''],
                        ['align' => 'left', 'value' => $item->details],
                        ['align' => 'left', 'value' => $item->reported_to->name],
                        ['align' => 'center', 'value' => $item->status],
                        ['align' => 'left', 'value' => date("m/d/y", strtotime($item->created_at))]
                    ];
                }
            break;
            case "Visitors":
                foreach($list as $item){
                    $data[] = [
                        ['align' => 'left', 'value' => $item->id],
                        ['align' => 'left', 'value' => $item->visitor_type],
                        ['align' => 'left', 'value' => $item->name],
                        ['align' => 'left', 'value' => $item->purpose],
                        ['align' => 'left', 'value' => date("m/d/Y h:i A", strtotime($item->time_in))],
                        ['align' => 'left', 'value' => $item->time_out != '' ? date("m/d/Y h:i A", strtotime($item->time_out)) : ''],
                        ['align' => 'left', 'value' => date("m/d/y", strtotime($item->created_at))]
                    ];
                }
            break;
        }
        return $data;
    }
}