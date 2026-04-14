<?php

namespace Database\Seeders;

use App\Models\Cesta;
use Illuminate\Database\Seeder;

class CestaSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     *
     * Inserta una cesta de prueba para el usuario cliente.
     *
     * @return void
     */
    public function run(): void
    {
        $cestas = [
            [
                'idUsuario' => 1,
                'fechaCreacion' => now()->toDateString(),
                'estado' => 'activa',
                'totalCesta' => 14.98,
            ],
        ];

        foreach ($cestas as $cesta) {
            Cesta::create($cesta);
        }
    }
}
