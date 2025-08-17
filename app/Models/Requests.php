<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Requests extends Model
{
    use HasFactory;
    protected $appends = ["formattedrequestdate", "formattedvalidfrom", "formattedvalidto"];

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
            case "Submitted": return "bg-secondary text-white"; break;
            case "Pending": return "bg-info text-white"; break;
            case "Checked": return "bg-warning text-white"; break;
            case "Approved": return "bg-success text-white"; break;
        }
    }

    public function getFormattedRequestdateAttribute()
    {
        return Carbon::parse($this->created_at)->format("m/d/Y");
    }

    public function getFormattedValidfromAttribute()
    {
        return Carbon::parse($this->valid_from)->format("m/d/Y");
    }

    public function getFormattedValidtoAttribute()
    {
        return Carbon::parse($this->valid_to)->format("m/d/Y");
    }
}