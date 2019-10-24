<?php

namespace App\Models;

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
}
