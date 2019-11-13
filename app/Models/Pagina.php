<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagina extends Model
{
    protected $table = 'pagina';

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }
}
