<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Licenca extends Model
{
    protected $table = "licenca";

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, "cliente_id");
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class, "plano_id");
    }
}
