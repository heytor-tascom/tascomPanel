<?php

use Illuminate\Database\Seeder;
use App\Models\Pagina;

class PaginaTableSeeder extends Seeder
{
    public function run()
    {
        $paginas = [
            ["nm_pagina" => "Clientes", 'modulo_id' => 1, 'nm_rota' => 'painel.config.cliente', 'nr_ordem' => 10],
            ["nm_pagina" => "Produtos", 'modulo_id' => 1, 'nm_rota' => 'painel.config.produto', 'nr_ordem' => 20],
            ["nm_pagina" => "Planos", 'modulo_id' => 1, 'nm_rota' => 'painel.config.plano', 'nr_ordem' => 30],
            ["nm_pagina" => "Licenças", 'modulo_id' => 1, 'nm_rota' => 'painel.config.licenca', 'nr_ordem' => 40],
        ];

        foreach ($paginas as $pagina) {
            Pagina::create($pagina);
        }
    }
}
