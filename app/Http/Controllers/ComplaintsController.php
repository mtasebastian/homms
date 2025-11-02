<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaints;
use Illuminate\Support\Facades\DB;
use App\Models\RefSetup;
use Carbon\Carbon;

class ComplaintsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $params = ['complaints', 'refsetup'];
        $refsetup = RefSetup::whereIn("for", ["comptype", "compstatus"])->with("referential")->get();
        
        $columns = \Schema::getColumnListing('residents');
        $selectColumns = ['complaints.*'];
        foreach($columns as $col){
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
        
        $query = Complaints::select($selectColumns)
                ->join('residents', 'complaints.resident_id', '=', 'residents.id');
        $search = $request->txtcomplaintsearch;
        $datefrom = $request->txtcomplaintdatefrom;
        $dateto = $request->txtcomplaintdateto;

        if($search){
            $query->where(function ($q) use ($search){
                $q->where('complaint_type', 'like', "%$search%")
                ->orWhere('purpose', 'like', "%$search%")
                ->orWhere('details', 'like', "%$search%")
                ->orWhere(DB::raw("CONCAT(residents.last_name, ' ', residents.first_name, ' ', residents.middle_name)"), 'like', "%$search%")
                ->when($user->role->role === 'Resident', function($q) use($search, $user){
                    $q->orWhere('complaints.resident_id', $user->resident->id);
                });
            });
            $searchkey = $search;
            array_push($params, ['searchkey']);
        }
        else{
            $query->when($user->role->role == 'Resident', function($q) use($user){
                $q->orWhere('complaints.resident_id', $user->resident->id);
            });
        }

        if($datefrom && !$dateto){
            $query->whereDate('complaints.created_at', Carbon::parse($datefrom)->format('Y-m-d'));
            array_push($params, ['datefrom']);
        }
        elseif(!$datefrom && $dateto){
            $query->whereDate('complaints.created_at', Carbon::parse($dateto)->format('Y-m-d'));
            array_push($params, ['dateto']);
        }
        elseif($datefrom && $dateto){
            $query->whereBetween(DB::raw("DATE(complaints.created_at)"), [
                Carbon::parse($datefrom)->format('Y-m-d'),
                Carbon::parse($dateto)->format('Y-m-d')
            ]);
            array_push($params, ['datefrom', 'dateto']);
        }

        $complaints = $query->paginate(15);
        $complaints->appends($request->except('page')); 
        return view("complaints.index", compact($params));
    }

    public function addcomplaint(Request $request)
    {
        $complaint = new Complaints();
        $complaint->complaint_type = $request->comptype;
        $complaint->status = $request->compstatus;
        $complaint->resident_id = $request->compresid;
        $complaint->complaint_to = $request->compdefid;
        $complaint->purpose = $request->comppurpose;
        $complaint->details = $request->compremarks ?? 'N/A';
        $complaint->report_to = Auth::user()->id;
        $complaint->save();

        return redirect()->back()->with("success", "Complaint has been added.");
    }

    public function updatecomplaint(Request $request)
    {
        $complaint = Complaints::find($request->compid);
        $complaint->complaint_type = $request->comptype;
        $complaint->status = $request->compstatus;
        $complaint->resident_id = $request->compresid;
        $complaint->complaint_to = $request->compdefid;
        $complaint->purpose = $request->comppurpose;
        $complaint->details = $request->compremarks;
        $complaint->report_to = Auth::user()->id;
        $complaint->save();

        return redirect()->back()->with("success", "Complaint has been updated.");
    }

    public function deletecomplaint(Request $request)
    {
        try{
            Complaints::find($request->compdelid)->delete();
            return redirect()->back()->with("success", "Complaint has been deleted.");
        }
        catch(Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
}
