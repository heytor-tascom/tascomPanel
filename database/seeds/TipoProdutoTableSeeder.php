<?php

use Illuminate\Database\Seeder;
use App\Models\TipoProduto;

class TipoProdutoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipo_produtos = [
            [
                'nm_tipo_produto' => 'PAINEL',
            ],
        ];

        foreach ($tipo_produtos as $tipo_produto) {
            TipoProduto::create($tipo_produto);
        }
    }
}
