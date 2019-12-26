<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdutoPostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nm_produto'        => 'required|min:5',
            'ds_produto'        => 'required|min:5',
            'tipo_produto_id'   => 'required',
            'ambiente_id'       => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nm_produto.required'       => 'Digite o nome do produto',
            'nm_produto.min'            => 'O nome do produto deve conter no mínimo 5 caracteres',
            'ds_produto.required'       => 'Digite sobre o que se trata o produto',
            'ds_produto.min'            => 'A descrição do produto deve conter no mínimo 5 caracteres',
            'tipo_produto_id.required'  => 'Selecione o tipo do produto',
            'ambiente_id.required'      => 'Selecione o ambiente do produto',
        ];
    }
}
