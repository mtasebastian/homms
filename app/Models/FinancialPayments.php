<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialPayments extends Model
{
    use HasFactory;
    public $table = "financials_payment";
    public $timestamps = true;
    public $appends = ["formatted_date"];
    
    public function getFormattedDateAttribute()
    {
        return date("m/d/Y h:i A", strtotime($this->created_at));
    }
}
