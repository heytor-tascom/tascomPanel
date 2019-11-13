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
                'nm_produto' => 'PAINEL DE CHECAGEM',
                'ds_produto' => 'Painel para auxíliar a enfermagem nos tempos de checagem',
                'tipo_produto_id' => 1,
                'ambiente_id' => 3,
                'nm_rota' => 'painel.enfermagem.checagem',
                'ds_parametros' => 'Estoque e Setores',
                'nr_ordem' => 1,
                'usuario_id' => 1,
            ],
            [
                'nm_produto' => 'FARMÁCIA CENTRAL',
                'ds_produto' => 'Painel para exibir as solicitações para atendimento',
                'tipo_produto_id' => 1,
                'ambiente_id' => 3,
                'nm_rota' => 'painel.farmacia.central',
                'ds_parametros' => 'Estoque e Setores',
                'nr_ordem' => 1,
                'usuario_id' => 1,
                'tempo_atualizacao' => 60000,
            ],
            [
                'nm_produto' => 'FARMACIA UTI',
                'ds_produto' => 'Painel para exibir as solicitações para atendimento',
                'tipo_produto_id' => 1,
                'ambiente_id' => 3,
                'nm_rota' => 'painel.farmacia.uti',
                'ds_parametros' => 'Estoque e Setores',
                'nr_ordem' => 1,
                'usuario_id' => 1,
                'tempo_atualizacao' => 60000,
            ],
            [
                'nm_produto' => 'FARMACIA ONCOLOGIA',
                'ds_produto' => 'Painel para exibir as solicitações para atendimento',
                'tipo_produto_id' => 1,
                'ambiente_id' => 3,
                'nm_rota' => 'painel.farmacia.oncologia',
                'ds_parametros' => 'Estoque e Setores',
                'nr_ordem' => 1,
                'usuario_id' => 1,
                'tempo_atualizacao' => 60000,
            ],
        ];

        foreach ($produtos as $produto) {
            Produto::create($produto);
        }
    }
}
