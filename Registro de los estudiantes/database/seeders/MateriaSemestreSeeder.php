<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\materia;
use App\Models\semestre;
use App\Models\Profesor;
use App\Models\MateriaEstudianteSemestre;
use App\Models\estudiante;
use Illuminate\Support\Facades\DB;
use App\Models\carrera;

class MateriaSemestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar tablas relacionadas
        DB::table('materia_estudiante_semestres')->delete();
        DB::table('profesor_materia')->delete();
        DB::table('materias')->delete();
        DB::table('semestres')->delete();

        // Crear carrera de prueba si no existe
        $carrera = carrera::first();
        if (!$carrera) {
            $carrera = carrera::create(['nombre' => 'Ingeniería Demo']);
        }

        // Crear semestres
        $semestre1 = semestre::create(['nombre' => 'primero']);
        $semestre2 = semestre::create(['nombre' => 'segundo']);

        // Crear materias
        $mat1 = materia::create(['nombre' => 'Matemáticas', 'estado' => 'Activo']);
        $mat2 = materia::create(['nombre' => 'Lengua', 'estado' => 'Activo']);
        $mat3 = materia::create(['nombre' => 'Historia', 'estado' => 'Activo']);

        // Asociar materias a semestres
        $mat1->semestres()->attach($semestre1->id);
        $mat2->semestres()->attach($semestre1->id);
        $mat3->semestres()->attach($semestre2->id);

        // Asignar materias al profesor demo
        $profesor = Profesor::first();
        if ($profesor) {
            $profesor->materias()->sync([$mat1->id, $mat2->id, $mat3->id]);
        }

        // Asignar materias al estudiante demo en semestre 1
        $estudiante = estudiante::first();
        if ($estudiante) {
            MateriaEstudianteSemestre::create([
                'estudiante_id' => $estudiante->id,
                'materia_id' => $mat1->id,
                'semestre_id' => $semestre1->id,
                'estado' => 'cursando',
            ]);
            MateriaEstudianteSemestre::create([
                'estudiante_id' => $estudiante->id,
                'materia_id' => $mat2->id,
                'semestre_id' => $semestre1->id,
                'estado' => 'cursando',
            ]);
        }
    }
}
