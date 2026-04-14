<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * Crea la tabla intermedia entre cestas y productos.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('cesta_producto', function (Blueprint $table) {
            // Clave primaria personalizada
            $table->id('idCestaProducto');

            // Relación con la cesta y el producto
            $table->unsignedBigInteger('idCesta');
            $table->unsignedBigInteger('idProducto');

            // Datos de la línea de cesta
            $table->integer('cantidad');
            $table->decimal('precioUnitario', 10, 2);

            // Timestamps automáticos de Laravel
            $table->timestamps();

            // Relación con cestas
            $table->foreign('idCesta')
                ->references('idCesta')
                ->on('cestas')
                ->onDelete('cascade');

            // Relación con productos
            $table->foreign('idProducto')
                ->references('idProducto')
                ->on('productos')
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
        Schema::dropIfExists('cesta_producto');
    }
};
