<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table        = 'paciente';
    protected $connection   = 'oracle';

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class, 'cd_paciente', 'cd_paciente');
    }
}
