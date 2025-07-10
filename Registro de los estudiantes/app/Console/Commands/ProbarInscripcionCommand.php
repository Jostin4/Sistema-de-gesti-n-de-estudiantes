<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\carrera;
use App\Models\estudiante;
use App\Models\MateriaEstudianteSemestre;

class ProbarInscripcionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inscripcion:probar {carrera_id} {estudiante_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba la funcionalidad de inscripción automática de estudiantes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $carreraId = $this->argument('carrera_id');
        $estudianteId = $this->argument('estudiante_id');

        $this->info("Probando inscripción automática...");
        $this->info("Carrera ID: {$carreraId}");

        // Obtener la carrera
        $carrera = carrera::with(['semestres.materias'])->find($carreraId);
        if (!$carrera) {
            $this->error("Carrera con ID {$carreraId} no encontrada.");
            return 1;
        }

        $this->info("Carrera: {$carrera->nombre}");
        $this->info("Semestres disponibles: " . $carrera->semestres->count());

        // Obtener el primer semestre
        $primerSemestre = $carrera->semestres->sortBy('id')->first();
        if (!$primerSemestre) {
            $this->error("No hay semestres disponibles en esta carrera.");
            return 1;
        }

        $this->info("Primer semestre: {$primerSemestre->nombre}");
        $this->info("Materias en primer semestre: " . $primerSemestre->materias->count());

        // Listar materias del primer semestre
        foreach ($primerSemestre->materias as $materia) {
            $this->line("  - {$materia->nombre} ({$materia->codigo})");
        }

        // Si se especificó un estudiante, mostrar su información
        if ($estudianteId) {
            $estudiante = estudiante::find($estudianteId);
            if (!$estudiante) {
                $this->error("Estudiante con ID {$estudianteId} no encontrado.");
                return 1;
            }

            $this->info("\nInformación del estudiante:");
            $this->info("Nombre: {$estudiante->nombre} {$estudiante->apellido_paterno}");
            $this->info("Email: {$estudiante->correo}");

            // Verificar si ya está inscrito en la carrera
            $estaInscrito = $carrera->estudiantes->contains($estudiante->id);
            $this->info("¿Está inscrito en la carrera? " . ($estaInscrito ? 'Sí' : 'No'));

            if ($estaInscrito) {
                // Verificar materias inscritas
                $materiasInscritas = MateriaEstudianteSemestre::where([
                    'estudiante_id' => $estudiante->id,
                    'semestre_id' => $primerSemestre->id
                ])->with('materia')->get();

                $this->info("Materias inscritas en {$primerSemestre->nombre}: " . $materiasInscritas->count());
                foreach ($materiasInscritas as $inscripcion) {
                    $this->line("  - {$inscripcion->materia->nombre} (Estado: {$inscripcion->estado})");
                }
            }
        } else {
            // Mostrar todos los estudiantes inscritos en esta carrera
            $this->info("\nEstudiantes inscritos en {$carrera->nombre}:");
            foreach ($carrera->estudiantes as $estudiante) {
                $this->line("  - {$estudiante->nombre} {$estudiante->apellido_paterno} (ID: {$estudiante->id})");
                
                // Contar materias inscritas
                $materiasInscritas = MateriaEstudianteSemestre::where([
                    'estudiante_id' => $estudiante->id,
                    'semestre_id' => $primerSemestre->id
                ])->count();
                
                $this->line("    Materias inscritas en {$primerSemestre->nombre}: {$materiasInscritas}");
            }
        }

        $this->info("\nPrueba completada.");
        return 0;
    }
} 