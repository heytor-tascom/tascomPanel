<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientePost extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "nm_cliente"    => "required|min:5",
            "nr_cpf_cnpj"   => "required|min:11|max:14|unique:cliente,nr_cpf_cnpj,$this->id",
        ];
    }

    public function messages()
    {
        return [
            "nm_cliente.required"   => "Você precisa digitar o nome do cliente",
            "nm_cliente.min"        => "O nome do cliente deve conter no mínimo 5 caracteres",
            "nr_cpf_cnpj.required"  => "Você precisa digitar o CPF/CNPJ do cliente",
            "nr_cpf_cnpj.min"       => "O número do CPF/CNPJ do cliente é menor que 11 caracteres",
            "nr_cpf_cnpj.max"       => "O número do CPF/CNPJ do cliente é menor que 14 caracteres",
            "nr_cpf_cnpj.unique"    => "Já existe um número do CPF/CNPJ igual a este",
        ];
    }
}
