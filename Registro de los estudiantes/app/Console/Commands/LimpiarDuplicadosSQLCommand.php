<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LimpiarDuplicadosSQLCommand extends Command
{
    protected $signature = 'limpiar:duplicados-sql';
    protected $description = 'Limpiar duplicados usando SQL directo';

    public function handle()
    {
        $this->info('Limpiando duplicados con SQL directo...');

        try {
            // Limpiar carrera_estudiantes
            $this->info('Limpiando carrera_estudiantes...');
            DB::statement("
                DELETE FROM carrera_estudiantes 
                WHERE id NOT IN (
                    SELECT MIN(id) 
                    FROM carrera_estudiantes 
                    GROUP BY carreras_id, estudiantes_id
                )
            ");
            $this->info('✓ carrera_estudiantes limpiado');

            // Limpiar semestres_estudiantes
            $this->info('Limpiando semestres_estudiantes...');
            DB::statement("
                DELETE FROM semestres_estudiantes 
                WHERE id NOT IN (
                    SELECT MIN(id) 
                    FROM semestres_estudiantes 
                    GROUP BY semestres_id, estudiantes_id, carreras_id
                )
            ");
            $this->info('✓ semestres_estudiantes limpiado');

            // Limpiar materia_estudiante_semestres
            $this->info('Limpiando materia_estudiante_semestres...');
            DB::statement("
                DELETE FROM materia_estudiante_semestres 
                WHERE id NOT IN (
                    SELECT MIN(id) 
                    FROM materia_estudiante_semestres 
                    GROUP BY materia_id, estudiante_id, semestre_id
                )
            ");
            $this->info('✓ materia_estudiante_semestres limpiado');

            $this->info('¡Limpieza completada exitosamente!');

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
} 