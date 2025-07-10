<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Api\MateriaApiController;

class TestApiCommand extends Command
{
    protected $signature = 'test:api {materia_id}';
    protected $description = 'Test the API endpoint for semestres por materia';

    public function handle()
    {
        $materiaId = $this->argument('materia_id');
        $controller = new MateriaApiController();
        $response = $controller->semestres($materiaId);
        
        $this->info('Respuesta de la API:');
        $this->line(json_encode($response->getData(), JSON_PRETTY_PRINT));
    }
} 