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

    public function origem()
    {
        return $this->hasMany(Origem::class, "cd_setor", "cd_setor");
    }

    public function getByTipo(Array $tiposSetores)
    {
        return $this->where("setor.sn_ativo", "S")
                    ->whereIn("setor.tp_setor", $tiposSetores)
                    ->where("setor.sn_ativo", "=", "S")
                    ->select("setor.cd_setor", "setor.nm_setor")
                    ->orderBy("setor.nm_setor")
                    ->get();
    }

    public function getByGrupo(Array $tiposGrupo)
    {
        return $this->where("setor.sn_ativo", "S")
                    ->whereIn("setor.tp_grupo_setor", $tiposGrupo)
                    ->where("setor.sn_ativo", "=", "S")
                    ->select("setor.cd_setor", "setor.nm_setor")
                    ->orderBy("setor.nm_setor")
                    ->get();
    }
}
