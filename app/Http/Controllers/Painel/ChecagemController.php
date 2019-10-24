<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Atendimento;
use App\Models\Paciente;

use App\Http\Helpers\AtendimentoHelpers;

class ChecagemController extends Controller
{
    public function index()
    {
        $title      = "PAINEL DE CHECAGEM";
        $pacientes  = Paciente::where("cd_paciente", 1187335)
                                ->with(["atendimentos" => function($query) {
                                    $query->with("convenio");
                                }])
                                // ->select('cd_paciente')
                                ->first();

        return view("painel.enfermagem.checagem.index", compact("title", "pacientes"));
    }
}
