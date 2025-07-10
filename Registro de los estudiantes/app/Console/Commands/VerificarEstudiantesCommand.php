<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\estudiante;

class VerificarEstudiantesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'estudiantes:verificar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica y corrige usuarios estudiantes sin registro en la tabla estudiantes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando usuarios estudiantes...');

        // Obtener usuarios con rol estudiante
        $usuariosEstudiantes = User::where('role', 'estudiante')->get();
        
        $this->info("Total de usuarios con rol estudiante: {$usuariosEstudiantes->count()}");

        // Mostrar todos los usuarios para verificar
        $this->info("\nTodos los usuarios en el sistema:");
        User::all(['id', 'name', 'email', 'role'])->each(function($user) {
            $this->line("ID: {$user->id} - {$user->name} ({$user->email}) - Rol: {$user->role}");
        });

        foreach ($usuariosEstudiantes as $usuario) {
            $estudiante = estudiante::where('user_id', $usuario->id)->first();
            
            if (!$estudiante) {
                $this->warn("Usuario {$usuario->name} ({$usuario->email}) no tiene registro en la tabla estudiantes");
                
                if ($this->confirm("¿Deseas crear un registro de estudiante para {$usuario->name}?")) {
                    // Crear registro de estudiante
                    $estudiante = estudiante::create([
                        'nombre' => $usuario->name,
                        'correo' => $usuario->email,
                        'user_id' => $usuario->id,
                        'estado' => 'Activo',
                        'matricula' => 'EST' . str_pad($usuario->id, 6, '0', STR_PAD_LEFT),
                        'fecha_nacimiento' => '1990-01-01', // Fecha por defecto
                        'genero' => 'No especificado'
                    ]);
                    
                    $this->info("✓ Registro de estudiante creado para {$usuario->name}");
                }
            } else {
                $this->info("✓ Usuario {$usuario->name} tiene registro de estudiante");
            }
        }

        $this->info('Verificación completada.');
    }
} 