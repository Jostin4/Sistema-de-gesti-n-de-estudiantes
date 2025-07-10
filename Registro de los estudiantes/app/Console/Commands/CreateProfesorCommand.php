<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Profesor;
use Illuminate\Support\Facades\Hash;

class CreateProfesorCommand extends Command
{
    protected $signature = 'profesor:create';
    protected $description = 'Crear un usuario profesor de prueba';

    public function handle()
    {
        // Crear usuario
        $user = User::create([
            'name' => 'Profesor Demo',
            'email' => 'profesor.demo@test.com',
            'password' => Hash::make('password'),
            'role' => 'profesor'
        ]);
        
        // Crear profesor
        $profesor = Profesor::create([
            'nombre' => 'Profesor',
            'apellido' => 'Demo',
            'user_id' => $user->id
        ]);
        
        $this->info('Usuario profesor creado exitosamente:');
        $this->info('Email: profesor.demo@test.com');
        $this->info('Contraseña: password');
        $this->info('Rol: profesor');
        
        return 0;
    }
} 