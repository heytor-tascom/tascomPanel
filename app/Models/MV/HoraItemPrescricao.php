<?php

namespace App\Models\MV;

use Illuminate\Database\Eloquent\Model;

class HoraItemPrescricao extends Model
{
    protected $table        = 'hritpre_med';
    protected $connection   = 'oracle';

    public function itemPrescricao()
    {
        return $this->belongsTo(ItemPrescricao::class, "cd_itpre_med", "cd_itpre_med");
    }
}
