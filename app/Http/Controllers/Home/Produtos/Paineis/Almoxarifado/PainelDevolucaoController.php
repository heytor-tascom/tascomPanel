<?php

namespace App\Http\Controllers\Home\Produtos\Paineis\Almoxarifado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers\AlmoxarifadoHelpers;

use App\Models\Produto;

class PainelDevolucaoController extends Controller
{
    public function index(Request $request)
    {
        $produto  = Produto::find(16);
        $title    = $produto->nm_produto;
        $tempoAtt = $produto->tempo_atualizacao;

        $solicitacoes = AlmoxarifadoHelpers::devolucoes();

        // dd($solicitacoes);

        return view('home.produtos.paineis.almoxarifado.devolucoes.index', compact('title', 'tempoAtt', 'solicitacoes'));
    }
}
