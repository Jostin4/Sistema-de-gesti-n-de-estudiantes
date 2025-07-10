<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Profesor;

class FixProfesorDemoCommand extends Command
{
    protected $signature = 'profesor:fix-demo';
    protected $description = 'Crear el registro de profesor para el usuario profesor.demo@test.com si no existe';

    public function handle()
    {
        $user = User::where('email', 'profesor.demo@test.com')->first();
        if (!$user) {
            $this->error('No existe el usuario profesor.demo@test.com');
            return 1;
        }
        $profesor = Profesor::where('user_id', $user->id)->first();
        if ($profesor) {
            $this->info('El registro de profesor ya existe.');
            return 0;
        }
        $profesor = Profesor::create([
            'nombre' => 'Profesor',
            'apellido' => 'Demo',
            'user_id' => $user->id
        ]);
        $this->info('Registro de profesor creado correctamente.');
        return 0;
    }
} 