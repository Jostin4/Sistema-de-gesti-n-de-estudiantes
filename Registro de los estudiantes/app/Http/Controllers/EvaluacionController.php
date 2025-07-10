<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluacion;
use App\Models\Profesor;
use App\Models\materia;
use App\Models\semestre;
use Illuminate\Support\Facades\Auth;

class EvaluacionController extends Controller
{
    // Listar materias del profesor autenticado
    public function index(Request $request)
    {
        $user = Auth::user();
        $profesor = Profesor::where('user_id', $user->id)->first();
        $materias = $profesor ? $profesor->materias()->with(['semestres.carreras'])->get() : collect();
        return view('evaluaciones.index', compact('materias'));
    }

    // Listar evaluaciones de una materia y semestre
    public function show($materiaId, $semestreId)
    {
        $materia = materia::findOrFail($materiaId);
        $semestre = semestre::findOrFail($semestreId);
        $evaluaciones = Evaluacion::where('materia_id', $materiaId)
            ->where('semestre_id', $semestreId)
            ->get();
        $porcentajeTotal = $evaluaciones->sum('porcentaje');
        return view('evaluaciones.show', compact('materia', 'semestre', 'evaluaciones', 'porcentajeTotal'));
    }

    // Mostrar formulario para crear evaluación
    public function create($materiaId, $semestreId)
    {
        $materia = materia::findOrFail($materiaId);
        $semestre = semestre::findOrFail($semestreId);
        $evaluaciones = Evaluacion::where('materia_id', $materiaId)
            ->where('semestre_id', $semestreId)
            ->get();
        $porcentajeTotal = $evaluaciones->sum('porcentaje');
        return view('evaluaciones.create', compact('materia', 'semestre', 'porcentajeTotal'));
    }

    // Guardar nueva evaluación
    public function store(Request $request)
    {
        $request->validate([
            'materia_id' => 'required|exists:materias,id',
            'semestre_id' => 'required|exists:semestres,id',
            'nombre' => 'required|string|max:255',
            'porcentaje' => 'required|numeric|min:1|max:100',
        ]);
        
        $evaluaciones = Evaluacion::where('materia_id', $request->materia_id)
            ->where('semestre_id', $request->semestre_id)
            ->get();
        $porcentajeTotal = $evaluaciones->sum('porcentaje');
        
        if (($porcentajeTotal + $request->porcentaje) > 100) {
            return back()->withErrors(['porcentaje' => 'El total de porcentajes no puede superar 100%.'])->withInput();
        }
        
        Evaluacion::create($request->only('materia_id', 'semestre_id', 'nombre', 'porcentaje'));
        return redirect()->route('evaluaciones.show', [$request->materia_id, $request->semestre_id])
            ->with('success', 'Evaluación creada correctamente');
    }
}
