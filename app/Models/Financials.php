<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Financials extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps = true;
    protected $appends = ["formattedid", "formattedcat", "formatteduat", "monthname", "formattedamt", "formattedbal"];

    public function resident()
    {
        return $this->belongsTo("App\Models\Residents", "resident_id", "id");
    }

    public function payments()
    {
        return $this->hasMany("App\Models\FinancialPayments", "financial_id", "id");
    }

    public function bills()
    {
        return $this->hasMany("App\Models\FinancialBills", "financial_id", "id");
    }

    public function balances()
    {
        if($this->latest('created_at')){
            return $this->hasMany(self::class, "resident_id", "resident_id")->where("id", "<=", $this->id);
        }
    }

    public function getFormattedidAttribute()
    {
        return str_pad($this->id, 7, '0', STR_PAD_LEFT);
    }
    
    public function getFormattedcatAttribute()
    {
        return Carbon::parse($this->created_at)->format("Y-m-d");
    }

    public function getFormatteduatAttribute()
    {
        return Carbon::parse($this->updated_at)->format("Y-m-d");
    }

    public function getMonthnameAttribute()
    {
        $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        return $months[$this->bill_month];
    }

    public function getFormattedamtAttribute()
    {
        return number_format($this->bill_amount, 2, '.', ',');
    }

    public function getFormattedbalAttribute()
    {
        return number_format($this->balance, 2, '.', ',');
    }
}
