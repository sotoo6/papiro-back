<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * Crea la tabla de productos de la tienda.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            // Clave primaria personalizada
            $table->id('idProducto');

            // Relación con el tipo de IVA
            $table->unsignedBigInteger('idIva');

            // Datos principales del producto
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->integer('stock')->default(0);

            // Ruta o nombre de la imagen del producto
            $table->string('imagen')->nullable();

            // Otros datos del producto
            $table->decimal('descuento', 5, 2)->default(0);
            $table->string('marca')->nullable();
            $table->string('proveedor')->nullable();

            // Timestamps automáticos de Laravel
            $table->timestamps();

            // Relación con la tabla de IVA
            $table->foreign('idIva')
                ->references('idIva')
                ->on('ivas')
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
        Schema::dropIfExists('productos');
    }
};
