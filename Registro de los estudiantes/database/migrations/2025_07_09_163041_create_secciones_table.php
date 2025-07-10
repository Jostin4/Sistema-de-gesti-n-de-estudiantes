<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('secciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: "A", "B", "C"
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('semestre_id')->constrained('semestres')->onDelete('cascade');
            $table->string('horario'); // Ej: "Lunes y Miércoles 8:00-10:00"
            $table->string('aula')->nullable();
            $table->integer('cupo_maximo')->default(30);
            $table->integer('cupo_actual')->default(0);
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('secciones');
    }
}; 