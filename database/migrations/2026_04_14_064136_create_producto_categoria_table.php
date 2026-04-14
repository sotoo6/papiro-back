<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * Crea la tabla intermedia entre productos y categorías.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('producto_categoria', function (Blueprint $table) {
            // Claves foráneas de la relación N:M
            $table->unsignedBigInteger('idProducto');
            $table->unsignedBigInteger('idCategoria');

            // Timestamps automáticos de Laravel
            $table->timestamps();

            // Clave primaria compuesta
            $table->primary(['idProducto', 'idCategoria']);

            // Relación con productos
            $table->foreign('idProducto')
                ->references('idProducto')
                ->on('productos')
                ->onDelete('cascade');

            // Relación con categorías
            $table->foreign('idCategoria')
                ->references('idCategoria')
                ->on('categorias')
                ->onDelete('cascade');
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_categoria');
    }
};
