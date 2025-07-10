<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Profesor;
use App\Models\User;

class CheckProfesorMateriasCommand extends Command
{
    protected $signature = 'check:profesor-materias {email}';
    protected $description = 'Check materias assigned to a professor by email';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Verificando materias del profesor: {$email}");
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Usuario no encontrado con email: {$email}");
            return 1;
        }
        
        $profesor = Profesor::where('user_id', $user->id)->first();
        
        if (!$profesor) {
            $this->error("No se encontró un profesor asociado al usuario: {$email}");
            return 1;
        }
        
        $this->info("Profesor encontrado: {$profesor->nombre} {$profesor->apellido}");
        $this->info("Materias asignadas:");
        
        $materias = $profesor->materias;
        
        if ($materias->isEmpty()) {
            $this->warn("No tiene materias asignadas.");
            return 0;
        }
        
        foreach ($materias as $materia) {
            $this->line("ID: {$materia->id} - {$materia->nombre}");
            $semestres = $materia->semestres;
            $this->line("  Semestres asociados: " . $semestres->count());
            foreach ($semestres as $semestre) {
                $this->line("    * {$semestre->nombre} (ID: {$semestre->id})");
            }
        }
        
        return 0;
    }
} 