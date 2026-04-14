<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * Crea la tabla de detalle de pedido, donde se guardan
     * los productos incluidos en cada pedido.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('detalle_pedido', function (Blueprint $table) {
            // Clave primaria personalizada
            $table->id('idDetallePedido');

            // Relación con el pedido y el producto
            $table->unsignedBigInteger('idPedido');
            $table->unsignedBigInteger('idProducto');

            // Datos de la línea del pedido
            $table->integer('cantidad');
            $table->decimal('precioUnitario', 10, 2);
            $table->decimal('ivaAplicado', 5, 2)->default(0);
            $table->decimal('subtotal', 10, 2);

            // Timestamps automáticos de Laravel
            $table->timestamps();

            // Relación con pedidos
            $table->foreign('idPedido')
                ->references('idPedido')
                ->on('pedidos')
                ->onDelete('cascade');

            // Relación con productos
            $table->foreign('idProducto')
                ->references('idProducto')
                ->on('productos')
                ->onDelete('restrict');
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_pedido');
    }
};
