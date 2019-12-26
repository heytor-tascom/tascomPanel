<?php

namespace App\Models\MV;

use Illuminate\Database\Eloquent\Model;

class Leito extends Model
{
    protected $table        = 'leito';
    protected $connection   = 'oracle';

    public function atendimento()
    {
        return $this->belongsTo(Atendimento::class, "cd_leito", "cd_leito");
    }
}
