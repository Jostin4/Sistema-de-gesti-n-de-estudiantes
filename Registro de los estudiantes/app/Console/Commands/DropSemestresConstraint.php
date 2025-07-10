<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DropSemestresConstraint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:drop-semestres-constraint';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eliminar la restricción CHECK de nombre en la tabla semestres';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Eliminando restricción CHECK de la tabla semestres...');
            
            DB::statement('ALTER TABLE semestres DROP CONSTRAINT IF EXISTS semestres_nombre_check;');
            
            $this->info('✅ Restricción CHECK eliminada exitosamente.');
            $this->info('Ahora puedes crear semestres con cualquier nombre.');
            
        } catch (\Exception $e) {
            $this->error('❌ Error al eliminar la restricción: ' . $e->getMessage());
        }
    }
}
