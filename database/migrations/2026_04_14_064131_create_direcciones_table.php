<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * Crea la tabla de direcciones asociadas a los usuarios.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('direcciones', function (Blueprint $table) {
            // Clave primaria personalizada
            $table->id('idDireccion');

            // Clave foránea del usuario propietario de la dirección
            $table->unsignedBigInteger('idUsuario');

            // Nombre identificativo de la dirección: Casa, Trabajo, etc.
            $table->string('nombreDireccion');

            // Datos de la dirección
            $table->string('pais');
            $table->string('provincia');
            $table->string('ciudad');
            $table->string('codigoPostal');
            $table->string('calle');
            $table->string('numeroPortal');

            // Indica si esta es la dirección principal del usuario
            $table->boolean('esPrincipal')->default(false);

            // Campos automáticos de Laravel
            $table->timestamps();

            // Relación con la tabla usuarios
            $table->foreign('idUsuario')
                ->references('idUsuario')
                ->on('usuarios')
                ->onDelete('cascade');
        });
    }

    /**
     * Revierte la migración.
     *
     * Elimina la tabla de direcciones.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('direcciones');
    }
};
