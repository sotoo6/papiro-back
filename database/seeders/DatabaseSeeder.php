<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta todos los seeders principales.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            IvaSeeder::class,
            CategoriaSeeder::class,
            UsuarioSeeder::class,
            ProductoSeeder::class,
            ProductoCategoriaSeeder::class,
            DireccionSeeder::class,
            CestaSeeder::class,
            CestaProductoSeeder::class,
        ]);
    }
}
