<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LimpiarDuplicadosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'limpiar:duplicados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpiar registros duplicados en las tablas de relación';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando limpieza de duplicados...');

        // Limpiar duplicados en carrera_estudiantes
        $this->info('Limpiando duplicados en carrera_estudiantes...');
        $duplicadosCarrera = DB::table('carrera_estudiantes')
            ->select('carreras_id', 'estudiantes_id', DB::raw('COUNT(*) as total'))
            ->groupBy('carreras_id', 'estudiantes_id')
            ->having('total', '>', 1)
            ->get();

        foreach ($duplicadosCarrera as $duplicado) {
            $registros = DB::table('carrera_estudiantes')
                ->where('carreras_id', $duplicado->carreras_id)
                ->where('estudiantes_id', $duplicado->estudiantes_id)
                ->orderBy('id')
                ->get();

            // Mantener solo el primer registro
            $primerRegistro = $registros->first();
            $registrosAEliminar = $registros->where('id', '!=', $primerRegistro->id);

            foreach ($registrosAEliminar as $registro) {
                DB::table('carrera_estudiantes')->where('id', $registro->id)->delete();
            }

            $this->info("Eliminados " . $registrosAEliminar->count() . " duplicados para carrera_id: {$duplicado->carreras_id}, estudiante_id: {$duplicado->estudiantes_id}");
        }

        // Limpiar duplicados en semestres_estudiantes
        $this->info('Limpiando duplicados en semestres_estudiantes...');
        $duplicadosSemestre = DB::table('semestres_estudiantes')
            ->select('semestres_id', 'estudiantes_id', 'carreras_id', DB::raw('COUNT(*) as total'))
            ->groupBy('semestres_id', 'estudiantes_id', 'carreras_id')
            ->having('total', '>', 1)
            ->get();

        foreach ($duplicadosSemestre as $duplicado) {
            $registros = DB::table('semestres_estudiantes')
                ->where('semestres_id', $duplicado->semestres_id)
                ->where('estudiantes_id', $duplicado->estudiantes_id)
                ->where('carreras_id', $duplicado->carreras_id)
                ->orderBy('id')
                ->get();

            // Mantener solo el primer registro
            $primerRegistro = $registros->first();
            $registrosAEliminar = $registros->where('id', '!=', $primerRegistro->id);

            foreach ($registrosAEliminar as $registro) {
                DB::table('semestres_estudiantes')->where('id', $registro->id)->delete();
            }

            $this->info("Eliminados " . $registrosAEliminar->count() . " duplicados para semestre_id: {$duplicado->semestres_id}, estudiante_id: {$duplicado->estudiantes_id}, carrera_id: {$duplicado->carreras_id}");
        }

        // Limpiar duplicados en materia_estudiante_semestres
        $this->info('Limpiando duplicados en materia_estudiante_semestres...');
        $duplicadosMateria = DB::table('materia_estudiante_semestres')
            ->select('materia_id', 'estudiante_id', 'semestre_id', DB::raw('COUNT(*) as total'))
            ->groupBy('materia_id', 'estudiante_id', 'semestre_id')
            ->having('total', '>', 1)
            ->get();

        foreach ($duplicadosMateria as $duplicado) {
            $registros = DB::table('materia_estudiante_semestres')
                ->where('materia_id', $duplicado->materia_id)
                ->where('estudiante_id', $duplicado->estudiante_id)
                ->where('semestre_id', $duplicado->semestre_id)
                ->orderBy('id')
                ->get();

            // Mantener solo el primer registro
            $primerRegistro = $registros->first();
            $registrosAEliminar = $registros->where('id', '!=', $primerRegistro->id);

            foreach ($registrosAEliminar as $registro) {
                DB::table('materia_estudiante_semestres')->where('id', $registro->id)->delete();
            }

            $this->info("Eliminados " . $registrosAEliminar->count() . " duplicados para materia_id: {$duplicado->materia_id}, estudiante_id: {$duplicado->estudiante_id}, semestre_id: {$duplicado->semestre_id}");
        }

        $this->info('Limpieza de duplicados completada exitosamente.');
    }
} 