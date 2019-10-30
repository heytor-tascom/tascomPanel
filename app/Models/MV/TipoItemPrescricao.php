<?php

namespace App\Models\MV;

use Illuminate\Database\Eloquent\Model;

class TipoItemPrescricao extends Model
{
    protected $table        = 'tip_presc';
    protected $connection   = 'oracle';

    public function itensPrescricao()
    {
        return $this->hasMany(ItemPrescricao::class, "cd_tip_presc", "cd_tip_presc");
    }
}
