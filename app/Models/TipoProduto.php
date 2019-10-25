<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoProduto extends Model
{
    protected $table = 'tipo_produto';

    public function produtos(){
        return $this->hasMany(Produto::class, 'tipo_produto_id');
    }
}
