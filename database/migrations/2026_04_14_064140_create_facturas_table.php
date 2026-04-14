<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * Crea la tabla de facturas asociadas a pedidos.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            // Clave primaria personalizada
            $table->id('idFactura');

            // Relación 1:1 con pedido
            $table->unsignedBigInteger('idPedido')->unique();

            // Datos de la factura
            $table->date('fechaEmision')->nullable();
            $table->string('numeroFactura')->unique();

            // Timestamps automáticos de Laravel
            $table->timestamps();

            // Relación con pedidos
            $table->foreign('idPedido')
                ->references('idPedido')
                ->on('pedidos')
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
        Schema::dropIfExists('facturas');
    }
};
