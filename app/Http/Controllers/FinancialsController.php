<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Financials;
use App\Models\FinancialBills;
use App\Models\FinancialPayments;
use App\Models\RefSetup;
use App\Models\FinancialSetup;
use App\Models\Residents;
use App\Models\SystemSetup;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialsController extends Controller
{
    public function index(Request $request)
    {
        $params = ['financials', 'refsetup', 'finsetup'];
        $refsetup = RefSetup::whereIn("for", ["mod", "disctype"])->with("referential")->get();
        $finsetup = FinancialSetup::get();

        $columns = \Schema::getColumnListing('residents');
        $selectColumns = ['financials.*'];
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

        $query = Financials::select($selectColumns)
                ->join('residents', 'financials.resident_id', '=', 'residents.id')
                ->with(["resident"]);
        $searchkey = $request->searchkey;
        $year = $request->billyear;
        $month = $request->billmonth;

        if($searchkey){
            $query->where(DB::raw("CONCAT(residents.last_name, ' ', residents.first_name, ' ', residents.middle_name)"), "like", "%$searchkey%");
            $params[] = 'searchkey';
        }
        
        if($year && !$month){
            $query->where("bill_year", $year);
            $params[] = 'year';
        }
        elseif(!$year && $month){
            $query->where("bill_month", self::getMonthNum($month));
            $params[] = 'month';
        }
        elseif($year && $month){
            $query->where("bill_year", $year)
                ->where("bill_month", self::getMonthNum($month));
            $params[] = 'year';
            $params[] = 'month';
        }

        $financials = $query->orderBy('bill_year', 'desc')
            ->orderBy('bill_month', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));
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
            $reslist = Residents::pluck("id");
        }
        foreach($reslist as $res){
            $resident = Residents::find($res);
            $financial = new Financials();
            $financial->bill_year = $request->finbillyear;
            $financial->bill_month = self::getMonthNum($request->finbillmonth);
            $financial->due_date = Carbon::parse($request->finduedate)->format("Y-m-d");
            $financial->resident_id = $res;
            $financial->bill_amount = $total;
            $financial->balance = $total;
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

    public function billingstatement(Request $request)
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

        $financial = Financials::with(["resident", "bills"])->find($request->id);
        $ret['financials'] = $financial;

        return $ret;
    }

    public function receipt(Request $request)
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

        
        $financial_payment = FinancialPayments::find($request->id);
        $financial = Financials::with(["resident"])->find($financial_payment->financial_id);
        $ret['financial_payments'] = $financial_payment;
        $ret['financials'] = $financial;

        return $ret;
    }

    protected function getMonthNum($str)
    {
        $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        return array_search($str, $months);
    }
}
