<?php

namespace App\Models\MV;

use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    protected $table        = 'setor';
    protected $connection   = 'oracle';

    public function unidsInt()
    {
        return $this->hasMany(UnidInt::class, "cd_setor", "cd_setor");
    }

    public function getByTipo(Array $tiposSetores)
    {
        return $this->where("setor.sn_ativo", "S")
                    ->whereIn("setor.tp_setor", $tiposSetores)
                    ->orderBy("setor.nm_setor")
                    ->select("setor.cd_setor", "setor.nm_setor")
                    ->get();
    }
}
