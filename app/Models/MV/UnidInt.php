<?php

namespace App\Models\MV;

use Illuminate\Database\Eloquent\Model;

class UnidInt extends Model
{
    protected $table = "unid_int";
    protected $connection = "oracle";

    public function setor()
    {
        return $this->belongsTo(Setor::class, "cd_setor", "cd_setor");
    }

    public function atendimentos()
    {
        return $this->belongsTo(Atendimento::class, "cd_unid_int", "cd_unid_int");
    }
}
