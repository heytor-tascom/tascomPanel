<?php

namespace App\Models\MV;

use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
    protected $table        = 'atendime';
    protected $connection   = 'oracle';

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, "cd_paciente", "cd_paciente");
    }

    public function convenio()
    {
        return $this->belongsTo(Convenio::class, "cd_convenio", "cd_convenio");
    }

    public function leito()
    {
        return $this->hasOne(Leito::class, "cd_leito", "cd_leito");
    }

    public function prestador()
    {
        return $this->hasOne(Prestador::class, "cd_prestador", "cd_prestador");
    }
}
