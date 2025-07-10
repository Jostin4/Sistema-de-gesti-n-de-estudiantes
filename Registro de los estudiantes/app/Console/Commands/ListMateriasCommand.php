<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\materia;

class ListMateriasCommand extends Command
{
    protected $signature = 'list:materias';
    protected $description = 'List all materias with their IDs';

    public function handle()
    {
        $this->info('Materias disponibles:');
        
        $materias = materia::all();
        
        if ($materias->isEmpty()) {
            $this->warn('No hay materias en la base de datos.');
            return 0;
        }
        
        foreach ($materias as $materia) {
            $this->line("ID: {$materia->id} - {$materia->nombre}");
        }
        
        return 0;
    }
} 