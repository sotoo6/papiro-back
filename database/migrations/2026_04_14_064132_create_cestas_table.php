<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * Crea la tabla de cestas de compra.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('cestas', function (Blueprint $table) {
            // Clave primaria personalizada
            $table->id('idCesta');

            // Cada usuario solo puede tener una cesta activa asociada
            $table->unsignedBigInteger('idUsuario')->unique();

            // Fecha lógica de creación de la cesta
            $table->date('fechaCreacion')->nullable();

            // Estado de la cesta
            $table->string('estado')->default('activa');

            // Total acumulado de la cesta
            $table->decimal('totalCesta', 10, 2)->default(0);

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
        Schema::dropIfExists('cestas');
    }
};
