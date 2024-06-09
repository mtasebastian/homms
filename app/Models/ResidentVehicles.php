<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentVehicles extends Model
{
    use HasFactory;
    public $table = "resident_vehicles";
    public $timestamps = true;
    protected $fillable = [
        "type",
        "brand",
        "model",
        "plate_number"
    ];
}
