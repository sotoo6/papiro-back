<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     *
     * Inserta productos de prueba para el catálogo.
     *
     * @return void
     */
    public function run(): void
    {
        $productos = [
            [
                'idIva' => 3,
                'nombre' => 'Agenda espiral',
                'descripcion' => 'Agenda espiral para organización diaria.',
                'precio' => 10.99,
                'stock' => 25,
                'imagen' => 'productos/producto1.png',
                'descuento' => 0,
                'marca' => 'Liderpapel',
                'proveedor' => 'Proveedor A',
            ],
            [
                'idIva' => 3,
                'nombre' => 'Block cartulinas',
                'descripcion' => 'Block de cartulinas de colores surtidos.',
                'precio' => 3.99,
                'stock' => 40,
                'imagen' => 'productos/producto2.png',
                'descuento' => 0,
                'marca' => 'Liderpapel',
                'proveedor' => 'Proveedor B',
            ],
            [
                'idIva' => 3,
                'nombre' => 'Pegamento Pritt',
                'descripcion' => 'Pegamento transparente en barra.',
                'precio' => 1.29,
                'stock' => 60,
                'imagen' => 'productos/producto3.png',
                'descuento' => 0,
                'marca' => 'Pritt',
                'proveedor' => 'Proveedor C',
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
