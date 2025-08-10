<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialBills extends Model
{
    use HasFactory;
    public $table = "financial_bills";
    public $timestamps = true;
    protected $appends = ["formattedamt", "billname"];

    public function getFormattedamtAttribute()
    {
        return number_format($this->bill_amt, 2, '.', ',');
    }

    public function getBillnameAttribute()
    {
        return $this->bill->bill_name;
    }

    public function bill()
    {
        return $this->belongsTo("App\Models\FinancialSetup", "bill_id", "id");
    }
}
