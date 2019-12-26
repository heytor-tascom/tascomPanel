<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class PlanoProduto extends Model
{
    protected $table = "plano_produto";
    protected $guarded = ["id", "created_at"];

    public function plano()
    {
        return $this->belongsTo(Plano::class, "plano_id");
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, "produto_id");
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
                "message"   => "Produto vinculado ao plano cadastrado com sucesso!"
            ];

        } else {
            DB::rollBack();

            return [
                "success"   => false,
                "message"   => "Ocorreu um erro ao vincular o produto ao plano."
            ];
        }
    }
}
