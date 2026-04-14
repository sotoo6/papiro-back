<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     *
     * Inserta algunas categorías iniciales para el catálogo.
     *
     * @return void
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Escritura',
                'descripcion' => 'Productos para escribir y dibujar.',
            ],
            [
                'nombre' => 'Organización',
                'descripcion' => 'Agendas, archivadores y material de organización.',
            ],
            [
                'nombre' => 'Manualidades',
                'descripcion' => 'Materiales para trabajos manuales.',
            ],
            [
                'nombre' => 'Adhesivos',
                'descripcion' => 'Pegamentos, cintas y correctores.',
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
