<?php

use Illuminate\Database\Seeder;
use App\Models\Painel;

class PainelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paineis = [
            [
                'nm_painel' => 'FARMACIA UTI',
                'ds_painel' => 'FARMACIA UTI',
                'nm_rota' => 'painel.farmacia.uti',
                'nr_ordem' => 1,
                'ambiente' => 'DEV',
                'usuario_id' => 1,
            ],
        ];

        foreach ($paineis as $painel) {
            Painel::create($painel);
        }
    }
}
