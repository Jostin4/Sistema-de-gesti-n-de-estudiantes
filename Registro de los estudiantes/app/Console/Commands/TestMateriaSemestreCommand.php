<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\materia;
use App\Models\semestre;

class TestMateriaSemestreCommand extends Command
{
    protected $signature = 'test:materia-semestre';
    protected $description = 'Test the relationship between materias and semestres';

    public function handle()
    {
        $this->info('Verificando relación materia-semestre...');
        
        $materias = materia::all();
        $this->info("Total de materias: " . $materias->count());
        
        foreach ($materias as $materia) {
            $this->info("Materia: {$materia->nombre}");
            $semestres = $materia->semestres;
            $this->info("  - Semestres asociados: " . $semestres->count());
            
            foreach ($semestres as $semestre) {
                $this->info("    * {$semestre->nombre}");
            }
        }
        
        $semestres = semestre::all();
        $this->info("\nTotal de semestres: " . $semestres->count());
        
        foreach ($semestres as $semestre) {
            $this->info("Semestre: {$semestre->nombre}");
            $materias = $semestre->materias;
            $this->info("  - Materias asociadas: " . $materias->count());
            
            foreach ($materias as $materia) {
                $this->info("    * {$materia->nombre}");
            }
        }
        
        return 0;
    }
} 