<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\semestre;
use App\Models\carrera;
use App\Models\materia;
use App\Models\estudiante;

class CorregirSemestresCarrera extends Command
{
    protected $signature = 'corregir:semestres-carrera';
    protected $description = 'Corrige los semestres compartidos entre carreras, duplicando y actualizando todas las relaciones e inscripciones';

    public function handle()
    {
        $this->info('Iniciando corrección de semestres compartidos...');
        DB::beginTransaction();
        try {
            $semestres = semestre::with('carreras')->get();
            $duplicados = $semestres->filter(fn($s) => $s->carreras->count() > 1);
            $this->info('Semestres compartidos encontrados: ' . $duplicados->count());
            foreach ($duplicados as $semestre) {
                foreach ($semestre->carreras as $carrera) {
                    // Crear nuevo semestre para la carrera
                    $nuevo = $semestre->replicate();
                    $nuevo->save();
                    // Asociar a la carrera
                    DB::table('carrera_semestres')->insert([
                        'carreras_id' => $carrera->id,
                        'semestres_id' => $nuevo->id
                    ]);
                    $this->line("Creado semestre '{$nuevo->nombre}' para carrera '{$carrera->nombre}' (ID: {$nuevo->id})");
                    // Actualizar materias asociadas a este semestre y carrera
                    $materias = DB::table('materia_semestre')
                        ->where('semestre_id', $semestre->id)
                        ->get();
                    foreach ($materias as $mat) {
                        // Solo si la materia pertenece a la carrera
                        $materia = materia::find($mat->materia_id);
                        if ($materia) {
                            // Asociar materia al nuevo semestre
                            DB::table('materia_semestre')->insert([
                                'materia_id' => $materia->id,
                                'semestre_id' => $nuevo->id,
                                'estado' => $mat->estado,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                    // Actualizar inscripciones de estudiantes
                    $inscripciones = DB::table('semestres_estudiantes')
                        ->where('semestres_id', $semestre->id)
                        ->get();
                    foreach ($inscripciones as $insc) {
                        // Solo si el estudiante pertenece a la carrera
                        $est = estudiante::find($insc->estudiantes_id);
                        if ($est && $est->carreras->contains($carrera->id)) {
                            DB::table('semestres_estudiantes')->insert([
                                'semestres_id' => $nuevo->id,
                                'estudiantes_id' => $est->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                    // Actualizar materia_estudiante_semestres
                    $mes = DB::table('materia_estudiante_semestres')
                        ->where('semestre_id', $semestre->id)
                        ->get();
                    foreach ($mes as $row) {
                        $est = estudiante::find($row->estudiante_id);
                        if ($est && $est->carreras->contains($carrera->id)) {
                            DB::table('materia_estudiante_semestres')->insert([
                                'materia_id' => $row->materia_id,
                                'estudiante_id' => $row->estudiante_id,
                                'semestre_id' => $nuevo->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
                // Eliminar relaciones antiguas
                DB::table('carrera_semestres')->where('semestres_id', $semestre->id)->delete();
                DB::table('materia_semestre')->where('semestre_id', $semestre->id)->delete();
                DB::table('semestres_estudiantes')->where('semestres_id', $semestre->id)->delete();
                DB::table('materia_estudiante_semestres')->where('semestre_id', $semestre->id)->delete();
                // Eliminar el semestre original
                $semestre->delete();
                $this->warn("Semestre original '{$semestre->nombre}' (ID: {$semestre->id}) eliminado");
            }
            DB::commit();
            $this->info('¡Corrección completada!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error: ' . $e->getMessage());
        }
    }
} 