<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefSetup extends Model
{
    use HasFactory;
    public $table = "ref_setup";
    public $timestamps = true;

    public function referential()
    {
        return $this->hasOne("App\Models\Referentials", "id", "ref_id");
    }
}
