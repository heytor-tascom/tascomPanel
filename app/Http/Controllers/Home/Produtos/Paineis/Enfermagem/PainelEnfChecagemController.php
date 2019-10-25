<?php

namespace App\Http\Controllers\Home\Produtos\Paineis\Enfermagem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Atendimento;
use App\Models\Paciente;
use App\Models\Setor;

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
}
