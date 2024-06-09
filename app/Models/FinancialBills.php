<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialBills extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $table = "financial_bills";
    public $timestamps = true;

    public function bill()
    {
        return $this->belongsTo("App\Models\FinancialSetup", "bill_id", "id");
    }
}
