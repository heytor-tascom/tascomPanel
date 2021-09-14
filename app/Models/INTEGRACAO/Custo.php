<?php

namespace App\Models\INTEGRACAO;

use Illuminate\Database\Eloquent\Model;

class Custo extends Model
{
    protected $table = 'competencia';
    protected $connection = 'mysqlIntegracaoDrg';

    protected $fillable = [
        'acao',
        'cd_atendimento',
        'sn_integrado',
        'dt_competencia_geracao',
    ];

}
