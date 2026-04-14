<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoCategoriaSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     *
     * Inserta las relaciones entre productos y categorías.
     *
     * @return void
     */
    public function run(): void
    {
        $relaciones = [
            [
                'idProducto' => 1,
                'idCategoria' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idProducto' => 2,
                'idCategoria' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idProducto' => 3,
                'idCategoria' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('producto_categoria')->insert($relaciones);
    }
}
