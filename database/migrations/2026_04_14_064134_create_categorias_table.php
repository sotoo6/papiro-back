<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * Crea la tabla de categorías de productos.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            // Clave primaria personalizada
            $table->id('idCategoria');

            // Nombre de la categoría
            $table->string('nombre');

            // Descripción opcional
            $table->text('descripcion')->nullable();

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
        Schema::dropIfExists('categorias');
    }
};
