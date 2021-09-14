<?php

namespace App\Http\Controllers\Home\Produtos\Paineis\Farmacia\Oncologia;

use DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Produto;
use App\Models\MV\Atendimento;
use App\Models\MV\Estoque;
use App\Models\MV\Prescricao;
use App\Models\MV\Setor;

use App\Http\Controllers\Home\Produtos\Paineis\Enfermagem\PainelEnfChecagemController;

class PainelFarmOncoSMLController extends Controller
{
    public function index(Request $request){

        $filterSetores = [];
        $estoques      = [];
        $tipoVisualizacao = null;

        $params   = $request->only('setores', 'estoque', 'tipoVisualizacao');

        if (isset($params['tipoVisualizacao']) && $params['tipoVisualizacao'] == 'd8375b48751caf44e5c23d4b0dcc2984d6081784') {
            $tipoVisualizacao = $params['tipoVisualizacao'];
            $produtoId        = 7;
        } else {
            $produtoId        = 6;
        }

        $produto  = Produto::find($produtoId);

        $title    = $produto->nm_produto;
        $tempoAtt = $produto->tempo_atualizacao;

        if (isset($params['setores'])) {
            $filterSetores = $params['setores'];
            $filterSetores = explode(',', $filterSetores);
            $estoques      = Estoque::join('config_estoque', 'config_estoque.cd_estoque', '=', 'estoque.cd_estoque')
                                    ->join('setor', 'setor.cd_setor', '=', 'config_estoque.cd_setor')
                                    ->whereIn('setor.cd_setor', $filterSetores)
                                    ->selectRaw('DISTINCT estoque.cd_estoque as cd_estoque, ds_estoque')
                                    ->orderBy('estoque.ds_estoque')
                                    ->get();
        }

        $setor = new Setor;
        $setores = $setor->getByTipo(['P']);

        $solicitacoes = self::getSolicitacoes($filterSetores);

        foreach($solicitacoes as $key => $solicitacao) {
            $statusPaciente = PainelEnfChecagemController::getStatusPacientes($solicitacao->atendimento);
            $solicitacoes[$key]->med_status = isset($statusPaciente[0]) ? $statusPaciente[0] : null;
        }

       //dd($solicitacoes);

        return view("home.produtos.paineis.farmacia.oncologia.indexSML", compact('title', 'solicitacoes', 'setores', 'estoques', 'tempoAtt', 'tipoVisualizacao'));
    }

    public function details($atendimentoId, $preMedId, $solSaiProId, Request $request)
    {
        $pacienteData = Atendimento::where('cd_atendimento', $atendimentoId)
                                    ->with(['paciente' => function($query) {
                                        $query->select('paciente.cd_paciente', 'paciente.nm_paciente', 'paciente.dt_nascimento')
                                                ->first();
                                    }])
                                    ->select('atendime.cd_atendimento', 'atendime.cd_paciente')
                                    ->first();

        $pacienteData->cd_pre_med     = $preMedId;
        $pacienteData->cd_solsai_pro  = $solSaiProId;

        $prescricaoData = Prescricao::where("cd_pre_med", $preMedId)
                                    ->with([
                                        "prestador" => function($query) {
                                            $query->select("cd_prestador", "nm_prestador");
                                        },
                                        "itens" => function($query) {
                                            $query->join("tip_presc", "tip_presc.cd_tip_presc", "=", "itpre_med.cd_tip_presc")
                                                ->join("tip_fre", "tip_fre.cd_tip_fre", "=", "itpre_med.cd_tip_fre")
                                                ->whereNotIn('tip_presc.cd_tip_esq', ['EXL', 'EXI'])
                                                ->whereRaw('(SELECT count(*) FROM hritpre_med ipm WHERE cd_itpre_med = itpre_med.cd_itpre_med) > 0')
                                                ->with([
                                                    'tipoItemPrescricao' => function($query) {
                                                        $query->select("cd_tip_presc", "ds_tip_presc");
                                                    }
                                                ])
                                                ->select("cd_pre_med", "cd_itpre_med", "tip_presc.cd_tip_presc", "tip_fre.ds_tip_fre", "sn_cancelado", "dh_cancelado", "sn_urgente");

                                            }
                                    ])
                                    ->select("pre_med.cd_pre_med", "pre_med.cd_prestador", "pre_med.dt_validade", "pre_med.dh_criacao", DB::raw("(SELECT count(*) FROM itpre_med, hritpre_med WHERE itpre_med.cd_pre_med = pre_med.cd_pre_med AND itpre_med.cd_itpre_med = hritpre_med.cd_itpre_med AND itpre_med.sn_urgente = 'S') as itens_urgente"))
                                    ->get();

        return view('home.produtos.paineis.farmacia.oncologia.components.detalhes', compact('pacienteData', 'prescricaoData'));
    }

    public static function getSolicitacoes($parSetores)
    {
        $setores = "'".implode("','",array_filter($parSetores))."'";

        return DB::connection('oracle')
                    ->select("SELECT
                    DISTINCT CD_PRE_MED,
                      CD_SOLSAI_PRO,
                      FNC_ITPREMED,
                      ATENDIMENTO,
                      DT_NASCIMENTO,
                      DT_ATENDIMENTO,
                      COD_PACIENTE,
                      NOME_PACIENTE,
                      LEITO,
                      SETOR,
                      IDADE,
                      NOME_SOCIAL,
                      NM_SETOR,
                      STATUS,
                      ALTA
                     FROM (
                     SELECT
                     DBAMV.FNCDES_PAINEL_VAL_ITPRE_MED(ITPRE_MED.CD_PRE_MED) FNC_ITPREMED,
                     (
                     CASE
                      WHEN DBAMV.FNCDES_PAINEL_STATUS_ONCOLOGIA(ATE.CD_ATENDIMENTO,ITPRE_MED.CD_PRE_MED,SOLSAI_PRO.CD_SOLSAI_PRO,5) IS NOT NULL THEN 'RECEB'
                      WHEN DBAMV.FNCDES_PAINEL_STATUS_ONCOLOGIA(ATE.CD_ATENDIMENTO,ITPRE_MED.CD_PRE_MED,SOLSAI_PRO.CD_SOLSAI_PRO,4) IS NOT NULL THEN 'MED_PREP'
                      WHEN DBAMV.FNCDES_PAINEL_STATUS_ONCOLOGIA(ATE.CD_ATENDIMENTO,ITPRE_MED.CD_PRE_MED,SOLSAI_PRO.CD_SOLSAI_PRO,3) IS NOT NULL THEN 'BAI_PED'
                      WHEN DBAMV.FNCDES_PAINEL_STATUS_ONCOLOGIA(ATE.CD_ATENDIMENTO,ITPRE_MED.CD_PRE_MED,SOLSAI_PRO.CD_SOLSAI_PRO,2) IS NOT NULL THEN 'VALID_FARM'
                      WHEN DBAMV.FNCDES_PAINEL_STATUS_ONCOLOGIA(ATE.CD_ATENDIMENTO,ITPRE_MED.CD_PRE_MED,SOLSAI_PRO.CD_SOLSAI_PRO,1) IS NOT NULL THEN 'PUNCAO'
                      ELSE 'SEM STATUS'
                      END
                     ) AS STATUS,
                        SOLSAI_PRO.CD_SETOR AS SETOR,
                        NVL(L.DS_LEITO,(DECODE(ATE.TP_ATENDIMENTO,'A','AMBULATORIAL','E','EXTERNO'))) AS LEITO,
                        PAC.CD_PACIENTE AS COD_PACIENTE,
                        ATE.CD_ATENDIMENTO AS ATENDIMENTO,
                        PAC.NM_PACIENTE AS NOME_PACIENTE,
                        TO_CHAR(PAC.DT_NASCIMENTO,'DD/MM/RRRR') AS DT_NASCIMENTO,
                        TO_CHAR(ATE.DT_ATENDIMENTO,'DD/MM/RRRR') AS DT_ATENDIMENTO,
                        Upper(DBAMV.fn_idade(PAC.DT_NASCIMENTO, 'a A')) AS IDADE,
                        Decode(PAC.NM_SOCIAL_PACIENTE,'N','-',NVL(PAC.NM_SOCIAL_PACIENTE,'-')) AS NOME_SOCIAL,
                        ITPRE_MED.CD_PRE_MED CD_PRE_MED,
                        SOLSAI_PRO.CD_SOLSAI_PRO,
                        SETOR.NM_SETOR NM_SETOR,
                        DECODE(ATE.DT_ALTA, NULL, 0, 1) ALTA
                    FROM
                      ITPRE_MED,
                      SOLSAI_PRO,
                      PACIENTE PAC,
                      ATENDIME ATE,
                      LEITO L,
                      SETOR
                    WHERE ITPRE_MED.CD_TIP_ESQ IN ('ONC','IMT')
                    AND   SOLSAI_PRO.CD_SETOR = SETOR.CD_SETOR
                    AND   SETOR.CD_SETOR IN (100,97)
                    AND   SOLSAI_PRO.CD_PRE_MED = ITPRE_MED.CD_PRE_MED
                    AND   ATE.CD_ATENDIMENTO = SOLSAI_PRO.CD_ATENDIMENTO
                    AND   PAC.CD_PACIENTE = ATE.CD_PACIENTE
                    AND   L.CD_LEITO(+) = ATE.CD_LEITO
                    AND   SOLSAI_PRO.HR_SOLSAI_PRO >= SYSDATE - 12/24
                    AND   (ATE.DT_ALTA >=  SYSDATE - 12/24 OR ATE.DT_ALTA IS NULL)
                    AND   ATE.DT_ATENDIMENTO >= SYSDATE - 1
                    --and   ATE.CD_ATENDIMENTO = 1032662
                    )
                    ORDER BY ALTA ASC, FNC_ITPREMED DESC ,SETOR, LEITO ASC ");
    }

    public static function getStatusPrescricaoOncologia($cdPreMed, $cdSolSaiPro)
    {
        return DB::connection('oracle')
                    ->select("SELECT
                            DISTINCT SSP.CD_SOLSAI_PRO SOLICITACAO,
                            (SELECT NVL(DBAMV.FNCDES_PAINEL_STATUS_ONCOLOGIA(ATE.CD_ATENDIMENTO,:cdPreMed,SSP.CD_SOLSAI_PRO,1),'NÃO AVALIADO') FROM DUAL) AS PUNCAO,
                            (SELECT NVL(DBAMV.FNCDES_PAINEL_STATUS_ONCOLOGIA(ATE.CD_ATENDIMENTO,:cdPreMed,SSP.CD_SOLSAI_PRO,4),'NÃO AVALIADO') FROM DUAL) AS MED_PREP,
                            PAC.NM_PACIENTE NOME_PACIENTE,
                            PAC.CD_PACIENTE CODIGO_PACIENTE,
                            ATE.CD_ATENDIMENTO ATENDIMENTO,
                            (SELECT DECODE(TP_STATUS, 'CON','CONCLUÍDA','PAR', 'PARCIAL', 'ANA', 'EM ANÁLISE')
                            FROM PW_PRE_MED_AVALIACAO_STATUS WHERE CD_PRE_MED = :cdPreMed) TP_STATUS_AVAL,
                            (SELECT NM_PRESTADOR FROM PRESTADOR WHERE CD_PRESTADOR = (SELECT CD_PRESTADOR FROM PRE_MED WHERE CD_PRE_MED = SSP.CD_PRE_MED)) PRESTADOR
                        FROM
                            PACIENTE PAC,
                            ATENDIME ATE,
                            LEITO L,
                            SOLSAI_PRO SSP
                        WHERE SSP.CD_ATENDIMENTO = ATE.CD_ATENDIMENTO
                        AND   ATE.CD_PACIENTE    = PAC.CD_PACIENTE
                        AND   L.CD_LEITO(+)      = ATE.CD_LEITO
                        AND   SSP.CD_SOLSAI_PRO  = :cdSolSaiPro
                        ORDER BY SSP.CD_SOLSAI_PRO ASC
                        ", [":cdPreMed" => $cdPreMed, ":cdSolSaiPro" => $cdSolSaiPro]);
    }
}
