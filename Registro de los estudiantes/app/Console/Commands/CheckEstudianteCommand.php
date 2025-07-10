<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\estudiante;

class CheckEstudianteCommand extends Command
{
    protected $signature = 'estudiante:check';
    protected $description = 'Verificar si el usuario estudiante tiene su registro correspondiente';

    public function handle()
    {
        $estudiantes = User::where('role', 'estudiante')->get();
        
        $this->info('Verificando usuarios estudiantes:');
        
        foreach ($estudiantes as $user) {
            $estudianteRecord = estudiante::where('user_id', $user->id)->first();
            
            if ($estudianteRecord) {
                $this->info("✓ {$user->name} ({$user->email}) - Tiene registro en tabla estudiantes");
            } else {
                $this->warn("✗ {$user->name} ({$user->email}) - NO tiene registro en tabla estudiantes");
            }
        }
        
        return 0;
    }
} 