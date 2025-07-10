<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Profesor;
use App\Models\Materia;
use Illuminate\Support\Facades\DB;

class AsociarMateriaProfesorCommand extends Command
{
    protected $signature = 'profesor:asociar-materia';
    protected $description = 'Asociar la materia 4 al profesor demo';

    public function handle()
    {
        $profesor = Profesor::whereHas('user', function($q){
            $q->where('email', 'profesor.demo@test.com');
        })->first();
        if (!$profesor) {
            $this->error('No se encontró el profesor demo.');
            return 1;
        }
        $materiaId = 4;
        $materia = Materia::find($materiaId);
        if (!$materia) {
            $this->error('No se encontró la materia con ID 4.');
            return 1;
        }
        // Insertar en la tabla pivote
        DB::table('profesor_materia')->updateOrInsert([
            'profesor_id' => $profesor->id,
            'materia_id' => $materiaId
        ]);
        $this->info('Materia "' . $materia->nombre . '" asociada correctamente al profesor demo.');
        return 0;
    }
} 