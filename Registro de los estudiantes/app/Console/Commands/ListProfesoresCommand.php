<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Profesor;
use App\Models\User;

class ListProfesoresCommand extends Command
{
    protected $signature = 'list:profesores';
    protected $description = 'List all professors with their emails';

    public function handle()
    {
        $this->info('Profesores disponibles:');
        
        $profesores = Profesor::with('user')->get();
        
        if ($profesores->isEmpty()) {
            $this->warn('No hay profesores en la base de datos.');
            return 0;
        }
        
        foreach ($profesores as $profesor) {
            $email = $profesor->user ? $profesor->user->email : 'Sin usuario';
            $this->line("ID: {$profesor->id} - {$profesor->nombre} {$profesor->apellido} - {$email}");
        }
        
        return 0;
    }
} 