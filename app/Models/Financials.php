<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Financials extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps = true;

    public function resident()
    {
        return $this->belongsTo("App\Models\Residents", "resident_id", "id");
    }

    public function bills()
    {
        return $this->hasMany("App\Models\FinancialBills", "financial_id", "id");
    }
}
