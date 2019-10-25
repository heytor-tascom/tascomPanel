<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    protected $table        = 'setor';
    protected $connection   = 'oracle';

    public function unidsInt()
    {
        return $this->hasMany(UnidInt::class, "cd_setor", "cd_setor");
    }
}
