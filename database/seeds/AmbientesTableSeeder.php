<?php

use Illuminate\Database\Seeder;
use App\Models\Ambiente;

class AmbientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ambientes = [
            ['ds_ambiente' => 'PRODUÇÃO',],
            ['ds_ambiente' => 'HOMOLOGAÇÃO',],
            ['ds_ambiente' => 'DESENVOLVIMENTO',],
        ];

        foreach ($ambientes as $ambiente) {
            Ambiente::create($ambiente);
        }
    }
}
