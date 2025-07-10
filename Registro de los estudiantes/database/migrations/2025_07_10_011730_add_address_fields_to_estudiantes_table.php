<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->string('direccion')->nullable()->after('telefono');
            $table->string('ciudad')->nullable()->after('direccion');
            $table->string('estado_direccion')->nullable()->after('ciudad');
            $table->string('codigo_postal', 10)->nullable()->after('estado_direccion');
            
            // Cambiar el nombre de la columna estado para evitar conflicto
            $table->renameColumn('estado', 'estado_estudiante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropColumn(['direccion', 'ciudad', 'estado_direccion', 'codigo_postal']);
            $table->renameColumn('estado_estudiante', 'estado');
        });
    }
};
