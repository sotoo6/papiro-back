<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * Crea la tabla de pedidos de la tienda.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            // Clave primaria personalizada
            $table->id('idPedido');

            // Relación con el usuario que realiza el pedido
            $table->unsignedBigInteger('idUsuario');

            // Datos principales del pedido
            $table->date('fechaPedido')->nullable();
            $table->string('estado');
            $table->string('metodoPago');
            $table->string('metodoEntrega');
            $table->decimal('totalPedido', 10, 2);
            $table->decimal('descuento', 5, 2)->default(0);

            // Dirección de envío guardada dentro del pedido
            $table->string('paisEnvio');
            $table->string('provinciaEnvio');
            $table->string('ciudadEnvio');
            $table->string('codigoPostalEnvio');
            $table->string('calleEnvio');
            $table->string('numeroEnvio');

            // Timestamps automáticos de Laravel
            $table->timestamps();

            // Relación con usuarios
            $table->foreign('idUsuario')
                ->references('idUsuario')
                ->on('usuarios')
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
        Schema::dropIfExists('pedidos');
    }
};
