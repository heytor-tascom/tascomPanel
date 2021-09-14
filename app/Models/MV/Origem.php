<?php

namespace App\Models\MV;

use Illuminate\Database\Eloquent\Model;

class Origem extends Model
{
    protected $table        = 'ori_ate';
    protected $connection   = 'oracle';

    public function setor()
    {
        return $this->belongsTo(Setor::class, "cd_setor", "cd_setor");
    }

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class, "cd_ori_ate", "cd_ori_ate");
    }

}
