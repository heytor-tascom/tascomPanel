<?php

namespace App\Models\MV;

use App\Models\Atendimento;
use Illuminate\Database\Eloquent\Model;

class Prescricao extends Model
{
    protected $table        = 'pre_med';
    protected $connection   = 'oracle';

    public function atendimento()
    {
        return $this->belongsTo(Atendimento::class, "cd_atendimento", "cd_atendimento");
    }

    public function prestador()
    {
        return $this->belongsTo(Prestador::class, "cd_prestador", "cd_prestador");
    }

    public function itens()
    {
        return $this->hasMany(ItemPrescricao::class, "cd_pre_med", "cd_pre_med");
    }
}
