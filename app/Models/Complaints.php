<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaints extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps = true;

    public function resident()
    {
        return $this->belongsTo("App\Models\Residents", "resident_id", "id");
    }

    public function defendant()
    {
        return $this->belongsTo("App\Models\Residents", "complaint_to", "id");
    }

    public function reported_to()
    {
        return $this->belongsTo("App\Models\User", "report_to", "id");
    }
}
