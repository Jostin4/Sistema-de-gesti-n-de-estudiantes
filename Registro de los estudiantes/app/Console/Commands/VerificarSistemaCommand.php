<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\estudiante;
use App\Models\carrera;
use App\Models\semestre;
use Illuminate\Support\Facades\DB;

class VerificarSistemaCommand extends Command
{
    protected $signature = 'verificar:sistema';
    protected $description = 'Verificar el estado del sistema académico';

    public function handle()
    {
        $this->info('=== VERIFICACIÓN DEL SISTEMA ACADÉMICO ===');
        
        // Verificar duplicados
        $this->info('1. Verificando duplicados...');
        
        $duplicadosCarrera = DB::table('carrera_estudiantes')
            ->select('carreras_id', 'estudiantes_id', DB::raw('COUNT(*) as total'))
            ->groupBy('carreras_id', 'estudiantes_id')
            ->having('total', '>', 1)
            ->count();
            
        $duplicadosSemestre = DB::table('semestres_estudiantes')
            ->select('semestres_id', 'estudiantes_id', 'carreras_id', DB::raw('COUNT(*) as total'))
            ->groupBy('semestres_id', 'estudiantes_id', 'carreras_id')
            ->having('total', '>', 1)
            ->count();
            
        if ($duplicadosCarrera > 0) {
            $this->error("❌ Encontrados {$duplicadosCarrera} duplicados en carrera_estudiantes");
        } else {
            $this->info("✅ No hay duplicados en carrera_estudiantes");
        }
        
        if ($duplicadosSemestre > 0) {
            $this->error("❌ Encontrados {$duplicadosSemestre} duplicados en semestres_estudiantes");
        } else {
            $this->info("✅ No hay duplicados en semestres_estudiantes");
        }
        
        // Verificar estadísticas
        $this->info('2. Estadísticas del sistema...');
        
        $totalEstudiantes = estudiante::count();
        $estudiantesConCarrera = estudiante::whereHas('carreras')->count();
        $estudiantesConSemestre = estudiante::whereHas('semestres')->count();
        
        $this->info("📊 Total estudiantes: {$totalEstudiantes}");
        $this->info("📊 Estudiantes con carrera: {$estudiantesConCarrera}");
        $this->info("📊 Estudiantes con semestre: {$estudiantesConSemestre}");
        
        // Verificar umbral de aprobación
        $this->info('3. Verificando umbral de aprobación...');
        
        $notasAprobadas = DB::table('notas')->where('nota', '>=', 50)->count();
        $notasReprobadas = DB::table('notas')->where('nota', '<', 50)->count();
        $totalNotas = DB::table('notas')->count();
        
        $this->info("📊 Total notas: {$totalNotas}");
        $this->info("📊 Notas aprobadas (>=50): {$notasAprobadas}");
        $this->info("📊 Notas reprobadas (<50): {$notasReprobadas}");
        
        if ($totalNotas > 0) {
            $porcentajeAprobacion = round(($notasAprobadas / $totalNotas) * 100, 1);
            $this->info("📊 Porcentaje de aprobación: {$porcentajeAprobacion}%");
        }
        
        $this->info('=== VERIFICACIÓN COMPLETADA ===');
    }
} 