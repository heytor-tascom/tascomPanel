<?php

use Illuminate\Database\Seeder;
use App\Models\Modulo;

class ModuloTableSeeder extends Seeder
{
    public function run()
    {
        $modulos = [
            ["nm_modulo" => "Configurações", 'ds_icone' => 'fas fa-cogs', 'nr_ordem' => 10]
        ];

        foreach ($modulos as $modulo) {
            Modulo::create($modulo);
        }
    }
}
