<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\materia;

class MateriaApiController extends Controller
{
    // Devuelve los semestres asociados a una materia
    public function semestres($materiaId)
    {
        $materia = materia::findOrFail($materiaId);
        $semestres = $materia->semestres()->with('carreras')->orderBy('nombre')->get();
        
        // Formatear los semestres para incluir la información de carrera
        $semestresFormateados = $semestres->map(function($semestre) {
            $carreras = $semestre->carreras->pluck('nombre')->implode(', ');
            $nombreCompleto = $carreras ? "{$semestre->nombre} de {$carreras}" : $semestre->nombre;
            
            return [
                'id' => $semestre->id,
                'nombre' => $semestre->nombre,
                'nombre_completo' => $nombreCompleto,
                'carreras' => $semestre->carreras->pluck('nombre')->toArray(),
                'carreras_texto' => $carreras
            ];
        });
        
        return response()->json($semestresFormateados);
    }
}
