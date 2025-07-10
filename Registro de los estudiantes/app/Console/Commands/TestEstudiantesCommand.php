<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\estudiante;
use App\Models\materia;
use App\Models\semestre;

class TestEstudiantesCommand extends Command
{
    protected $signature = 'test:estudiantes';
    protected $description = 'Verificar estudiantes y sus relaciones';

    public function handle()
    {
        $this->info('=== CARRERAS ===');
        $carreras = \App\Models\carrera::all();
        foreach ($carreras as $carrera) {
            $this->line("ID: {$carrera->id} | Nombre: {$carrera->nombre}");
        }

        $this->info('\n=== ESTUDIANTES ===');
        $estudiantes = estudiante::with(['carreras', 'semestres'])->get();
        foreach ($estudiantes as $estudiante) {
            $carreras = $estudiante->carreras->pluck('nombre')->implode(', ');
            $semestres = $estudiante->semestres->pluck('nombre')->implode(', ');
            $this->line("ID: {$estudiante->id} | Nombre: {$estudiante->nombre} | Carreras: {$carreras} | Semestres: {$semestres}");
        }

        $this->info('\n=== MATERIAS Y SUS ESTUDIANTES ===');
        $materias = materia::with(['estudiantes', 'semestres.carreras'])->get();
        foreach ($materias as $materia) {
            $estudiantes = $materia->estudiantes->pluck('nombre')->implode(', ');
            $semestres = $materia->semestres->map(function($s) {
                $carreras = $s->carreras->pluck('nombre')->implode(', ');
                return "{$s->nombre} ({$carreras})";
            })->implode(', ');
            $this->line("Materia: {$materia->nombre} | Estudiantes: {$estudiantes} | Semestres: {$semestres}");
        }

        $this->info('\n=== TABLA MATERIA_ESTUDIANTE_SEMESTRES ===');
        $pivots = \DB::table('materia_estudiante_semestres')->get();
        foreach ($pivots as $pivot) {
            $materia = materia::find($pivot->materia_id);
            $estudiante = estudiante::find($pivot->estudiante_id);
            $semestre = semestre::find($pivot->semestre_id);
            $this->line("Materia: {$materia->nombre} | Estudiante: {$estudiante->nombre} | Semestre: {$semestre->nombre}");
        }
    }
} 