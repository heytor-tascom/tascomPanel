<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteProduto extends Model
{
    protected $table = "cliente_produto";

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, "cliente_id");
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, "produto_id");
    }
}
