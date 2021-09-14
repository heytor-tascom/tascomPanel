<?php

namespace App\Http\Controllers\Home\Produtos\Paineis\Enfermagem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Helpers\PacienteHelpers;

use App\Models\Produto;
use App\Models\MV\Atendimento;
use App\Models\MV\Setor;

class PainelEnfGestaoVistaUti extends Controller
{
    public function index(Request $request)
    {
        
        // $cdSetor = $setorId;//352;//94;
        $produto = Produto::find(12);
        $params = $request->only("setores");
        $filterSetores = [];

        $title = $produto->nm_produto;
        
        $setor   = new Setor;
        $setores = $setor->where("nm_setor", "LIKE", "%UTI%")->select("cd_setor", "nm_setor")->get();

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
                                atendime.dt_prevista_alta,
                                leito.cd_leito,
                                leito.ds_leito,
                                leito.ds_resumo,
                                unid_int.cd_unid_int,
                                unid_int.ds_unid_int,
                                (SELECT nm_setor FROM setor WHERE setor.cd_setor = unid_int.cd_setor) as nm_setor,
                                (
                                    SELECT  count(cd_pre_med)
                                    FROM    pre_med
                                    WHERE   cd_atendimento = atendime.cd_atendimento
                                    AND     dh_impressao IS NOT NULL
                                    AND     tp_pre_med = 'M'
                                    AND     dt_referencia >= trunc(SYSDATE)
                                ) as pre_med
                                ")
                                ->orderBy("leito.ds_resumo")
                                ->get();

        foreach($atendimentos as $key => $atendimento) {
            $checagem       = PacienteHelpers::checagem($atendimento->cd_atendimento);
            $aprazamento    = PacienteHelpers::aprazamento($atendimento->cd_atendimento);
            $avFarmac       = PacienteHelpers::avaliacaoFarmacia($atendimento->cd_atendimento);
            $dispensacao    = PacienteHelpers::dispensacao($atendimento->cd_atendimento);
            $exameLab       = PacienteHelpers::exameLaboratorio($atendimento->cd_atendimento);
            $exameImg       = PacienteHelpers::exameImagem($atendimento->cd_atendimento);
            $leito          = PacienteHelpers::solicitacaoLeito($atendimento->cd_atendimento);

            $atendimentos[$key]->checagem       = isset($checagem[0]->checagem) ? $checagem[0]->checagem : null;
            $atendimentos[$key]->avfarmac       = isset($avFarmac[0]->avaliacao) ? $avFarmac[0]->avaliacao : null;
            $atendimentos[$key]->aprazamento    = isset($aprazamento[0]->aprazamento) ? $aprazamento[0]->aprazamento : null;
            $atendimentos[$key]->dispensacao    = isset($dispensacao[0]->dispensacao) ? $dispensacao[0]->dispensacao : null;
            $atendimentos[$key]->exalab         = isset($exameLab[0]->lab) ? $exameLab[0]->lab : null;
            $atendimentos[$key]->exaimg         = isset($exameImg[0]->img) ? $exameImg[0]->img : null;
            $atendimentos[$key]->leitoSolicitado = isset($leito[0]->solicitado) ? $leito[0]->solicitado : null;
            $atendimentos[$key]->leitoAtendido  = isset($leito[0]->atendido) ? $leito[0]->atendido : null;
        }

        return view('home.produtos.paineis.enfermagem.gestao-a-vista.uti', compact('title', 'atendimentos', 'setores'));
    }
}
