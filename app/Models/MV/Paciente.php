<?php

namespace App\Models\MV;

use DB;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table        = 'paciente';
    protected $connection   = 'oracle';

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class, 'cd_paciente', 'cd_paciente');
    }

    public function medicacoesAtrasadas($cdAtendimento)
    {
        return DB::connection('oracle')
                    ->select("SELECT 	*
                    FROM 	(
                    SELECT 	itpre_med.cd_itpre_med,
                            cd_tip_presc,
                            (SELECT ds_tip_presc FROM tip_presc WHERE cd_tip_presc = itpre_med.cd_tip_presc) AS ds_tip_presc,
                            hritpre_med.dh_medicacao,
                            hritpre_cons.dh_checagem,
                            CASE WHEN hritpre_med.dh_medicacao < SYSDATE AND hritpre_cons.dh_checagem IS NULL THEN '1' ELSE '0' END AS atrasado
                    FROM 	pre_med,
                            itpre_med,
                            hritpre_med,
                            hritpre_cons
                    WHERE 	pre_med.cd_atendimento 		= $cdAtendimento
                    AND 	pre_med.dh_impressao 		IS NOT NULL
                    AND 	pre_med.tp_pre_med 			= 'M'
                    AND 	pre_med.DT_REFERENCIA 		>= trunc(SYSDATE)
                    AND 	itpre_med.cd_pre_med 		= pre_med.cd_pre_med
                    AND 	hritpre_med.cd_itpre_med 	= itpre_med.cd_itpre_med
                    AND 	hritpre_med.cd_itpre_med 	= hritpre_cons.cd_itpre_med(+)
                    AND 	hritpre_med.dh_medicacao 	= hritpre_cons.dh_medicacao(+)
                    )
                    WHERE 	atrasado = 1");
    }
}
