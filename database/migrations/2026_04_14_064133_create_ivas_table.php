<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * Crea la tabla de tipos de IVA.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('ivas', function (Blueprint $table) {
            // Clave primaria personalizada
            $table->id('idIva');

            // Porcentaje de IVA
            $table->decimal('porcentaje', 5, 2);

            // Timestamps automáticos de Laravel
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('ivas');
    }
};
