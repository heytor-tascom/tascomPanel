<?php

namespace App\Models\MV;

use Illuminate\Database\Eloquent\Model;

class Convenio extends Model
{
    protected $table        = 'convenio';
    protected $connection   = 'oracle';

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class, "cd_convenio", "cd_convenio");
    }
}
