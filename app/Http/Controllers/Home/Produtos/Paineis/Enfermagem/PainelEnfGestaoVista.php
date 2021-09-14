<?php

namespace App\Http\Controllers\Home\Produtos\Paineis\Enfermagem;

use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Produto;
use App\Models\MV\Atendimento;
use App\Models\MV\Setor;

use App\Http\Helpers\PacienteHelpers;

class PainelEnfGestaoVista extends Controller
{
    public function index($setorId = null, Request $request)
    {
        
        // $cdSetor = $setorId;//352;//94;
        $produto = Produto::find(11);
        $params = $request->only("setores");
        $filterSetores = [];

        $title = $produto->nm_produto;

        $setor   = new Setor;
        $setores = $setor->getByTipo(['P']);
        
        if (isset($params['setores'])) {
            $filterSetores = $params['setores'];
            $filterSetores = explode(',', $filterSetores);
        } else {
            return view('home.produtos.paineis.enfermagem.gestao-a-vista.index', compact('title', 'setores'));
        }

        $atendimentos = Atendimento::join("paciente", "paciente.cd_paciente", "=", "atendime.cd_paciente")
                                ->join("leito", "leito.cd_leito", "=", "atendime.cd_leito")
                                ->join("unid_int", "unid_int.cd_unid_int", "=", "leito.cd_unid_int")
                                ->whereNull("atendime.dt_alta")
                                ->whereIn("unid_int.cd_setor", $filterSetores)
                                ->with(["paciente" => function($query) {
                                    $query->select("cd_paciente", "nm_paciente", "dt_nascimento");
                                }, "prestador" => function($query) {
                                    $query->select("cd_prestador", "nm_prestador");
                                }])
                                ->selectRaw("
                                atendime.cd_atendimento,
                                atendime.cd_paciente,
                                atendime.cd_prestador,
                                atendime.dt_atendimento,
                                atendime.dt_alta,
                                CASE WHEN atendime.dt_alta_medica IS NOT NULL THEN TO_CHAR(atendime.dt_alta_medica, 'DD/MM/YYYY') || ' ' || TO_CHAR(atendime.hr_alta_medica, 'HH24:MI:SS') ELSE NULL END AS dt_alta_medica,
                                CASE
                                WHEN DT_ALTA_MEDICA IS NOT NULL THEN ROUND((SYSDATE - TO_DATE(TO_CHAR(DT_ALTA_MEDICA, 'DD/MM/YYYY') || ' ' || TO_CHAR(HR_ALTA_MEDICA, 'HH24:MI:SS'), 'DD/MM/YYYY HH24:MI:SS'))*1440,2)
                                ELSE NULL END AS tempo_alta,
                                atendime.dt_prevista_alta,
                                atendime.dt_alta_medica,
                                leito.cd_leito,
                                leito.ds_leito,
                                leito.ds_resumo,
                                unid_int.cd_unid_int,
                                unid_int.ds_unid_int,
                                (SELECT nm_setor FROM setor WHERE setor.cd_setor = unid_int.cd_setor) as nm_setor,
                                (
                                    SELECT 	COUNT(*) AS TOTAL
                                    FROM 	DBAMV.PW_DOCUMENTO_CLINICO DC,
                                            DBAMV.PW_EDITOR_CLINICO EC
                                    WHERE	EC.CD_DOCUMENTO_CLINICO = DC.CD_DOCUMENTO_CLINICO
                                    AND   	TRUNC(DC.DH_FECHAMENTO) = TRUNC(SYSDATE)
                                    AND 	DC.TP_STATUS 			IN ('FECHADO', 'ASSINADO')
                                    AND 	EC.CD_DOCUMENTO 		IN (599, 1106) -- 1106: Evolução Médica, 599: Plano Terapêutico
                                    AND 	DC.CD_ATENDIMENTO 		= atendime.cd_atendimento
                                    GROUP BY DC.CD_ATENDIMENTO
                                ) as evo_med,
                                (
                                    SELECT 	SUM(TOTAL)
                                    FROM 	(
                                        SELECT 	COUNT(*) AS TOTAL
                                        FROM 	PW_DOCUMENTO_CLINICO
                                        WHERE 	CD_ATENDIMENTO 			= atendime.cd_atendimento
                                        AND 	CD_OBJETO 				IN (444, 449, 450, 454)
                                        AND   	TRUNC(DH_FECHAMENTO) 	= TRUNC(SYSDATE)
                                        AND 	TP_STATUS 				IN ('FECHADO', 'ASSINADO')
                                        GROUP BY CD_ATENDIMENTO
                                        
                                        UNION ALL
                                        
                                        SELECT 	COUNT(*) AS TOTAL
                                        FROM 	DBAMV.PW_DOCUMENTO_CLINICO DC,
                                                DBAMV.PW_EDITOR_CLINICO EC
                                        WHERE	EC.CD_DOCUMENTO_CLINICO = DC.CD_DOCUMENTO_CLINICO
                                        AND   	TRUNC(DC.DH_FECHAMENTO) = TRUNC(SYSDATE)
                                        AND 	DC.TP_STATUS 			IN ('FECHADO', 'ASSINADO')
                                        AND 	EC.CD_DOCUMENTO 		IN (598) -- 699: Evolução Enfermagem, 598: Evolução Nefro
                                        AND 	DC.CD_ATENDIMENTO 		= atendime.cd_atendimento
                                        GROUP BY DC.CD_ATENDIMENTO
                                    )
                                ) as evo_enf,
                                (
                                    SELECT  count(cd_pre_med)
                                    FROM    pre_med
                                    WHERE   cd_atendimento = atendime.cd_atendimento
                                    AND     dh_impressao IS NOT NULL
                                    AND     tp_pre_med = 'M'
                                    AND     dt_referencia >= trunc(SYSDATE)
                                ) as pre_med,
                                (
                                    SELECT 	count(*) AS total
                                    FROM 	PW_PROBLEMA,
                                            PW_ALERGIA_PAC,
                                            SUBSTANCIA
                                    WHERE 	CD_PACIENTE = atendime.cd_paciente
                                    AND 	PW_PROBLEMA.CD_PROBLEMA = PW_ALERGIA_PAC.CD_PROBLEMA
                                    AND 	PW_ALERGIA_PAC.CD_SUBSTANCIA = SUBSTANCIA.CD_SUBSTANCIA(+)
                                    AND 	DS_OBSERVACAO NOT LIKE RTRIM(LTRIM('NEGA%'))
                                ) as alergia,
                                (
                                    null
                                ) as aprazamento,
                                null as checagem,
                                null as precaucao,
                                null as rq,
                                null as rlpp,
                                (
                                    SELECT count(*) FROM par_med WHERE cd_atendimento = atendime.cd_atendimento AND ds_situacao = 'Solicitado'
                                ) as parecer
                                ")
                                ->orderBy("leito.ds_resumo")
                                ->get();

        foreach($atendimentos as $key => $atendimento) {
            $checagem       = self::checagem($atendimento->cd_atendimento);
            $aprazamento    = self::aprazamento($atendimento->cd_atendimento);
            $avaliacaoFarm  = PacienteHelpers::avaliacaoFarmacia($atendimento->cd_atendimento);
            $dispensacao    = PacienteHelpers::dispensacao($atendimento->cd_atendimento);
            $pavci          = self::protocolo($atendimento->cd_atendimento, 4);
            $psepse         = self::protocolo($atendimento->cd_atendimento, 5);
            $psepseped      = self::protocolo($atendimento->cd_atendimento, 6);
            $ptev           = self::protocolo($atendimento->cd_atendimento, 2);
            $ptevcir        = self::protocolo($atendimento->cd_atendimento, 13);
            $pqueda         = self::protocolo($atendimento->cd_atendimento, 10);
            $pbronco        = self::protocolo($atendimento->cd_atendimento, 3);
            $pbronconeoped  = self::protocolo($atendimento->cd_atendimento, 14);
            $precaucao      = self::precaucao($atendimento->cd_paciente);
            $exameLab       = self::exameLaboratorio($atendimento->cd_atendimento);
            $exameImagem    = self::exameImagem($atendimento->cd_atendimento);
            $rq             = self::riscoQueda($atendimento->cd_atendimento);
            $rlpp           = self::riscoLpp($atendimento->cd_atendimento);

            $atendimentos[$key]->checagem       = isset($checagem[0]->checagem) ? $checagem[0]->checagem : null;
            $atendimentos[$key]->aprazamento    = isset($aprazamento[0]->aprazamento) ? $aprazamento[0]->aprazamento : null;
            $atendimentos[$key]->avfarmac       = isset($avaliacaoFarm[0]->avaliacao) ? $avaliacaoFarm[0]->avaliacao : null;
            $atendimentos[$key]->dispensacao    = isset($dispensacao[0]->dispensacao) ? $dispensacao[0]->dispensacao : null;
            $atendimentos[$key]->pavci          = isset($pavci[0]->cd_etapa_protocolo) ? $pavci[0] : null;
            $atendimentos[$key]->psepse         = isset($psepse[0]->cd_etapa_protocolo) ? $psepse[0] : null;
            $atendimentos[$key]->psepseped      = isset($psepseped[0]->cd_etapa_protocolo) ? $psepseped[0] : null;
            $atendimentos[$key]->ptev           = isset($ptev[0]->cd_etapa_protocolo) ? $ptev[0] : null;
            $atendimentos[$key]->ptevcir        = isset($ptevcir[0]->cd_etapa_protocolo) ? $ptevcir[0] : null;
            $atendimentos[$key]->pqueda         = isset($pqueda[0]->cd_etapa_protocolo) ? $pqueda[0] : null;
            $atendimentos[$key]->pbronco        = isset($pbronco[0]->cd_etapa_protocolo) ? $pbronco[0] : null;
            $atendimentos[$key]->pbronconeoped  = isset($pbronconeoped[0]->cd_etapa_protocolo) ? $pbronconeoped[0] : null;
            $atendimentos[$key]->precaucao      = isset($precaucao[0]->ds_rgb_hexadecimal) ? $precaucao[0]->ds_rgb_hexadecimal : null;
            $atendimentos[$key]->exalab         = isset($exameLab[0]->lab) ? $exameLab[0]->lab : null;
            $atendimentos[$key]->exaimg         = isset($exameImagem[0]->img) ? $exameImagem[0]->img : null;
            $atendimentos[$key]->rq             = isset($rq[0]->cd_avaliacao) ? $rq[0]->cd_avaliacao : null;
            $atendimentos[$key]->rlpp           = isset($rlpp[0]->cd_avaliacao) ? $rlpp[0]->cd_avaliacao : null;
        }

        return view('home.produtos.paineis.enfermagem.gestao-a-vista.index', compact('title', 'atendimentos', 'setores', 'setorId'));
    }

    public function protocolo($atendimentoId, $cdAlertaProtocolo)
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

    public function checagem($atendimentoId)
    {
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
                                    hritpre_cons
                            WHERE 	pre_med.cd_atendimento 		= $atendimentoId
                            AND 	pre_med.dh_impressao 		IS NOT NULL
                            AND 	pre_med.tp_pre_med 			= 'M'
                            AND 	pre_med.DT_REFERENCIA 		>= trunc(SYSDATE)
                            AND 	itpre_med.cd_pre_med 		= pre_med.cd_pre_med
                            AND 	hritpre_med.cd_itpre_med 	= itpre_med.cd_itpre_med
                            AND 	hritpre_med.cd_itpre_med 	= hritpre_cons.cd_itpre_med(+)
                            AND 	hritpre_med.dh_medicacao 	= hritpre_cons.dh_medicacao(+)
                        )
                        GROUP BY cd_atendimento
                    )
                    ");
    }

    public function aprazamento($atendimentoId)
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
                            hritpre_med
                    WHERE 	pre_med.cd_atendimento 	= $atendimentoId
                    AND    	pre_med.cd_pre_med		= itpre_med.cd_pre_med
                    AND 	itpre_med.cd_tip_presc	= tip_presc.cd_tip_presc
                    AND 	tip_presc.cd_tip_esq	= tip_esq.cd_tip_esq
                    AND 	itpre_med.cd_itpre_med 	= hritpre_med.cd_itpre_med(+)
                    AND 	trunc(pre_med.dt_validade) 	>= TRUNC(SYSDATE)
                    AND 	(itpre_med.sn_cancelado	IS NULL OR itpre_med.sn_cancelado NOT IN ('S'))
                    AND 	tip_esq.tp_checagem		= 'CC' -- CC - CONTROLA CHECAGEM
                    AND 	tip_presc.cd_tip_esq	IN ('MDN', 'MDA', 'MDO', 'MDU', 'MED', 'DIC', 'TOS', 'NPD', 'ATB', 'IMT', 'PEP')
                    AND     tip_presc.cd_produto    IS NOT NULL
                    GROUP BY pre_med.cd_atendimento
                    ");
    }

    public function riscoQueda($atendimentoId)
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

    public function riscoLpp($atendimentoId)
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

    public function precaucao($pacienteId)
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

    public function exameLaboratorio($atendimentoId)
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

    public function exameImagem($atendimentoId)
    {
        return DB::connection('oracle')
                    ->select("  SELECT  CASE
                                        WHEN Count(CASE WHEN ITPED_RX.SN_REALIZADO = 'N' THEN 1 ELSE 0 END) > 0 THEN 'NR' -- NÃO REALIZADO
                                        WHEN Count(CASE WHEN ITPED_RX.SN_REALIZADO = 'S' AND ITPED_RX.CD_LAUDO IS NULL THEN 1 ELSE 0 END) > 0 THEN 'R' -- REALIZADO (CHECAR SE TEM IMAGEM)
                                        WHEN Count(CASE WHEN ITPED_RX.SN_REALIZADO = 'S' AND ITPED_RX.CD_LAUDO IS NOT NULL THEN 1 ELSE 0 END) > 0 THEN 'L' -- LAUDADO
                                        END AS IMG
                                FROM 	DBAMV.PED_RX,
                                        DBAMV.ITPED_RX
                                WHERE 	PED_RX.CD_ATENDIMENTO 	= $atendimentoId
                                AND 	PED_RX.CD_PED_RX 		= ITPED_RX.CD_PED_RX
                    ");
    }
}
