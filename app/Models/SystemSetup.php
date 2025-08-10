<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetup extends Model
{
    use HasFactory;
    public $table = "sys_setup";
    public $timestamps = true;
}
