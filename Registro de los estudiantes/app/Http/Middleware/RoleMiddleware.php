<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        
        if (!$user) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        // Procesar roles que pueden venir separados por comas
        $allowedRoles = [];
        foreach ($roles as $role) {
            $allowedRoles = array_merge($allowedRoles, explode(',', $role));
        }
        
        // Limpiar espacios en blanco
        $allowedRoles = array_map('trim', $allowedRoles);
        
        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        return $next($request);
    }
}
