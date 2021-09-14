<?php

namespace App\Http\Controllers\Home\Produtos\Paineis\Urgencia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Helpers\PacienteHelpers;

use App\Models\MV\Atendimento;
use App\Models\MV\ItemPrescricao;
use App\Models\MV\Paciente;
use App\Models\MV\Prescricao;
use App\Models\MV\Setor;

class PainelReavaliacaoController extends Controller
{
    public function index($setorId = null, Request $request)
    {
        // $produto            = Produto::find(1);
        $title              = "PAINEL URGÊNCIA";
        $filterSetores      = [];
        $tipoVisualizacao   = null;
        // $refreshTime        = $produto->tempo_atualizacao;

        if (is_null($setorId)) {
            $setor   = new Setor;
            $setores = $setor->getByGrupo(['U']);

            return view('home.produtos.paineis.urgencia.reavaliacao.setores', compact('title', 'setores'));
        }        

        $atendimentos = Atendimento::join("setor", "setor.cd_multi_empresa", "=", "atendime.cd_multi_empresa")
                                    ->join("ori_ate", function ($join) {
                                        $join->on("ori_ate.cd_ori_ate", "=", "atendime.cd_ori_ate")
                                        ->on("ori_ate.cd_setor", "=", "setor.cd_setor");
                                    })
                                    ->join("especialid", "especialid.cd_especialid", "=", "atendime.cd_especialid")
                                    ->where("atendime.tp_atendimento", "U")
                                    ->whereNull("atendime.dt_alta")
                                    ->whereRaw("atendime.dt_atendimento >= SYSDATE - 1")
                                    ->where("setor.cd_setor", "=", $setorId)
                                    ->with(["paciente" => function($query) {
                                        $query->select("paciente.cd_paciente", "paciente.nm_paciente");
                                    }])
                                    ->select("atendime.cd_atendimento", "atendime.cd_paciente", "atendime.hr_atendimento", "especialid.ds_especialid")
                                    ->get();
        
        foreach($atendimentos as $key => $atendimento) {
            $afericao                       = PacienteHelpers::afericao($atendimento->cd_atendimento);
            $checagem                       = PacienteHelpers::checagem($atendimento->cd_atendimento);
            $exameLab                       = PacienteHelpers::exameLaboratorio($atendimento->cd_atendimento);
            $exameImg                       = PacienteHelpers::exameImagem($atendimento->cd_atendimento);
            
            $atendimentos[$key]->afericao   = isset($afericao[0]->afericao) ? $afericao[0]->afericao : null;
            $atendimentos[$key]->checagem   = isset($checagem[0]->checagem) ? $checagem[0]->checagem : null;
            $atendimentos[$key]->exalab     = isset($exameLab[0]->lab) ? $exameLab[0]->lab : null;
            $atendimentos[$key]->exaimg     = isset($exameImg[0]->img) ? $exameImg[0]->img : null;
        }

        // dd($atendimentos);
        
        return view("home.produtos.paineis.urgencia.reavaliacao.index", compact("title", "atendimentos"));
    }
}
