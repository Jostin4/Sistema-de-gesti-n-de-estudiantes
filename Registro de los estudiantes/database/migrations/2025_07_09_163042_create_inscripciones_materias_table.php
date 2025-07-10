<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inscripciones_materias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('semestre_id')->constrained('semestres')->onDelete('cascade');
            $table->foreignId('seccion_id')->constrained('secciones')->onDelete('cascade');
            $table->enum('estado', ['inscrito', 'aprobado', 'reprobado', 'retirado'])->default('inscrito');
            $table->decimal('nota_final', 3, 2)->nullable();
            $table->timestamps();
            
            // Un estudiante no puede inscribirse dos veces a la misma materia en el mismo semestre
            $table->unique(['estudiante_id', 'materia_id', 'semestre_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inscripciones_materias');
    }
}; 