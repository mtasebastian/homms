<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaints;
use App\Models\RefSetup;

class ComplaintsController extends Controller
{
    public function index(Request $request)
    {
        $params = ['complaints', 'refsetup'];
        $refsetup = RefSetup::whereIn("for", ["comptype", "compstatus"])->with("referential")->get();
        $complaints = Complaints::paginate(20);
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
        $complaint->details = $request->compremarks;
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
