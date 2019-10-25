<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produto';

    public function tipoProduto(){
        return $this->belongsTo(TipoProduto::class, 'tipo_produto_id');
    }
}