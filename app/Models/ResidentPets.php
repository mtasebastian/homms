<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentPets extends Model
{
    use HasFactory;
    public $table = "resident_pets";
    public $timestamps = true;
    protected $fillable = [
        "type",
        "breed",
        "name",
        "birth_date"
    ];
}
