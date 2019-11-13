<?php

namespace App\Http\Controllers\Painel;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

use App\Http\Requests\StoreClientePost;

class ClienteController extends Controller
{
    private $totalPage = 10;

    public function index(Request $request)
    {
        $params = $request->only('s');

        if (isset($params['s']))
            $clientes = Cliente::search($params['s']);
        $clientes = Cliente::paginate($this->totalPage);
        
        return view('painel.config.cliente.index', compact('clientes'));
    }

    public function create()
    {
        return view('painel.config.cliente.form');   
    }

    public function store(StoreClientePost $request, Cliente $cliente)
    {
        $clienteData = $request->except('_token');
        $clienteData["created_by"] = Auth::user()->id;
        $clienteData["updated_by"] = Auth::user()->id;

        $response = $cliente->store($clienteData);

        if ($response) {
            return redirect()
                ->route('painel.config.cliente')
                ->with('success', $response['message']);
        } else {
            return redirect()
                ->back()
                ->with('error', $response['message']);
        }
    }

    public function view($clienteId, Request $request, Cliente $cliente)
    {
        $formData = $cliente->where("id", $clienteId)->with('licencas')->first();

        if ($formData->id)
            return view('painel.config.cliente.form', compact('formData'));
        return redirect()
            ->back()
            ->with('error', 'Cliente não encontrado');
    }

    public function update($clienteId, StoreClientePost $request, Cliente $cliente)
    {
        $clienteData = $cliente->find($clienteId); 

        if (!isset($clienteData->id))
            return redirect()
                    ->back()
                    ->with('error', 'Cliente não encontrado');

                    
        $formData               = $request->except("_token");
        $formData['id']         = $clienteId;
        $formData['updated_by'] = Auth::user()->id;
        $formData['ativo']      = (isset($formData['ativo'])) ? 1 : 0;

        $response = $cliente->atualizar($formData);

        if ($response) {
            return redirect()
                ->route('painel.config.cliente')
                ->with('success', $response['message']);
        } else {
            return redirect()
                ->back()
                ->with('error', $response['message']);
        }
    }
}
