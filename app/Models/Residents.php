<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Residents extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $appends = ["fullname", "hoaaddress", "fulladdress"];

    public function getFullNameAttribute()
    {
        return $this->last_name . ", " . $this->first_name . "  " . substr($this->middle_name, 0, 1) . ".";
    }

    public function getHoaAddressAttribute()
    {
        return $this->phase . " Block " . $this->block . " Lot " . $this->lot . " Unit " . $this->house_number;
    }

    public function getFullAddressAttribute()
    {
        return ($this->barangay ? "Brgy. " . ucwords(strtolower($this->barangay->name)) : "") . 
            " " . $this->hoaaddress . ", " .
            ($this->city ? ucwords(strtolower($this->city->name)) . (str_contains(strtolower($this->city->name), 'city') ? ', ' : ' City, ') : "") .
            ($this->province ? ucwords(strtolower($this->province->name)) : "");
    }

    public function province()
    {
        return $this->belongsTo("App\Models\Provinces", "province_id", "id");
    }

    public function city()
    {
        return $this->belongsTo("App\Models\Cities", "city_id", "id");
    }

    public function barangay()
    {
        return $this->belongsTo("App\Models\Barangays", "barangay_id", "id");
    }

    public function status()
    {
        return ($this->home_status == 1) ? "ACTIVE" : "INACTIVE";
    }

    public function occupants()
    {
        return $this->hasMany("App\Models\ResidentOccupants", "resident_id", "id");
    }

    public function vehicles()
    {
        return $this->hasMany("App\Models\ResidentVehicles", "resident_id", "id");
    }

    public function pets()
    {
        return $this->hasMany("App\Models\ResidentPets", "resident_id", "id");
    }

    public function balance()
    {
        return $this->hasOne("App\Models\Financials", "resident_id", "id")->latest();
    }

    public function residentStatus()
    {
        switch($this->home_status){
            case "1": return "bg-info text-white"; break;
            case "0": return "bg-light text-dark"; break;
        }
    }
}
