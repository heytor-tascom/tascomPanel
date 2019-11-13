<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = "cliente";
    protected $guarded = ["id", "created_at"];

    public function licencas()
    {
        return $this->hasMany(Licenca::class, "cliente_id");
    }

    public function produtos()
    {
        return $this->hasMany(ClienteProduto::class, "cliente_id");
    }

    public function store(Array $formData)
    {
        DB::beginTransaction();

        $response = $this->create($formData);

        if ($response) {
            DB::commit();

            return [
                "id"        => $response->id,
                "success"   => true,
                "message"   => "Cliente cadastrado com sucesso!"
            ];

        } else {
            DB::rollBack();

            return [
                "success"   => false,
                "message"   => "Ocorreu um erro ao cadastrar o cliente."
            ];
        }
    }

    public function atualizar(Array $formData)
    {
        DB::beginTransaction();

        $cliente = $this->find($formData['id']);

        foreach ($cliente->original as $key => $value) {

            switch ($key) {
                case 'id':
                case 'created_at':
                case 'created_by':
                break;
                
                default:
                    $cliente[$key] = isset($formData[$key]) ? $formData[$key] : null;
                break;
            }

        }

        $response = $cliente->save();

        if ($response) {
            DB::commit();

            return [
                "success"   => true,
                "message"   => "Cliente atualizado com sucesso!"
            ];

        } else {
            DB::rollBack();

            return [
                "success"   => false,
                "message"   => "Ocorreu um erro ao atualizar o cliente."
            ];
        }
    }

    public static function search(String $search, $totalPage = 10)
    {
        return Cliente::where('nm_cliente', 'LIKE', "%$search%")
                    ->paginate($totalPage);
    }
}
