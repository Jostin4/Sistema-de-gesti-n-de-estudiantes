<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUsersCommand extends Command
{
    protected $signature = 'users:check';
    protected $description = 'Verificar usuarios y sus roles';

    public function handle()
    {
        $users = User::all(['id', 'name', 'email', 'role']);
        
        $this->info('Usuarios y roles:');
        $this->table(
            ['ID', 'Nombre', 'Email', 'Rol'],
            $users->map(function($user) {
                return [$user->id, $user->name, $user->email, $user->role];
            })->toArray()
        );
        
        return 0;
    }
} 