<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ambiente extends Model
{
    public function produtos(){
        return $this->hasMany(Produto::class, 'ambiente_id');
    }
}
