<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MateriaEstudianteSemestre;
use App\Models\semestre;
use App\Models\materia;
use App\Models\Evaluacion;
use App\Models\Nota;

class EstudianteNotasController extends Controller
{
    // Dashboard: lista de semestres y materias del estudiante
    public function index()
    {
        $user = Auth::user();
        $estudiante = $user->estudiante ?? null;
        if (!$estudiante) {
            abort(403, 'No eres estudiante.');
        }
        // Obtener los semestres en los que está o estuvo inscrito
        $semestres = MateriaEstudianteSemestre::where('estudiante_id', $estudiante->id)
            ->with('semestre')
            ->get()
            ->pluck('semestre')
            ->unique('id');
        return view('estudiante.dashboard', compact('semestres'));
    }

    // Ver materias de un semestre
    public function semestre($semestreId)
    {
        $user = Auth::user();
        $estudiante = $user->estudiante ?? null;
        if (!$estudiante) {
            abort(403, 'No eres estudiante.');
        }
        $semestre = semestre::findOrFail($semestreId);
        $materias = MateriaEstudianteSemestre::where('estudiante_id', $estudiante->id)
            ->where('semestre_id', $semestreId)
            ->with('materia')
            ->get()
            ->pluck('materia');
        return view('estudiante.semestre', compact('semestre', 'materias'));
    }

    // Ver evaluaciones y notas de una materia en un semestre
    public function materia($semestreId, $materiaId)
    {
        $user = Auth::user();
        $estudiante = $user->estudiante ?? null;
        if (!$estudiante) {
            abort(403, 'No eres estudiante.');
        }
        $semestre = semestre::findOrFail($semestreId);
        $materia = materia::findOrFail($materiaId);
        $evaluaciones = Evaluacion::where('materia_id', $materiaId)
            ->where('semestre_id', $semestreId)
            ->get();
        $notas = Nota::where('estudiante_id', $estudiante->id)
            ->whereIn('evaluacion_id', $evaluaciones->pluck('id'))
            ->get()
            ->keyBy('evaluacion_id');
        return view('estudiante.materia', compact('semestre', 'materia', 'evaluaciones', 'notas'));
    }

    // Ver todas las notas del estudiante
    public function notas()
    {
        $user = Auth::user();
        $estudiante = $user->estudiante ?? null;
        if (!$estudiante) {
            abort(403, 'No eres estudiante.');
        }

        // Obtener todas las notas del estudiante con información relacionada
        $notas = Nota::where('estudiante_id', $estudiante->id)
            ->with(['evaluacion.materia', 'evaluacion.semestre'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Agrupar notas por semestre y materia
        $notasAgrupadas = $notas->groupBy(function($nota) {
            return $nota->evaluacion->semestre->nombre . ' - ' . $nota->evaluacion->materia->nombre;
        });

        return view('estudiante.notas', compact('notas', 'notasAgrupadas'));
    }
}
