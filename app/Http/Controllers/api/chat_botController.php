<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class chat_botController extends Controller
{
    public function index(Request $request)
    {
        $cd_atendimnento = $request->input('cd_atendimento');
        $token = $request->input('token');

        

        if ($token == 'ghjr4925ddrnnlpo56c6d5hj6d5b2e9aqz6494adadhjkghudsdf4d54mlo9kyrscnznx'){

            $pacientes            = self::pacientes($cd_atendimnento);
            
            if ($pacientes == []){
                return [ 0 => 
                    ['cd_atendimento' => null]
                ];
            }else{
                return $pacientes;
            }

            
        }else{
            return [ 0 => 
                ['cd_atendimento' => null]
            ];
        }

        
        
        
    }

    public static function pacientes($cd_atendimnento)
    {
        return DB::connection('oracle')
                    ->select("SELECT 
                    U.CD_SETOR || '-' || L.CD_LEITO  AS cd_setor_leito,
                    DECODE(P.SN_VIP, 'S', 'VIP', '') AS PACIENTE_VIP,
                    A.CD_ATENDIMENTO,
                    P.CD_PACIENTE,
                    P.NM_PACIENTE,
                    A.CD_CONVENIO,
                    C.NM_CONVENIO,
                    To_Char(P.DT_NASCIMENTO, 'DD/MM/YYYY') dt_nascimento,
                    A.CD_MULTI_EMPRESA,
                    DECODE(P.NR_CELULAR, NULL, P.NR_FONE, P.NR_CELULAR) AS TELEFONE,
                    (
                        SELECT	CAT.DS_CATEGORIA
                        FROM 	DBAMV.CATEGORIA CAT,
                                DBAMV.CATEGORIA_PACIENTE CP
                        WHERE 	CAT.CD_CATEGORIA 	= CP.CD_CATEGORIA
                        AND 	CP.CD_PACIENTE		= P.CD_PACIENTE
                        AND 	CAT.SN_ATIVO		= 'S'
                  AND P.CD_PACIENTE IN (
                    SELECT DISTINCT CD_PACIENTE FROM ATENDIME A, ITPRE_MED I , PRE_MED P
                    WHERE A.CD_ATENDIMENTO = P.CD_ATENDIMENTO
                    AND   I.CD_PRE_MED = P.CD_PRE_MED
                    AND   I.CD_TIP_ESQ = 'CIH'
                    AND   I.CD_TIP_PRESC IN (1,2,3,4,5,6,7,6793,6795,6797)
                  )
                    ) AS PRECAUCAO
            FROM 	DBAMV.ATENDIME A,
                    DBAMV.PACIENTE P,
                    DBAMV.CONVENIO C,
                    DBAMV.LEITO L,
                    DBAMV.UNID_INT U
            WHERE	A.CD_PACIENTE 		= P.CD_PACIENTE
            AND 	A.CD_CONVENIO		= C.CD_CONVENIO
            AND 	A.CD_LEITO 			= L.CD_LEITO(+)
            AND 	L.CD_UNID_INT		= U.CD_UNID_INT(+)
            AND 	A.DT_ALTA 			IS NULL
            AND 	A.TP_ATENDIMENTO 	= 'I'
            and     A.CD_ATENDIMENTO = $cd_atendimnento 
            ");
    }
}
