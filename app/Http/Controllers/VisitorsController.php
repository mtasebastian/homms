<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitors;
use App\Models\RefSetup;
use Carbon\Carbon;

class VisitorsController extends Controller
{
    public function index(Request $request)
    {
        $params = ['visitors', 'refsetup'];
        $refsetup = RefSetup::whereIn("for", ["visitortype", "presentedid"])->with("referential")->get();
        $query = Visitors::query();
        $search = $request->txtvisitorsearch;
        $dateFrom = $request->txtvisitordatefrom;
        $dateTo = $request->txtvisitordateto;
        if($search){
            $query->where(function ($q) use ($search) {
                $q->where('visitor_type', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%")
                ->orWhere('purpose', 'like', "%$search%");
            });
            $searchkey = $search;
            array_push($params, ['searchkey']);
        }

        if($dateFrom && !$dateTo){
            $query->whereDate('created_at', Carbon::parse($dateFrom)->format('Y-m-d'));
            array_push($params, ['datefrom']);
        }
        elseif(!$dateFrom && $dateTo){
            $query->whereDate('created_at', Carbon::parse($dateTo)->format('Y-m-d'));
            array_push($params, ['dateto']);
        }
        elseif($dateFrom && $dateTo){
            $query->whereBetween(DB::raw("DATE(created_at)"), [
                Carbon::parse($dateFrom)->format('Y-m-d'),
                Carbon::parse($dateTo)->format('Y-m-d')
            ]);
            array_push($params, ['datefrom', 'dateto']);
        }
        $visitors = $query->paginate(10);
        $visitors->appends($request->except('page')); 
        return view("visitors.index", compact($params));
    }

    public function addvisitor(Request $request)
    {
        $visitor = new Visitors();
        $visitor->visitor_type = $request->visvisitortype;
        $visitor->presented_id = $request->vispresentedid;
        $visitor->name = $request->visname;
        $visitor->purpose = $request->vispurpose;
        $visitor->remarks = $request->visremarks;
        $visitor->time_in = Carbon::now()->format("Y-m-d H:i:s");
        $visitor->save();

        return redirect()->back()->with("success", "Visitor has been added.");
    }

    public function timeoutvisitor(Request $request)
    {
        $visitor = Visitors::find($request->visoutid);
        $visitor->time_out = Carbon::now()->format("Y-m-d H:i:s");
        $visitor->save();

        return redirect()->back()->with("success", "The visitor has left.");
    }
}
