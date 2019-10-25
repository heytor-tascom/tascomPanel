<?php

namespace App\Http\Controllers\Home\Produtos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produto;

class ProdutoController extends Controller
{
    public function index()
    {

        $produtos = Produto::where('ativo', 1)
                             ->with('tipoProduto')
                             ->get();
        return view('home.produtos.index', compact('produtos'));

    }
}