<?php
namespace App\Http\Helpers;

use DB;

class PacienteHelpers
{
    public static function protocolo($atendimentoId, $cdAlertaProtocolo)
    {
        return DB::connection('oracle')
                    ->select("
                    SELECT	CD_ETAPA_PROTOCOLO,
                            DS_RGB_HEXADECIMAL as COR,
                            DS_SIGLA_PROTOCOLO
                    FROM 	PW_CASO_PROTOCOLO,
                            PW_ALERTA_PROTOCOLO
                    WHERE 	PW_CASO_PROTOCOLO.CD_CASO_PROTOCOLO = (
                        SELECT 	MAX(CD_CASO_PROTOCOLO)
                        FROM 	PW_CASO_PROTOCOLO
                        WHERE 	PW_CASO_PROTOCOLO.CD_ALERTA_PROTOCOLO 	= $cdAlertaProtocolo
                        AND 	CD_ATENDIMENTO  						= $atendimentoId
                    )
                    AND     PW_CASO_PROTOCOLO.CD_ALERTA_PROTOCOLO = PW_ALERTA_PROTOCOLO.CD_ALERTA_PROTOCOLO
                    ");
    }

    public static function checagem($atendimentoId)
    {
        /*
        TP_CHECAGEM
        -----------
        CC = CONTROLA CHECAGEM
        CA = CHECAGEM AUTOMÁTICA
        NE = NÃO EXIBE HORÁRIO
        NI = NÃO EXIBE ITEM
        */

        return DB::connection('oracle')
                    ->select("
                    SELECT 	CASE
                            WHEN atrasado > 0 THEN 'A' -- ATRASADO
                            WHEN prox_checagem > 0 THEN 'C' -- CHECAGEM PRÓXIMA
                            WHEN feito > 0 THEN 'F' -- TUDO CHECADO
                            ELSE NULL -- NÃO TEM NADA
                            END checagem
                    FROM 	(
                        SELECT 	SUM(CASE atrasado WHEN '1' THEN 1 ELSE 0 END) AS atrasado,
                                SUM(CASE WHEN prox_checagem <= 30 AND atrasado = '0' AND dh_checagem IS NULL THEN 1 ELSE 0 END) prox_checagem,
                                SUM(CASE WHEN atrasado = '0' AND prox_checagem > 30 AND dh_checagem IS NULL THEN 1 ELSE 0 END) feito
                        FROM 	(
                            SELECT 	pre_med.cd_atendimento,
                                    itpre_med.cd_itpre_med,
                                    cd_tip_presc,
                                    (SELECT ds_tip_presc FROM tip_presc WHERE cd_tip_presc = itpre_med.cd_tip_presc) AS ds_tip_presc,
                                    hritpre_med.dh_medicacao,
                                    hritpre_cons.dh_checagem,
                                    CASE WHEN hritpre_med.dh_medicacao < SYSDATE AND hritpre_cons.dh_checagem IS NULL THEN '1' ELSE '0' END AS atrasado,
                                    round((hritpre_med.dh_medicacao - SYSDATE)*1440) AS prox_checagem
                            FROM 	pre_med,
                                        itpre_med,
                                        hritpre_med,
                                        hritpre_cons,
                                        tip_esq
                            WHERE 	pre_med.cd_atendimento 		= $atendimentoId
                            AND 	pre_med.dh_impressao 		IS NOT NULL
                            AND 	pre_med.tp_pre_med 	        = 'M'
                            AND 	pre_med.DT_REFERENCIA 		>= trunc(SYSDATE)
                            AND         tip_esq.tp_checagem             IN ('CC', 'CA')
                            AND 	itpre_med.cd_pre_med 		= pre_med.cd_pre_med
                            AND 	hritpre_med.cd_itpre_med 	= itpre_med.cd_itpre_med
                            AND         tip_esq.cd_tip_esq              = itpre_med.cd_tip_esq
                            AND 	hritpre_med.cd_itpre_med 	= hritpre_cons.cd_itpre_med(+)
                            AND 	hritpre_med.dh_medicacao 	= hritpre_cons.dh_medicacao(+)
                        )
                        GROUP BY cd_atendimento
                    )
                    ");
    }

    public static function afericao($atendimentoId)
    {
            return DB::connection('oracle')
            ->select("SELECT	COUNT(*) AS afericao
                        FROM 	COLETA_SINAL_VITAL C,
                                PW_DOCUMENTO_CLINICO DC
                        WHERE 	C.CD_DOCUMENTO_CLINICO 	= DC.CD_DOCUMENTO_CLINICO
                        AND	DC.TP_STATUS		IN ('FECHADO', 'ASSINADO')
                        AND 	TRUNC(C.DATA_COLETA)	= TRUNC(SYSDATE)
                        AND 	C.CD_ATENDIMENTO	= $atendimentoId");
    }

    public static function aprazamento($atendimentoId)
    {
        return DB::connection('oracle')
                    ->select("
                        SELECT 	CASE
                                WHEN SUM(CASE WHEN hritpre_med.cd_itpre_med IS NULL THEN 1 ELSE 0 END) > 0 AND SUM(CASE WHEN hritpre_med.cd_itpre_med IS NOT NULL THEN 1 ELSE 0 END) = 0 THEN 'V' -- NÃO HÁ APRAZAMENTO
                                WHEN SUM(CASE WHEN hritpre_med.cd_itpre_med IS NULL THEN 1 ELSE 0 END) > 0 THEN 'N' -- FALTANDO APRAZAMENTO
                                WHEN SUM(CASE WHEN hritpre_med.cd_itpre_med IS NOT NULL THEN 1 ELSE 0 END) > 0 THEN 'S' -- APRAZAMENTO OK
                                ELSE NULL END aprazamento
                        FROM 	pre_med,
                                itpre_med,
                                tip_presc,
                                tip_esq,
                                hritpre_med,
                                tip_fre
                        WHERE 	pre_med.cd_atendimento 	        = $atendimentoId
                        AND    	pre_med.cd_pre_med              = itpre_med.cd_pre_med
                        AND 	itpre_med.cd_tip_presc	        = tip_presc.cd_tip_presc
                        AND 	tip_presc.cd_tip_esq	        = tip_esq.cd_tip_esq
                        AND 	itpre_med.cd_itpre_med 	        = hritpre_med.cd_itpre_med(+)
                        AND 	itpre_med.cd_tip_fre	        = tip_fre.cd_tip_fre
                        AND 	trunc(pre_med.dt_validade) 	>= TRUNC(SYSDATE)
                        AND 	(itpre_med.sn_cancelado	IS NULL OR itpre_med.sn_cancelado NOT IN ('S'))
                        AND 	tip_esq.tp_checagem	        = 'CC' -- CC - CONTROLA CHECAGEM
                        AND     tip_presc.sn_solicitacao        = 'S'
                        AND 	tip_fre.tp_controle		NOT IN ('ACM')
                        AND 	tip_presc.cd_tip_esq	        IN ('MDN', 'MDA', 'MDO', 'MDU', 'MED', 'DIC', 'TOS', 'NPD', 'ATB', 'IMT', 'PEP')
                        AND     tip_presc.cd_produto            IS NOT NULL
                        GROUP BY pre_med.cd_atendimento
                    ");
    }

    public static function avaliacaoFarmacia($atendimentoId)
    {
        // return DB::connection('oracle')
        //             ->select("
        //             SELECT  COUNT(*) AS avaliacao--TO_CHAR(Max(PW_DOCUMENTO_CLINICO.DH_FECHAMENTO),'DD/MM/RRRR HH24:MI:SS')
        //             FROM    PW_AVALIACAO_PRE_MED,
        //                     PW_AVALIACAO_ITPRE_MED,
        //                     PW_DOCUMENTO_CLINICO,
        //                     PRE_MED
        //             WHERE   PW_DOCUMENTO_CLINICO.CD_DOCUMENTO_CLINICO 	IS NOT NULL
        //             AND     PW_AVALIACAO_PRE_MED.CD_DOCUMENTO_CLINICO   = PW_DOCUMENTO_CLINICO.CD_DOCUMENTO_CLINICO
        //             AND	    PW_AVALIACAO_PRE_MED.CD_AVALIACAO_PRE_MED 	= PW_AVALIACAO_ITPRE_MED.CD_AVALIACAO_PRE_MED
        //             AND     PW_DOCUMENTO_CLINICO.CD_ATENDIMENTO       	= $atendimentoId
        //             AND     PW_AVALIACAO_PRE_MED.CD_PRE_MED		= PRE_MED.CD_PRE_MED
        //             AND     PRE_MED.CD_ATENDIMENTO			= PW_DOCUMENTO_CLINICO.CD_ATENDIMENTO
        //             AND     PW_DOCUMENTO_CLINICO.TP_STATUS		IN ('FECHADO', 'ASSINADO')
        //             AND     PW_AVALIACAO_PRE_MED.CD_PRE_MED		= (SELECT MAX(CD_PRE_MED) FROM PRE_MED WHERE PRE_MED.CD_ATENDIMENTO = PW_DOCUMENTO_CLINICO.CD_ATENDIMENTO AND PRE_MED.DH_IMPRESSAO IS NOT NULL AND PRE_MED.TP_PRE_MED = 'M' AND TRUNC(DT_PRE_MED) = TRUNC(SYSDATE))
        //             ");
        // return DB::connection('oracle')
        //                 ->select("
        //                 SELECT  COUNT (*) AS AVALIACAO
        //                 FROM    PW_AVALIACAO_PRE_MED,
        //                         DBAMV.PRE_MED PM,
        //                         DBAMV.PW_PRE_MED_AVALIACAO_STATUS
        //                 WHERE    PM.CD_ATENDIMENTO       	            = $atendimentoId
        //                 AND     PW_AVALIACAO_PRE_MED.CD_PRE_MED             = PW_PRE_MED_AVALIACAO_STATUS.CD_PRE_MED
        //                 AND     PM.CD_PRE_MED                               = PW_AVALIACAO_PRE_MED.CD_PRE_MED
        //                 AND     PW_PRE_MED_AVALIACAO_STATUS.TP_STATUS       = 'CON'     
        //                 AND     PM.FL_IMPRESSO                              = 'S'
        //                 AND     PW_AVALIACAO_PRE_MED.CD_PRE_MED             =  (SELECT MAX(CD_PRE_MED) 
        //                                                                         FROM PRE_MED 
        //                                                                         WHERE PRE_MED.CD_ATENDIMENTO = PM.CD_ATENDIMENTO
        //                                                                         AND PRE_MED.DH_IMPRESSAO IS NOT NULL 
        //                                                                         AND PRE_MED.TP_PRE_MED = 'M' 
        //                                                                         AND TRUNC(DT_PRE_MED) = TRUNC(SYSDATE))
        //");
        return DB::connection('oracle')
                        ->select("
                        SELECT  COUNT (*) AS AVALIACAO
                        FROM    PW_AVALIACAO_PRE_MED,
                                DBAMV.PRE_MED PM,
                                DBAMV.PW_PRE_MED_AVALIACAO_STATUS
                        WHERE    PM.CD_ATENDIMENTO       	            = $atendimentoId
                        AND     PW_AVALIACAO_PRE_MED.CD_PRE_MED             = PW_PRE_MED_AVALIACAO_STATUS.CD_PRE_MED
                        AND     PM.CD_PRE_MED                               = PW_AVALIACAO_PRE_MED.CD_PRE_MED
                        AND     PW_PRE_MED_AVALIACAO_STATUS.TP_STATUS       = 'PAR'     
                        AND     PM.FL_IMPRESSO                              = 'S'
                        AND     PW_AVALIACAO_PRE_MED.CD_PRE_MED             IN (SELECT CD_PRE_MED
                                                                                FROM PRE_MED 
                                                                                WHERE PRE_MED.CD_ATENDIMENTO = PM.CD_ATENDIMENTO
                                                                                AND PRE_MED.DH_IMPRESSAO IS NOT NULL 
                                                                                AND PRE_MED.TP_PRE_MED = 'M' 
                                                                                AND TRUNC(DT_PRE_MED) = TRUNC(SYSDATE))
        ");
    }

    public static function dispensacao($atendimentoId)
    {
        return DB::connection('oracle')
                    ->select("
                        SELECT 	COUNT(*) AS DISPENSACAO
                        FROM 	SOLSAI_PRO,
                                MVTO_ESTOQUE,
                                PRE_MED
                        WHERE 	MVTO_ESTOQUE.CD_SOLSAI_PRO      = SOLSAI_PRO.CD_SOLSAI_PRO
                        AND 	PRE_MED.CD_PRE_MED	        = SOLSAI_PRO.CD_PRE_MED
                        AND 	PRE_MED.CD_PRE_MED		= (SELECT MAX(CD_PRE_MED) FROM PRE_MED WHERE PRE_MED.CD_ATENDIMENTO = $atendimentoId AND PRE_MED.DH_IMPRESSAO IS NOT NULL AND tp_pre_med = 'M')
                    ");
    }

    public static function riscoQueda($atendimentoId)
    {
        return DB::connection('oracle')
                    ->select("
                    SELECT 	CD_AVALIACAO
                    FROM 	PAGU_AVALIACAO
                    WHERE 	PAGU_AVALIACAO.CD_AVALIACAO		= (
                                SELECT 	MAX(CD_AVALIACAO)
                                FROM 	PAGU_AVALIACAO A,
                                        PW_DOCUMENTO_CLINICO
                                WHERE 	A.CD_FORMULA 		= 31
                                AND 	A.CD_ATENDIMENTO 	= $atendimentoId
                                AND 	PW_DOCUMENTO_CLINICO.CD_DOCUMENTO_CLINICO = A.CD_DOCUMENTO_CLINICO
                                AND 	PW_DOCUMENTO_CLINICO.TP_STATUS IN ('FECHADO', 'ASSINADO')
                            )
                    AND 	PAGU_AVALIACAO.CD_ATENDIMENTO	= $atendimentoId
                    AND 	(SELECT CD_PAGU_FORMULA_INTERPRETACAO FROM PAGU_FORMULA_INTERPRETACAO I WHERE I.CD_FORMULA = PAGU_AVALIACAO.CD_FORMULA AND PAGU_AVALIACAO.VL_RESULTADO BETWEEN I.VL_INICIAL AND I.VL_FINAL) NOT IN (36)
                    ");
    }

    public static function riscoLpp($atendimentoId)
    {
        return DB::connection('oracle')
                    ->select("
                    SELECT 	CD_AVALIACAO
                    FROM 	PAGU_AVALIACAO
                    WHERE 	PAGU_AVALIACAO.CD_AVALIACAO		= (
                                SELECT 	MAX(CD_AVALIACAO)
                                FROM 	PAGU_AVALIACAO A,
                                        PW_DOCUMENTO_CLINICO
                                WHERE 	A.CD_FORMULA 		= 32
                                AND 	A.CD_ATENDIMENTO 	= $atendimentoId
                                AND 	PW_DOCUMENTO_CLINICO.CD_DOCUMENTO_CLINICO = A.CD_DOCUMENTO_CLINICO
                                AND 	PW_DOCUMENTO_CLINICO.TP_STATUS IN ('FECHADO', 'ASSINADO')
                            )
                    AND 	PAGU_AVALIACAO.CD_ATENDIMENTO	= $atendimentoId
                    AND 	(SELECT CD_PAGU_FORMULA_INTERPRETACAO FROM PAGU_FORMULA_INTERPRETACAO I WHERE I.CD_FORMULA = PAGU_AVALIACAO.CD_FORMULA AND PAGU_AVALIACAO.VL_RESULTADO BETWEEN I.VL_INICIAL AND I.VL_FINAL) NOT IN (43)
                    ");
    }

    public static function precaucao($pacienteId)
    {
        return DB::connection('oracle')
                    ->select("
                    SELECT 	ds_rgb_hexadecimal
                    FROM 	categoria,
                                categoria_paciente
                    WHERE 	categoria.cd_categoria = categoria_paciente.CD_CATEGORIA
                    AND 	categoria_paciente.cd_paciente = $pacienteId
                    ");
    }

    public static function exameLaboratorio($atendimentoId)
    {
        return DB::connection('oracle')
                    ->select("SELECT 	CASE 
                                    WHEN SUM(CASE WHEN ITPED_LAB.SN_REALIZADO = 'S' THEN 1 ELSE 0 END) > 0 THEN 'R'
                                    WHEN SUM(CASE WHEN AMOSTRA.DT_COLETA IS NOT NULL AND ITPED_LAB.SN_REALIZADO IS NULL THEN 1 ELSE 0 END) > 0 THEN 'C'
                                    WHEN SUM(CASE WHEN ITPED_LAB.SN_REALIZADO IS NULL THEN 1 ELSE 0 END) > 0 THEN 'S'
                                    ELSE NULL
                                    END LAB
                            FROM 	DBAMV.AMOSTRA
                                    ,DBAMV.AMOSTRA_EXA_LAB
                                    ,DBAMV.PED_LAB
                                    ,DBAMV.ITPED_LAB
                            WHERE 	AMOSTRA.CD_AMOSTRA 				= AMOSTRA_EXA_LAB.CD_AMOSTRA
                            AND 	AMOSTRA_EXA_LAB.CD_ITPED_LAB 	= ITPED_LAB.CD_ITPED_LAB
                            AND 	ITPED_LAB.CD_PED_LAB 			= PED_LAB.CD_PED_LAB
                            AND 	ITPED_LAB.CD_PED_LAB 			IN (
                                                                        SELECT 	MAX(CD_PED_LAB)
                                                                        FROM 	PED_LAB
                                                                        WHERE 	PED_LAB.CD_ATENDIMENTO = $atendimentoId
                                                                        )");
    }

    public static function exameImagem($atendimentoId)
    {
        return DB::connection('oracle')
                    ->select("SELECT	CASE
                            WHEN (ITPED_RX.SN_REALIZADO = 'N' OR ITPED_RX.SN_REALIZADO IS NULL) THEN 'NR' -- NÃO REALIZADO
                            WHEN ITPED_RX.SN_REALIZADO = 'S' AND ITPED_RX.CD_LAUDO IS NULL THEN 'R' -- REALIZADO (CHECAR SE TEM IMAGEM)
                            WHEN ITPED_RX.SN_REALIZADO = 'S' AND ITPED_RX.CD_LAUDO IS NOT NULL THEN 'L' -- LAUDADO
                            ELSE NULL
                            END AS IMG
                    FROM 	DBAMV.PED_RX,
                            DBAMV.ITPED_RX
                    WHERE 	PED_RX.CD_ATENDIMENTO 	= $atendimentoId
                    AND 	PED_RX.CD_PED_RX 		= (
                                                        SELECT MAX(CD_PED_RX)
                                                        FROM 	PED_RX
                                                        WHERE 	PED_RX.CD_ATENDIMENTO = $atendimentoId
                                                    )
                    AND 	PED_RX.CD_PED_RX 		= ITPED_RX.CD_PED_RX
                    ");
    }

    public static function solicitacaoLeito($atendimentoId)
    {
        return DB::connection('oracle')
                    ->select("  SELECT 	SUM(CASE WHEN TP_SITUACAO IN ('S') THEN 1 ELSE 0 END) AS solicitado,
                                        SUM(CASE WHEN TP_SITUACAO IN ('A') THEN 1 ELSE 0 END) AS atendido
                                FROM 	DBAMV.SOLIC_TRANSFERENCIA_LEITO
                                WHERE 	CD_ATENDIMENTO = $atendimentoId");
    }
}