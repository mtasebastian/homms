<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Financials;
use App\Models\FinancialBills;
use App\Models\FinancialPayments;
use App\Models\RefSetup;
use App\Models\FinancialSetup;
use App\Models\Residents;
use Carbon\Carbon;

class FinancialsController extends Controller
{
    public function index(Request $request)
    {
        $params = ['financials', 'refsetup', 'finsetup'];
        $refsetup = RefSetup::whereIn("for", ["mod", "disctype"])->with("referential")->get();
        $finsetup = FinancialSetup::get();
        $financials = Financials::with("resident")->where("created_at", Financials::max("created_at"))->paginate(10);
        if($request->searchkey != null){
            $financials = Financials::with("resident")->where("name", "like", "%" . $request->searchkey . "%")->paginate(10);
            $searchkey = $request->searchkey;
            array_push($params, ['searchkey']);
            if($request->datefrom != null && $request->dateto == null){
                $financials = Financials::with("resident")->where("name", "like", "%" . $request->searchkey . "%")->whereDate("created_at", Carbon::parse($request->datefrom)->format("Y-m-d"))->paginate(10);
                $datefrom = $request->datefrom;
                array_push($params, ['datefrom']);
            }
            elseif($request->datefrom == null && $request->dateto != null){
                $financials = Financials::with("resident")->where("name", "like", "%" . $request->searchkey . "%")->whereDate("created_at", Carbon::parse($request->dateto)->format("Y-m-d"))->paginate(10);
                $dateto = $request->dateto;
                array_push($params, ['dateto']);
            }
            elseif($request->datefrom != null && $request->dateto != null){
                $financials = Financials::with("resident")->where("name", "like", "%" . $request->searchkey . "%")->whereBetween(DB::raw("DATE(created_at)"), [Carbon::parse($request->datefrom)->format("Y-m-d"), Carbon::parse($request->dateto)->format("Y-m-d")])->paginate(10);
                $datefrom = $request->datefrom;
                $dateto = $request->dateto;
                array_push($params, ['datefrom', 'dateto']);
            }
        }
        elseif($request->searchkey == null){
            if($request->datefrom != null && $request->dateto == null){
                $financials = Financials::with("resident")->whereDate("created_at", Carbon::parse($request->datefrom)->format("Y-m-d"))->paginate(10);
                $datefrom = $request->datefrom;
                array_push($params, ['datefrom']);
            }
            elseif($request->datefrom == null && $request->dateto != null){
                $financials = Financials::with("resident")->whereDate("created_at", Carbon::parse($request->dateto)->format("Y-m-d"))->paginate(10);
                $dateto = $request->dateto;
                array_push($params, ['dateto']);
            }
            elseif($request->datefrom != null && $request->dateto != null){
                $financials = Financials::with("resident")->whereDate("created_at", ">=", Carbon::parse($request->datefrom)->format("Y-m-d"))->whereDate("created_at", "<=", Carbon::parse($request->dateto)->format("Y-m-d"))->paginate(10);
                $datefrom = $request->datefrom;
                $dateto = $request->dateto;
                array_push($params, ['datefrom', 'dateto']);
            }
        }
        return view("financials.index", compact($params));
    }

    public function searchresident(Request $request)
    {
        $residents = Residents::where("last_name", "like", "%" . $request->key . "%")->orWhere("first_name", "like", "%" . $request->key . "%")->orWhere("middle_name", "like", "%" . $request->key . "%")->with("balance")->get();
        return $residents;
    }

    public function generatebills(Request $request)
    {
        $findsetup = FinancialSetup::whereIn("id", $request->fin)->get();
        $total = $findsetup->sum("bill_amt");

        $reslist = json_decode($request->chkreslist, true);
        if($request->btngeneratebills == "all"){
            $reslist = Residents::get();
        }
        foreach($reslist as $res){
            $resident = Residents::find($res);
            $financial = new Financials();
            $financial->bill_period = Carbon::parse($request->finperiod)->format("Y-m-d");
            if($request->btngeneratebills == "all"){
                $resident = $res;
                $financial->resident_id = $res->id;
            }
            else{
                $financial->resident_id = $res;
            }
            $financial->bill_amount = $total;
            if($resident->balance == null){
                $financial->balance = $total;
            }
            else{
                $financial->balance = $resident->balance->balance + $total;
            }
            $financial->remarks = $request->finremarks;
            $financial->save();

            foreach($findsetup as $fin){
                $finbill = new FinancialBills();
                $finbill->financial_id = $financial->id;
                $finbill->bill_id = $fin->id;
                $finbill->bill_amt = $fin->bill_amt;
                $finbill->save();
            }
        }

        return redirect()->back()->with("success", "Bills has been generated.");
    }

    public function addpayment(Request $request)
    {
        $payment = new FinancialPayments();
        $payment->financial_id = $request->payfinid;
        $payment->reference_number = $request->payrefno;
        $payment->mode_of_payment = $request->paymod;
        $payment->payment = $request->payamt;
        $payment->discount_type = $request->disctype;
        $payment->discount_amount = $request->discamt;
        $payment->remarks = $request->payremarks;
        $payment->save();

        $financial = Financials::find($request->payfinid);
        $financial->balance = $financial->balance - $payment->payment;
        $financial->save();

        return redirect()->back()->with("success", "Payment has been posted.");
    }

    public function paymentlist(Request $request)
    {
        $payments = FinancialPayments::where("financial_id", $request->id)->get();
        return $payments;
    }
}
