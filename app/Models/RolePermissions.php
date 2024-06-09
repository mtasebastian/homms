<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermissions extends Model
{
    use HasFactory;
    public $table = "role_permissions";
    public $timestamps = true;

    public function role()
    {
        return $this->belongsTo("App\Models\Roles", "id", "role_id");
    }
}
