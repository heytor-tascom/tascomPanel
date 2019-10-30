<?php

namespace App\Models\MV;

use Illuminate\Database\Eloquent\Model;

class ItemPrescricao extends Model
{
    protected $table        = 'itpre_med';
    protected $connection   = 'oracle';

    public function prescricao()
    {
        return $this->belongsTo(Prescricao::class, "cd_pre_med", "cd_pre_med");
    }

    public function tipoItemPrescricao()
    {
        return $this->belongsTo(TipoItemPrescricao::class, "cd_tip_presc", "cd_tip_presc");
    }

    public function horariosItemPrescricao()
    {
        return $this->hasMany(HoraItemPrescricao::class, "cd_itpre_med", "cd_itpre_med");
    }
}
