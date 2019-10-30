<?php

namespace App\Models\MV;

use Illuminate\Database\Eloquent\Model;

class Prestador extends Model
{
    protected $table        = 'prestador';
    protected $connection   = 'oracle';

    public function prescricoes()
    {
        return $this->hasMany(Prescricao::class, "cd_prestador", "cd_prestador");
    }
}
