<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidInt extends Model
{
    protected $table = "unid_int";
    protected $connection = "oracle";

    public function setor()
    {
        return $this->belongsTo(Setor::class, "cd_setor", "cd_setor");
    }
}
