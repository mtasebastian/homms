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
    protected $appends = ["formattedcat", "formatteduat"];

    public function resident()
    {
        return $this->belongsTo("App\Models\Residents", "resident_id", "id");
    }

    public function bills()
    {
        return $this->hasMany("App\Models\FinancialBills", "financial_id", "id");
    }
    
    public function getFormattedcatAttribute()
    {
        return Carbon::parse($this->created_at)->format("Y-m-d");
    }

    public function getFormatteduatAttribute($date)
    {
        return Carbon::parse($this->updated_at)->format("Y-m-d");
    }
}
