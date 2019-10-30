<?php

namespace App\Http\Controllers;
use App\Models\Ambiente;
use App\Models\Produto;

class HomeController extends Controller
{
   
    public function __construct()
    {
      //   $this->middleware('auth');
    }

    public function index()
    {
        $paineis = Ambiente::where('ativo', 1)
                             ->with(['produtos' => function($query){
                                  $query->where('ativo', 1)
                                        ->where('tipo_produto_id', 1)
                                        ->orderBy('nr_ordem');
                             }])->get();

        return view('home.produtos.index', compact('paineis'));
    }
}
