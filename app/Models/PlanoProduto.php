<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanoProduto extends Model
{
    protected $table = "plano_produto";

    public function plano()
    {
        return $this->belongsTo(Plano::class, "plano_id");
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, "produto_id");
    }
}
