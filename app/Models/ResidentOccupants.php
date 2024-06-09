<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentOccupants extends Model
{
    use HasFactory;
    public $table = "resident_occupants";
    public $timestamps = true;
    protected $fillable = [
        "last_name",
        "first_name",
        "middle_name",
        "age",
        "gender",
        "relationship_to_homeowner",
        "email_address",
        "mobile_number"
    ];
}
