<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            TipoProdutoTableSeeder::class,
            AmbientesTableSeeder::class,
            ProdutoTableSeeder::class,
            ModuloTableSeeder::class,
            PaginaTableSeeder::class
        ]);
    }
}
