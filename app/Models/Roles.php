<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Roles extends Model
{
    use HasFactory;
    use SoftDeletes, Sortable;
    public $table = "user_roles";
    public $timestamps = true;

    public function permissions()
    {
        return $this->hasMany("App\Models\RolePermissions", "role_id", "id");
    }

    public function routes()
    {
        return $this->hasMany("App\Models\RolePermissions", "role_id", "id")->pluck('route');
    }

    public function status()
    {
        return ($this->status == 1) ? "ENABLED" : "DISABLED";
    }

    public function roleStatus()
    {
        switch($this->status){
            case "1": return "bg-info text-white"; break;
            case "0": return "bg-light text-dark"; break;
        }
    }
}
