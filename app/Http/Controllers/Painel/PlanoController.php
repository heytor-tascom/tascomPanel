<?php

namespace App\Http\Controllers\Painel;

use Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plano;
use App\Models\Produto;
use App\Http\Requests\StorePlanoPostRequest;
use App\Models\PlanoProduto;

class PlanoController extends Controller
{
    private $totalPage = 10;
    
    public function index(Request $request)
    {
        $params = $request->only('s');

        if (isset($params['s'])) {
            $planos = Plano::search($params['s']);
        } else {
            $planos = Plano::with(["produtos" => function($query) {
                                $query->with(["produto" => function($query) {
                                    $query->select("id", "nm_produto");
                                }]);
                            }])
                            ->paginate($this->totalPage);
        }
        
        // dd($planos);

        return view('painel.config.plano.index', compact('planos'));
    }

    public function create()
    {
        $produtos = Produto::where('ativo', 1)
                            ->with('ambiente', 'tipoProduto')
                            ->orderBy('nm_produto')
                            ->get();

        return view('painel.config.plano.form', compact('produtos'));
    }

    public function store(StorePlanoPostRequest $request, Plano $plano, PlanoProduto $planoProduto)
    {
       $formData = $request->except("_token", "produto_id");
       $produtos = $request->only("produto_id");

       $formData["created_by"] = Auth::user()->id;
       $formData["updated_by"] = Auth::user()->id;

       $response = $plano->store($formData);

       if ($response) {
           
            foreach($produtos['produto_id'] as $produto) {
                $formData = [
                    "plano_id"      => $response['id'],
                    "produto_id"    => $produto,
                    "created_by"    => Auth::user()->id,
                    "updated_by"    => Auth::user()->id,
                ];

                $planoProduto->store($formData);
            }

            return redirect()
                    ->route('painel.config.plano')
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

    public function edit($id, Plano $plano)
    {
        $formData = $plano->where("id", $id)
                        ->with(["produtos" => function($query) {
                            $query->with("produto")
                                ->select("plano_id", "produto_id");
                        }])
                        ->first();

        $produtos = Produto::where('ativo', 1)
                            ->with('ambiente', 'tipoProduto')
                            ->orderBy('nm_produto')
                            ->get();
                        
        return view('painel.config.plano.form', compact('produtos', 'formData'));
    }

    public function update($planoId, StorePlanoPostRequest $request, Plano $plano, PlanoProduto $planoProduto)
    {
        $planoData = $plano->find($planoId);

        if (isset($planoData->id)) {
            $formData               = $request->except("_token", "produtos");

            //Adiciona o id e o usuário da alteração no array dos dados do formulário
            $formData["id"]         = $planoId;
            $formData["updated_by"] = Auth::user()->id;

            $response = $plano->atualizar($formData);

            if ($response["success"]) {

                //Deleta os produtos do plano
                $planoProduto->where("plano_id", $planoId)
                                ->delete();

                //Adiciona os novos produtos ao plano
                $formProdutosData = $request->only('produto_id');

                foreach($formProdutosData['produto_id'] as $produto) {
                    $formData = [
                        "plano_id"      => $planoId,
                        "produto_id"    => $produto,
                        "created_by"    => Auth::user()->id,
                        "updated_by"    => Auth::user()->id,
                    ];
    
                    $planoProduto->store($formData);
                }

                return redirect()
                    ->route('painel.config.plano.edit', ["id" => $planoId])
                    ->with('success', $response['message']);

            } else {
                return redirect()
                        ->back()
                        ->with('error', $response['message']);
            }
            
        } else {
            return redirect()
                ->back()
                ->with('error', 'Cadastro do plano não encontrado');
        }
    }

    public function destroy($id)
    {
        //
    }
}
