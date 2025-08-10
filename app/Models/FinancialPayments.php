<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialPayments extends Model
{
    use HasFactory;
    public $table = "financials_payment";
    public $timestamps = true;
    public $appends = ["formattedid", "formatteddate", "formattedamount", "formatteddiscount", "formattedtotal"];

    public function getFormattedidAttribute()
    {
        return str_pad($this->id, 7, '0', STR_PAD_LEFT);
    }
    
    public function getFormattedDateAttribute()
    {
        return date("m/d/Y h:i A", strtotime($this->created_at));
    }
    
    public function getFormattedAmountAttribute()
    {
        return number_format($this->payment, "2", ".", ",");
    }
    
    public function getFormattedDiscountAttribute()
    {
        return $this->discount_amount ? number_format($this->discount_amount, "2", ".", ",") : "";
    }
    
    public function getFormattedTotalAttribute()
    {
        $total = $this->payment;
        if($this->discount_amount){
            $total = $total - $this->discount_amount;
        }
        return number_format($total, "2", ".", ",");
    }
}
