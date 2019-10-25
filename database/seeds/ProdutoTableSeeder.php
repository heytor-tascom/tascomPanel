<?php

use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $produtos = [
            [
                'nm_produto' => 'FARMACIA UTI',
                'ds_produto' => 'FARMACIA UTI',
                'tipo_produto_id' => 1,
                'nm_rota' => 'produto.painel.farmacia.uti',
                'nr_ordem' => 1,
                'ambiente' => 'DEV',
                'usuario_id' => 1,
            ],
        ];

        foreach ($produtos as $produto) {
            Produto::create($produto);
        }
    }
}
