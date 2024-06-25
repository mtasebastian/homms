<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    use HasFactory;

    public function reqBy()
    {
        return $this->belongsTo("App\Models\Residents", "requested_by", "id");
    }

    public function appBy()
    {
        return $this->belongsTo("App\Models\User", "approved_by", "id");
    }

    public function chkBy()
    {
        return $this->belongsTo("App\Models\User", "checked_by", "id");
    }

    public function requestStatus()
    {
        switch($this->request_status){
            case "Pending": return "bg-info text-white"; break;
            case "Checked": return "bg-warning text-white"; break;
            case "Approved": return "bg-success text-white"; break;
        }
    }
}