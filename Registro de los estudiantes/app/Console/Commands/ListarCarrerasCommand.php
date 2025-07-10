<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\carrera;

class ListarCarrerasCommand extends Command
{
    protected $signature = 'listar:carreras';
    protected $description = 'Listar todas las carreras';

    public function handle()
    {
        $this->info('=== CARRERAS ===');
        $carreras = carrera::all();
        foreach ($carreras as $carrera) {
            $this->line("ID: {$carrera->id} | Nombre: {$carrera->nombre}");
        }
    }
} 