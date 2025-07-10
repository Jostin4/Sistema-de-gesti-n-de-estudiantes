<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\estudiante;
use App\Models\carrera;
use App\Models\MateriaEstudianteSemestre;

class InscribirEstudianteCommand extends Command
{
    protected $signature = 'inscribir:estudiante {estudiante_id} {carrera_id}';
    protected $description = 'Inscribir manualmente a un estudiante en una carrera';

    public function handle()
    {
        $estudianteId = $this->argument('estudiante_id');
        $carreraId = $this->argument('carrera_id');

        $estudiante = estudiante::find($estudianteId);
        $carrera = carrera::with(['semestres.materias'])->find($carreraId);

        if (!$estudiante) {
            $this->error("Estudiante con ID {$estudianteId} no encontrado");
            return;
        }

        if (!$carrera) {
            $this->error("Carrera con ID {$carreraId} no encontrada");
            return;
        }

        $this->info("Inscribiendo estudiante {$estudiante->nombre} en carrera {$carrera->nombre}");

        // Obtener el primer semestre
        $primerSemestre = $carrera->semestres->sortBy('id')->first();
        
        if (!$primerSemestre) {
            $this->error("La carrera no tiene semestres");
            return;
        }

        $this->info("Primer semestre: {$primerSemestre->nombre}");

        // Inscribir en carrera
        $carrera->estudiantes()->attach($estudiante->id);
        $this->line("✓ Inscrito en carrera");

        // Inscribir en semestre
        $estudiante->semestres()->attach($primerSemestre->id, ['carreras_id' => $carrera->id]);
        $this->line("✓ Inscrito en semestre");

        // Inscribir en materias
        $materiasInscritas = 0;
        foreach ($primerSemestre->materias as $materia) {
            $existe = MateriaEstudianteSemestre::where([
                'estudiante_id' => $estudiante->id,
                'materia_id' => $materia->id,
                'semestre_id' => $primerSemestre->id
            ])->exists();

            if (!$existe) {
                MateriaEstudianteSemestre::create([
                    'estudiante_id' => $estudiante->id,
                    'materia_id' => $materia->id,
                    'semestre_id' => $primerSemestre->id,
                    'estado' => 'inscrito'
                ]);
                $materiasInscritas++;
                $this->line("✓ Inscrito en materia: {$materia->nombre}");
            }
        }

        $this->info("¡Inscripción completada! {$materiasInscritas} materias inscritas");
    }
} 