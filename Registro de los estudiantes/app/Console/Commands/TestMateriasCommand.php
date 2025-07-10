<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\materia;
use App\Models\semestre;
use App\Models\carrera;

class TestMateriasCommand extends Command
{
    protected $signature = 'test:materias';
    protected $description = 'Verificar materias y semestres';

    public function handle()
    {
        $this->info('=== SEMESTRES ===');
        $semestres = semestre::with('carreras')->get();
        foreach ($semestres as $semestre) {
            $carreras = $semestre->carreras->pluck('nombre')->implode(', ');
            $this->line("ID: {$semestre->id} | Nombre: {$semestre->nombre} | Carreras: {$carreras}");
        }

        $this->info('\n=== CARRERAS Y SUS SEMESTRES ===');
        $carreras = carrera::with('semestres')->get();
        foreach ($carreras as $carrera) {
            $semestres = $carrera->semestres->pluck('nombre')->implode(', ');
            $this->line("{$carrera->nombre}: {$semestres}");
        }

        $this->info('\n=== MATERIAS ===');
        $materias = materia::with('semestres.carreras')->get();
        foreach ($materias as $materia) {
            $semestres = $materia->semestres->map(function($s) {
                $carreras = $s->carreras->pluck('nombre')->implode(', ');
                return "{$s->nombre} ({$carreras})";
            })->implode(', ');
            $this->line("ID: {$materia->id} | Nombre: {$materia->nombre} | Semestres: {$semestres}");
        }

        $this->info('\n=== TABLA MATERIA_SEMESTRE ===');
        $pivots = \DB::table('materia_semestre')->get();
        foreach ($pivots as $pivot) {
            $materia = materia::find($pivot->materia_id);
            $semestre = semestre::find($pivot->semestre_id);
            $this->line("Materia: {$materia->nombre} | Semestre: {$semestre->nombre}");
        }
    }
} 