<?php

namespace App\Http\Controllers\Home\Produtos\Paineis\Enfermagem;

use DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MV\Atendimento;
use App\Models\MV\ItemPrescricao;
use App\Models\MV\Paciente;
use App\Models\MV\Prescricao;
use App\Models\MV\Setor;

use App\Http\Helpers\AtendimentoHelpers;

class PainelEnfChecagemController extends Controller
{
    public function index()
    {
        $title      = "PAINEL DE CHECAGEM";
        $setores    = Setor::join("unid_int", "unid_int.cd_setor", "=", "setor.cd_setor")
                            ->where("setor.sn_ativo", "S")
                            ->where("unid_int.sn_ativo", "S")
                            ->orderBy("setor.nm_setor")
                            ->select("setor.cd_setor", "setor.nm_setor")
                            ->get();

        $atendimentos  = Atendimento::join("leito", "leito.cd_leito", "=", "atendime.cd_leito")
                                ->join("unid_int", "unid_int.cd_unid_int", "=", "leito.cd_unid_int")
                                ->join("setor", "setor.cd_setor", "=", "unid_int.cd_setor")
                                ->whereNull("atendime.dt_alta")
                                ->where("setor.cd_setor", 107)
                                ->with(["paciente" => function($query) {
                                    $query->select("paciente.cd_paciente", "paciente.nm_paciente", "paciente.nm_social_paciente", "paciente.dt_nascimento");
                                }])
                                ->select("atendime.cd_atendimento", "atendime.cd_paciente", "atendime.dt_atendimento", "setor.nm_setor", "leito.ds_leito")
                                ->get();

        return view("home.produtos.paineis.enfermagem.checagem.index", compact("title", "atendimentos", "setores"));
    }

    public function details($atendimentoId, Request $request)
    {
        $pacienteData = Atendimento::where('cd_atendimento', $atendimentoId)
                                    ->with(['paciente' => function($query) {
                                        $query->select('paciente.cd_paciente', 'paciente.nm_paciente', 'paciente.dt_nascimento')
                                                ->first();
                                    }])
                                    ->select('atendime.cd_atendimento', 'atendime.cd_paciente')
                                    ->first();

        $prescricaoData = Prescricao::where("cd_atendimento", $atendimentoId)
                                    ->where("dt_validade", ">=", date('d/m/Y'))
                                    ->with([
                                        "prestador" => function($query) {
                                            $query->select("cd_prestador", "nm_prestador");
                                        },
                                        "itens" => function($query) {
                                            $query->join("tip_presc", "tip_presc.cd_tip_presc", "=", "itpre_med.cd_tip_presc")
                                                ->whereNotIn('tip_presc.cd_tip_esq', ['EXL', 'EXI'])
                                                ->whereRaw('(SELECT count(*) FROM hritpre_med ipm WHERE cd_itpre_med = itpre_med.cd_itpre_med) > 0')
                                                ->with([
                                                'tipoItemPrescricao' => function($query) {
                                                    $query->select("cd_tip_presc", "ds_tip_presc");
                                                }
                                            ])
                                            ->select("cd_pre_med", "cd_itpre_med", "tip_presc.cd_tip_presc", "sn_cancelado", "dh_cancelado");
                                        }
                                    ])
                                    ->select("pre_med.cd_pre_med", "pre_med.cd_prestador", "pre_med.dt_validade", "pre_med.dh_criacao")
                                    ->get();
                                    
        return view('home.produtos.paineis.enfermagem.checagem.components.detalhes', compact('pacienteData', 'prescricaoData'));
    }

    public static function horariosItemPrescricao($cdItemPrescricao)
    {
        return DB::connection('oracle')
                    ->select("SELECT  hritpre_med.cd_itpre_med,
                                    itpre_med.cd_pre_med,         
                                    tip_presc.cd_tip_presc,
                                    tip_presc.cd_tip_esq,
                                    tip_presc.ds_tip_presc,
                                    TO_CHAR(hritpre_med.dh_medicacao,'DD/MM/RRRR HH24:MI:SS') as dh_medicacao,
                                    TO_CHAR(hritpre_med.dh_medicacao,'DD/MM/RRRR') as dt_medicacao,
                                    TO_CHAR(hritpre_med.dh_medicacao,'HH24:MI') as hr_medicacao,
                                    TO_CHAR(hritpre_cons.dh_medicacao,'DD/MM/RRRR HH24:MI:SS') as dh_aprazado,
                                    TO_CHAR(hritpre_cons.dh_checagem,'DD/MM/RRRR HH24:MI:SS') as dh_checagem,
                                    hritpre_cons.sn_suspenso,
                                    hritpre_cons.ds_justificativa,
                                    (
                                    SELECT  TO_CHAR(Max(DH_MODIFICACAO),'DD/MM/RRRR HH24:MI:SS')
                                    FROM    LOG_HRITPRE_MED
                                    WHERE   CD_ITPRE_MED      = ITPRE_MED.CD_ITPRE_MED
                                    AND     DH_MEDICACAO_NOVA = hritpre_cons.DH_MEDICACAO
                                    AND     TP_ACAO           NOT IN ('IC', 'DC', 'UC')
                                    ) DH_MODIFICACAO,
                                    (
                                    SELECT  TO_CHAR(Max(DH_MEDICACAO),'DD/MM/RRRR HH24:MI:SS')
                                    FROM    LOG_HRITPRE_MED
                                    WHERE   CD_ITPRE_MED      = ITPRE_MED.CD_ITPRE_MED
                                    AND     DH_MEDICACAO_NOVA = hritpre_cons.DH_MEDICACAO
                                    AND     TP_ACAO           NOT IN ('IC', 'DC', 'UC')
                                    ) DH_MEDICACAO_ANTERIOR,
                                    CASE
                                    WHEN dh_checagem IS NULL THEN round((hritpre_med.dh_medicacao - SYSDATE)*1440)
                                    ELSE NULL
                                    END
                                    AS tempo_prox_minutos,
                                    (
                                    SELECT  TO_CHAR(Max(PW_DOCUMENTO_CLINICO.DH_FECHAMENTO),'DD/MM/RRRR HH24:MI:SS')
                                    FROM    PW_AVALIACAO_PRE_MED,
                                            PW_DOCUMENTO_CLINICO 
                                    WHERE   PW_DOCUMENTO_CLINICO.CD_DOCUMENTO_CLINICO IS NOT NULL
                                    AND     PW_AVALIACAO_PRE_MED.CD_DOCUMENTO_CLINICO = PW_DOCUMENTO_CLINICO.CD_DOCUMENTO_CLINICO
                                    AND     PW_AVALIACAO_PRE_MED.CD_PRE_MED           = pre_med.cd_pre_med
                                    ) AS dh_avaliacao,
                                    hritpre_cons.nm_usuario as nm_usuario_checagem,
                                    (
                                        SELECT 	TO_CHAR(MAX(MVTO_ESTOQUE.HR_MVTO_ESTOQUE),'DD/MM/RRRR HH24:MI:SS')
                                        FROM 	HRITSOLSAI_PRO,
                                                ITSOLSAI_PRO,
                                                MVTO_ESTOQUE
                                        WHERE 	HRITSOLSAI_PRO.CD_ITPRE_MED     = HRITPRE_MED.CD_ITPRE_MED
                                        AND		HRITSOLSAI_PRO.DH_MEDICACAO     = HRITPRE_MED.DH_MEDICACAO
                                        AND		HRITSOLSAI_PRO.CD_ITSOLSAI_PRO  = ITSOLSAI_PRO.CD_ITSOLSAI_PRO
                                        AND		MVTO_ESTOQUE.CD_SOLSAI_PRO      = ITSOLSAI_PRO.CD_SOLSAI_PRO
                                    ) AS dh_mvto_estoque
                            FROM    atendime,
                                    pre_med,
                                    itpre_med,      
                                    hritpre_med,
                                    hritpre_cons, 
                                    tip_presc
                            WHERE   atendime.cd_atendimento         = pre_med.cd_atendimento
                            AND     pre_med.cd_pre_med              = itpre_med.cd_pre_med
                            AND     tip_presc.cd_tip_presc          = itpre_med.cd_tip_presc     
                            AND     itpre_med.cd_itpre_med          = hritpre_med.cd_itpre_med
                            AND     hritpre_cons.cd_itpre_med(+)    = hritpre_med.cd_itpre_med
                            AND     hritpre_cons.dh_medicacao(+)    = hritpre_med.dh_medicacao
                            AND     itpre_med.cd_itpre_med          = ?
                            ORDER BY hritpre_med.dh_medicacao ASC", [$cdItemPrescricao]);
    }
}
