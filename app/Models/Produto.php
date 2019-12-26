<?php

namespace App\Models;

use DB;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produto';
    protected $guarded = ['id', 'created_at'];

    public function tipoProduto()
    {
        return $this->belongsTo(TipoProduto::class, 'tipo_produto_id');
    }

    public function ambiente()
    {
        return $this->belongsTo(Ambiente::class, 'ambiente_id');
    }

    public function produtosCliente()
    {
        return $this->hasMany(ClienteProduto::class, 'produto_id');
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
                "message"   => "Produto cadastrado com sucesso!"
            ];

        } else {
            DB::rollBack();

            return [
                "success"   => false,
                "message"   => "Ocorreu um erro ao cadastrar o produto."
            ];
        }
    }

    public function atualizar(Array $formData)
    {
        DB::beginTransaction();

        $produto = $this->find($formData['id']);

        foreach ($produto->original as $key => $value) {

            switch ($key) {
                case 'id':
                case 'created_at':
                case 'usuario_id':
                case 'created_by':
                break;
                
                default:
                    $produto[$key] = isset($formData[$key]) ? $formData[$key] : null;
                break;
            }

        }

        $response = $produto->save();

        if ($response) {
            DB::commit();

            return [
                "success"   => true,
                "message"   => "Produto atualizado com sucesso!"
            ];

        } else {
            DB::rollBack();

            return [
                "success"   => false,
                "message"   => "Ocorreu um erro ao atualizar o produto."
            ];
        }
    }

    public static function search(String $search, $totalPage = 10)
    {
        return Produto::orWhere("nm_produto", "LIKE", "%$search%")
                        ->orWhere("ds_produto", "LIKE", "%$search%")
                        ->orWhere("id", "LIKE", "%$search%")
                        ->with("ambiente")
                        ->paginate($totalPage);
    }
}
