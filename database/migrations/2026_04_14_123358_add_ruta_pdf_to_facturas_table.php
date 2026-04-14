<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * Añade la ruta del PDF a la tabla facturas.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            // Ruta donde se guardará el PDF de la factura.
            $table->string('rutaPdf')->nullable()->after('numeroFactura');
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn('rutaPdf');
        });
    }
};
