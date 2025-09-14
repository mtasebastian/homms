<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Requests;
use Illuminate\Support\Facades\DB;
use App\Models\RefSetup;
use App\Models\SystemSetup;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class RequestsController extends Controller
{
    public function index(Request $request)
    {
        $params = ['reqs', 'refsetup'];
        $refsetup = RefSetup::whereIn("for", ["reqtype", "reqstatus", "reqtranstype"])->with("referential")->get();
        $search = $request->txtreqsearch;
        $datefrom = $request->txtreqdatefrom;
        $dateto = $request->txtreqdateto;

        $columns = \Schema::getColumnListing('residents');
        $selectColumns = ['requests.*'];
        foreach ($columns as $col) {
            if($col === 'id'){
                $selectColumns[] = "residents.id as resident_id";
            }
            elseif($col === 'created_at') {
                $selectColumns[] = "residents.created_at as resident_created_at";
            }
            else{
                $selectColumns[] = "residents.$col";
            }
        }

        $query = Requests::select($selectColumns)
                ->join('residents', 'requests.requested_by', '=', 'residents.id')
                ->with(["reqBy", "appBy", "chkBy"]);
        if($search){
            $query->where(function ($q) use ($search) {
                $q->where('requests.request_type', 'like', "%$search%")
                ->orWhere('requests.details', 'like', "%$search%")
                ->orWhere('requests.address', 'like', "%$search%")
                ->orWhere(DB::raw("CONCAT(residents.last_name, ' ', residents.first_name, ' ', residents.middle_name)"), 'like', "%$search%");
            });
            $searchkey = $search;
            array_push($params, ['searchkey']);
        }

        if($datefrom && !$dateto){
            $query->whereDate('requests.created_at', Carbon::parse($datefrom)->format('Y-m-d'));
            array_push($params, ['datefrom']);
        }
        elseif(!$datefrom && $dateto){
            $query->whereDate('requests.created_at', Carbon::parse($dateto)->format('Y-m-d'));
            array_push($params, ['dateto']);
        }
        elseif($datefrom && $dateto){
            $query->whereBetween(DB::raw("DATE(requests.created_at)"), [
                Carbon::parse($datefrom)->format('Y-m-d'),
                Carbon::parse($dateto)->format('Y-m-d')
            ]);
            array_push($params, ['datefrom', 'dateto']);
        }
        $reqs = $query->paginate(15);
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

    public function printRequest(Request $request)
    {
        $ret = [];
        $sys_setup = SystemSetup::get();
        foreach($sys_setup as $setup){
            if($setup->setting_name == "systemlogo"){
                $ret['systemlogo'] = [
                    "mime" => $setup->mime,
                    "content" => base64_encode($setup->content)
                ];
            }
            else{
                $ret[$setup->setting_name] = $setup->text;
            }
        }

        $req = Requests::with("reqBy")->find($request->id);
        $qrcode = QrCode::format('svg')->size(120)->generate($req->qr_code);
        $ret["request"] = $req;
        $ret["qrcode"] = (string) $qrcode;
        
        return response()->json($ret);
    }
}
