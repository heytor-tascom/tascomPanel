<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanoPostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "nm_plano" => "required|min:3|unique:plano,nm_plano,".$this->id,
            "vl_plano" => "required|numeric",
            "nr_periodo" => "required|numeric",
            "produto_id" => "required",
        ];
    }

    public function messages()
    {
        return [
            "nm_plano.required"     => "Por favor, digite um nome para o plano.",
            "nm_plano.min"          => "O nome do plano deve conter no mínimo 3 caracteres.",
            "nm_plano.unique"       => "Já existe um plano com este nome. Por favor, escolha outro.",
            "vl_plano.required"     => "Digite um valor para o plano.",
            "vl_plano.numeric"      => "Formato inválido.",
            "nr_periodo.required"   => "Informe o número de dias para duração do plano.",
            "nr_periodo.numeric"    => "Formato inválido.",
            "produto_id.required"   => "Selecione ao menos um produto para o plano.",
        ]
        ;
    }
}
