<?php

namespace App\Http\Controllers\Home\Produtos;

use App\Http\Controllers\Controller;
use App\Models\Ambiente;
use Illuminate\Http\Request;
use App\Models\Produto;

class ProdutoController extends Controller
{
    public function index()
    {
        $ambientes = Ambiente::where('ativo', 1)
                             ->with(['produtos' => function($query) {
                                 $query->with('tipoProduto');
                             }])
                             ->get();
                             

        return view('home.produtos.index', compact('ambientes'));

    }

    public function update(Request $request)
    {
        $dados = $request->all();

            Produto::where('id', $dados['id'])
                    ->update(['nm_produto' => $dados['nm_produto'], 'tempo_atualizacao' => $dados['tempo_atualizacao']]); 

        return self::index();

    }
}