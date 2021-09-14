<?php

namespace App\Models\INTEGRACAO;

use Illuminate\Database\Eloquent\Model;

class Depara extends Model
{
    protected $table = 'depara';
    protected $connection = 'mysqlIntegracaoDrg';

    protected $fillable = [
        'tp_depara',
        'cd_depara_mv',
        'cd_depara_integra',
        'cd_multi_empresa',
        'cd_sistema_integra'
    ];

}
