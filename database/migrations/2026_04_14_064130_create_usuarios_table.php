<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * Crea la tabla de usuarios de la aplicación.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            // Clave primaria personalizada
            $table->id('idUsuario');

            // Datos personales del usuario
            $table->string('nombre', 25);
            $table->string('apellidos', 50);
            $table->string('email')->unique();

            // Contraseña hasheada
            $table->string('passwordHash');

            // Rol del usuario
            $table->string('rol')->default('cliente');

            // Teléfono opcional
            $table->string('telefono', 9)->nullable();

            // Fecha lógica de registro
            $table->date('fechaRegistro')->nullable();

            // Estado de la cuenta
            $table->boolean('estaActivo')->default(true);

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
        Schema::dropIfExists('usuarios');
    }
};
