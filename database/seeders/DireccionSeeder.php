<?php

namespace Database\Seeders;

use App\Models\Direccion;
use Illuminate\Database\Seeder;

class DireccionSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     *
     * Inserta direcciones de prueba para los usuarios.
     *
     * @return void
     */
    public function run(): void
    {
        $direcciones = [
            [
                'idUsuario' => 1,
                'nombreDireccion' => 'Casa',
                'pais' => 'España',
                'provincia' => 'A Coruña',
                'ciudad' => 'A Coruña',
                'codigoPostal' => '15001',
                'calle' => 'Calle Real',
                'numeroPortal' => '15',
                'esPrincipal' => true,
            ],
            [
                'idUsuario' => 1,
                'nombreDireccion' => 'Trabajo',
                'pais' => 'España',
                'provincia' => 'A Coruña',
                'ciudad' => 'A Coruña',
                'codigoPostal' => '15004',
                'calle' => 'Avenida Finisterre',
                'numeroPortal' => '42',
                'esPrincipal' => false,
            ],
            [
                'idUsuario' => 2,
                'nombreDireccion' => 'Oficina',
                'pais' => 'España',
                'provincia' => 'A Coruña',
                'ciudad' => 'A Coruña',
                'codigoPostal' => '15003',
                'calle' => 'Rúa San Andrés',
                'numeroPortal' => '8',
                'esPrincipal' => true,
            ],
        ];

        foreach ($direcciones as $direccion) {
            Direccion::create($direccion);
        }
    }
}
