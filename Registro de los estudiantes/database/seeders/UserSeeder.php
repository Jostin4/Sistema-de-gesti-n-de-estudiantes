<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profesor;
use App\Models\estudiante;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar tablas relacionadas
        \DB::table('profesors')->delete();
        \DB::table('estudiantes')->delete();
        \DB::table('users')->delete();

        // Usuario administrador
        User::create([
            'name' => 'Admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Usuario profesor
        $userProfesor = User::create([
            'name' => 'Profesor Demo',
            'email' => 'profesor@demo.com',
            'password' => Hash::make('profesor123'),
            'role' => 'profesor',
        ]);
        Profesor::create([
            'user_id' => $userProfesor->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
        ]);

        // Usuario estudiante
        $userEstudiante = User::create([
            'name' => 'Estudiante Demo',
            'email' => 'estudiante@demo.com',
            'password' => Hash::make('estudiante123'),
            'role' => 'estudiante',
        ]);
        estudiante::create([
            'nombre' => 'Ana',
            'segundo_nombre' => 'María',
            'apellido_paterno' => 'García',
            'apellido_materno' => 'López',
            'fecha_nacimiento' => '2000-01-01',
            'correo' => 'estudiante@demo.com',
            'telefono' => '123456789',
            'genero' => 'Femenino',
            'estado' => 'Activo',
            'matricula' => '20230001',
            'user_id' => $userEstudiante->id,
        ]);
    }
}
