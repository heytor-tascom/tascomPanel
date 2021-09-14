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
                'nm_produto'        => 'PAINEL DE CHECAGEM - GESTÃO À VISTA',
                'ds_produto'        => 'Painel para auxíliar a enfermagem nos tempos de checagem',
                'tipo_produto_id'   => 1,
                'ambiente_id'       => 3,
                'nm_rota'           => 'painel.enfermagem.checagem',
                'ds_parametros'     => 'Estoque e Setores',
                'nr_ordem'          => 1,
                'usuario_id'        => 1,
            ],
            [
                'nm_produto'        => 'TRAMITAÇÃO DE MEDICAMENTOS', //FARMÁCIA CENTRAL
                'ds_produto'        => 'Painel para exibir as solicitações para atendimento',
                'tipo_produto_id'   => 1,
                'ambiente_id'       => 3,
                'nm_rota'           => 'painel.farmacia.central',
                'ds_parametros'     => 'Estoque e Setores',
                'nr_ordem'          => 1,
                'usuario_id'        => 1,
                'tempo_atualizacao' => 60000,
            ],
            [
                'nm_produto'        => 'FARMACIA UTI',
                'ds_produto'        => 'Painel para exibir as solicitações para atendimento',
                'tipo_produto_id'   => 1,
                'ambiente_id'       => 1,
                'nm_rota'           => 'painel.farmacia.uti',
                'ds_parametros'     => 'Estoque e Setores',
                'nr_ordem'          => 1,
                'usuario_id'        => 1,
                'tempo_atualizacao' => 60000,
            ],
            [
                'nm_produto'        => 'MONITORAMENTO DA PRESCRIÇÂO ONCOLÓGICA',
                'ds_produto'        => 'Painel para exibir as solicitações para atendimento',
                'tipo_produto_id'   => 1,
                'ambiente_id'       => 3,
                'nm_rota'           => 'painel.farmacia.oncologia',
                'ds_parametros'     => 'Estoque e Setores',
                'nr_ordem'          => 1,
                'usuario_id'        => 1,
                'tempo_atualizacao' => 60000,
            ],
            [
                'nm_produto'        => 'MONITORAMENTO DA PRESCRIÇÂO ONCOLÓGICA',
                'ds_produto'        => 'http://10.0.38.39/tascom/reavaliacao/oncologia.html?setores=48',
                'tipo_produto_id'   => 1,
                'ambiente_id'       => 3,
                'nm_rota'           => 'painel.farmacia.oncologia',
                'ds_parametros'     => 'Estoque e Setores',
                'nr_ordem'          => 1,
                'usuario_id'        => 1,
                'tempo_atualizacao' => 60000,
            ],
            [
                'nm_produto'        => 'MONITORAMENTO DA PRESCRIÇÂO ONCOLÓGICA',
                'ds_produto'        => 'http://10.0.38.39/tascom/reavaliacao/oncologia.html?setores=48&tipoVisualizacao=f6b574aefef4e14e2f5f08ed76fee46e',
                'tipo_produto_id'   => 1,
                'ambiente_id'       => 3,
                'nm_rota'           => 'painel.farmacia.oncologia',
                'ds_parametros'     => 'Estoque e Setores',
                'nr_ordem'          => 1,
                'usuario_id'        => 1,
                'tempo_atualizacao' => 60000,
            ],
            [
                'nm_produto'        => 'ATRASOS NA TRAMITAÇÃO DE MEDICAMENTOS',
                'ds_produto'        => 'http://10.0.38.39/tascom/farmacia-dev/central_atrasadas.php',
                'tipo_produto_id'   => 1,
                'ambiente_id'       => 1,
                'nm_rota'           => 'painel.farmacia.oncologia',
                'ds_parametros'     => 'Estoque e Setores',
                'nr_ordem'          => 1,
                'usuario_id'        => 1,
                'tempo_atualizacao' => 60000,
            ],
            [
                'nm_produto'        => 'PAINEL DE CHECAGEM - GESTOR',
                'ds_produto'        => 'http://10.0.38.39/tascom/farmacia-dev/central_atrasadas.php',
                'tipo_produto_id'   => 1,
                'ambiente_id'       => 3,
                'nm_rota'           => 'painel.enfermagem.checagem.gestor',
                'ds_parametros'     => 'Painel para auxíliar a enfermagem nos tempos de checagem',
                'nr_ordem'          => 1,
                'usuario_id'        => 1,
                'tempo_atualizacao' => 60000,
            ],
            [
                'nm_produto'        => 'PAINEL DE GESTÃO À VISTA',
                'ds_produto'        => 'Painel de resumo dos status do paciente',
                'tipo_produto_id'   => 1,
                'ambiente_id'       => 3,
                'nm_rota'           => 'painel.enfermagem.gesta.vista.setores',
                'ds_parametros'     => 'Setor',
                'nr_ordem'          => 1,
                'usuario_id'        => 1,
                'tempo_atualizacao' => 60000,
            ],
            [
                'nm_produto'        => 'PAINEL DE GESTÃO À VISTA - UTI',
                'ds_produto'        => 'Painel de resumo dos status do paciente',
                'tipo_produto_id'   => 1,
                'ambiente_id'       => 3,
                'nm_rota'           => 'painel.enfermagem.gesta.vista.uti.setores',
                'ds_parametros'     => 'Setor',
                'nr_ordem'          => 1,
                'usuario_id'        => 1,
                'tempo_atualizacao' => 60000,
            ],
        ];

        foreach ($produtos as $produto) {
            Produto::create($produto);
        }
    }
}
