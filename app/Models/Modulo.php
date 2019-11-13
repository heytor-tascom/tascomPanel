<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulo';

    public function paginas()
    {
        return $this->hasMany(Pagina::class, "modulo_id");
    }
}
