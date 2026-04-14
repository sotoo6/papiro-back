<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     *
     * Inserta usuarios de prueba con distintos roles.
     *
     * @return void
     */
    public function run(): void
    {
        $usuarios = [
            [
                'nombre' => 'Lucía',
                'apellidos' => 'Soto',
                'email' => 'lucia@email.com',
                'passwordHash' => Hash::make('12345678'),
                'rol' => 'cliente',
                'telefono' => '600123123',
                'fechaRegistro' => now()->toDateString(),
                'estaActivo' => true,
            ],
            [
                'nombre' => 'Admin',
                'apellidos' => 'Papiro',
                'email' => 'admin@papiro.com',
                'passwordHash' => Hash::make('admin1234'),
                'rol' => 'admin',
                'telefono' => '611111111',
                'fechaRegistro' => now()->toDateString(),
                'estaActivo' => true,
            ],
            [
                'nombre' => 'Super',
                'apellidos' => 'Admin',
                'email' => 'superadmin@papiro.com',
                'passwordHash' => Hash::make('super1234'),
                'rol' => 'superadmin',
                'telefono' => '622222222',
                'fechaRegistro' => now()->toDateString(),
                'estaActivo' => true,
            ],
        ];

        foreach ($usuarios as $usuario) {
            Usuario::create($usuario);
        }
    }
}
