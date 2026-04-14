<?php

namespace Database\Seeders;

use App\Models\CestaProducto;
use Illuminate\Database\Seeder;

class CestaProductoSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     *
     * Inserta líneas de productos dentro de la cesta.
     *
     * @return void
     */
    public function run(): void
    {
        $cestaProductos = [
            [
                'idCesta' => 1,
                'idProducto' => 1,
                'cantidad' => 1,
                'precioUnitario' => 10.99,
            ],
            [
                'idCesta' => 1,
                'idProducto' => 2,
                'cantidad' => 1,
                'precioUnitario' => 3.99,
            ],
        ];

        foreach ($cestaProductos as $cestaProducto) {
            CestaProducto::create($cestaProducto);
        }
    }
}
