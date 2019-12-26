<?php

namespace App\Http\Controllers\Painel;

use Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Helpers\Helpers;

use App\Models\Ambiente;
use App\Models\Produto;
use App\Models\TipoProduto;

use App\Http\Requests\StoreProdutoPostRequest;

class ProdutoController extends Controller
{
    private $totalPage = 10;
    
    public function index(Request $request)
    {
        $params = $request->only('s');

        if (isset($params['s'])) {
            $produtos = Produto::search($params['s']);
        } else {
            $produtos = Produto::with("ambiente", "tipoProduto")
                                ->paginate($this->totalPage);
        }
        
        return view('painel.config.produto.index', compact('produtos'));
    }

    public function create()
    {
        $ambientes      = Ambiente::where("ativo", 1)
                            ->get();
        $tiposProduto   = TipoProduto::where("ativo", 1)
                            ->get();

        return view('painel.config.produto.form', compact("ambientes", "tiposProduto"));
    }

    public function store(StoreProdutoPostRequest $request, Produto $produto)
    {
        $formData = $request->except('_token');

        $nmRota   = "";

        switch ($formData['tipo_produto_id']) {
            case 1:
                $nmRota .= "painel.";

                $nmPainel = explode(" ", $formData['nm_produto']);

                if (strtolower($nmPainel[0]) == "painel") {
                    unset($nmPainel[0]);
                }
                
                foreach ($nmPainel as $key => $name) {
                    $nmRota .= Helpers::retirarAcentos($name);
                }

                break;
            
            default:
                # code...
            break;
        }

        $formData["nm_rota"]        = strtolower($nmRota);
        $formData["ds_parametros"]  = "";
        $formData["nr_ordem"]       = 100;
        $formData["usuario_id"]     = Auth::user()->id;

        $response = $produto->store($formData);

        if ($response) {
            return redirect()
                ->route('painel.config.produto')
                ->with('success', $response['message']);
        } else {
            return redirect()
                ->back()
                ->with('error', $response['message']);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $formData       = Produto::where("id", $id)
                            ->with("ambiente", "tipoProduto")
                            ->first();
        
        $ambientes      = Ambiente::where("ativo", 1)
                            ->get();
        
        $tiposProduto   = TipoProduto::where("ativo", 1)
                            ->get();

        return view('painel.config.produto.form', compact("formData", "ambientes", "tiposProduto"));
    }

    public function update($produtoId, StoreProdutoPostRequest $request, Produto $produto)
    {
        $formData = $request->except('_token');

        $nmRota   = "";

        switch ($formData['tipo_produto_id']) {
            case 1:
                $nmRota .= "painel.";

                $nmPainel = explode(" ", $formData['nm_produto']);

                if (strtolower($nmPainel[0]) == "painel") {
                    unset($nmPainel[0]);
                }

                $nmPainel = array_values($nmPainel);
                
                foreach ($nmPainel as $key => $name) {
                    $nmRota .= Helpers::retirarAcentos($name);

                    if ($key != count($nmPainel) - 1) {
                        $nmRota .= ".";
                    }
                }

                break;
            
            default:
                # code...
            break;
        }

        $formData["nm_rota"]        = strtolower($nmRota);
        $formData["ds_parametros"]  = "";
        $formData["nr_ordem"]       = 100;
        $formData['id']             = $produtoId;
        // $formData['updated_by']     = Auth::user()->id;
        $formData['ativo']          = (isset($formData['ativo'])) ? 1 : 0;

        $response = $produto->atualizar($formData);

        if ($response) {
            return redirect()
                ->route('painel.config.produto')
                ->with('success', $response['message']);
        } else {
            return redirect()
                ->back()
                ->with('error', $response['message']);
        }
    }

    public function destroy($id)
    {
        //
    }
}
