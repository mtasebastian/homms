<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Requests;
use App\Models\RefSetup;
use Carbon\Carbon;

class RequestsController extends Controller
{
    public function index(Request $request)
    {
        $params = ['reqs', 'refsetup'];
        $refsetup = RefSetup::whereIn("for", ["reqtype", "reqstatus"])->with("referential")->get();
        $search = $request->txtreqsearch;
        $dateFrom = $request->txtreqdatefrom;
        $dateTo = $request->txtreqdateto;
        $query = Requests::with(["reqBy", "appBy", "chkBy"]);
        if($search){
            $query->where(function ($q) use ($search) {
                $q->where('request_type', 'like', "%$search%")
                ->orWhere('details', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%");
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
        $reqs = $query->paginate(10);
        $reqs->appends($request->except('page')); 
        return view("requests.index", compact($params));
    }

    public function addrequest(Request $request)
    {
        if($request->btnsubmit){
            $req = new Requests();
            $req->request_type = $request->reqtype;
            $req->type = $request->reqtranstype;
            $req->address = $request->reqaddress;
            $req->details = $request->reqdetails;
            $req->pullout_delivery_date = Carbon::parse($request->reqtransdate)->format("Y-m-d");
            $req->valid_from = Carbon::parse($request->reqvalidfrom)->format("Y-m-d");
            $req->valid_to = Carbon::parse($request->reqvalidto)->format("Y-m-d");
            $req->requested_by = $request->reqrequestedbyid;
            $req->request_status = $request->reqstatus;
            $req->qr_code = self::genQR(20);
            $req->save();
            
            return redirect()->back()->with("success", "Request has been added.");
        }
    }

    public function updaterequest(Request $request)
    {
        if($request->btnsubmit){
            $req = Requests::find($request->reqid);
            $req->request_type = $request->reqtype;
            $req->type = $request->reqtranstype;
            $req->address = $request->reqaddress;
            $req->details = $request->reqdetails;
            $req->pullout_delivery_date = Carbon::parse($request->reqtransdate)->format("Y-m-d");
            $req->valid_from = Carbon::parse($request->reqvalidfrom)->format("Y-m-d");
            $req->valid_to = Carbon::parse($request->reqvalidto)->format("Y-m-d");
            $req->requested_by = $request->reqrequestedbyid;
            $req->request_status = $request->reqstatus;
            $req->save();
        }
        elseif($request->btnapprove){
            $req = Requests::find($request->reqid);
            $req->request_status = "Approved";
            $req->approved_by = Auth::user()->id;
            $req->save();
            
            return redirect()->back()->with("success", "Request has been approved.");
        }
    }

    public function deleterequest(Request $request)
    {
        $request = Requests::find($request->compdelid);
        $request->delete();
            
        return redirect()->back()->with("success", "Request has been deleted.");
    }

    public function genQR($length)
    {
        $characters = "0123456789";
        $charactersLength = strlen($characters);
        $qrCode = "";
        for($i = 0; $i < $length; $i++){
            $qrCode .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $qrCode;
    }

    public function getqrcode(Request $request)
    {
        $data = $request->all();
        return view("requests.qrcode", compact(["data"]));
    }

    public function findrequest(Request $request)
    {
        $req = Requests::where("qr_code", $request->qrcode)->with("reqBy")->first();
        return $req;
    }

    public function checkrequest(Request $request)
    {
        $req = Requests::find($request->id);
        $req->request_status = "Checked";
        $req->checked_by = Auth::user()->id;
        $req->save();
        return "success";
    }
}
