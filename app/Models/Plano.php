<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    protected $table = "plano";

    public function licencas()
    {
        return $this->hasMany(Licenca::class, "plano_id");
    }
}
