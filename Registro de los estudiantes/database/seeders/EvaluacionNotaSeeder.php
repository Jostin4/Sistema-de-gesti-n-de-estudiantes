<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Evaluacion;
use App\Models\Nota;
use App\Models\MateriaEstudianteSemestre;
use App\Models\estudiante;

class EvaluacionNotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar evaluaciones y notas
        \DB::table('notas')->delete();
        \DB::table('evaluacions')->delete();

        $estudiante = estudiante::first();
        $inscripciones = MateriaEstudianteSemestre::where('estudiante_id', $estudiante->id)->get();
        foreach ($inscripciones as $inscripcion) {
            // Crear dos evaluaciones por materia
            $eval1 = Evaluacion::create([
                'materia_id' => $inscripcion->materia_id,
                'semestre_id' => $inscripcion->semestre_id,
                'nombre' => 'Parcial 1',
                'porcentaje' => 40,
            ]);
            $eval2 = Evaluacion::create([
                'materia_id' => $inscripcion->materia_id,
                'semestre_id' => $inscripcion->semestre_id,
                'nombre' => 'Final',
                'porcentaje' => 60,
            ]);
            // Notas de ejemplo
            Nota::create([
                'evaluacion_id' => $eval1->id,
                'estudiante_id' => $estudiante->id,
                'nota' => 75,
            ]);
            Nota::create([
                'evaluacion_id' => $eval2->id,
                'estudiante_id' => $estudiante->id,
                'nota' => 85,
            ]);
        }
    }
}
