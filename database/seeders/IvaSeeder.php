<?php

namespace Database\Seeders;

use App\Models\Iva;
use Illuminate\Database\Seeder;

class IvaSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     *
     * Inserta los tipos de IVA básicos que utilizará la tienda.
     *
     * @return void
     */
    public function run(): void
    {
        $ivas = [
            ['porcentaje' => 4.00],
            ['porcentaje' => 10.00],
            ['porcentaje' => 21.00],
        ];

        foreach ($ivas as $iva) {
            Iva::create($iva);
        }
    }
}
