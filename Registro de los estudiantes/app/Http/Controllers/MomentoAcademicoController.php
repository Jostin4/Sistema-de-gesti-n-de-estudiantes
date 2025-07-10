<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\semestre;
use App\Models\carrera;
use Carbon\Carbon;

class MomentoAcademicoController extends Controller
{
    // Mostrar vista de gestión de momentos académicos
    public function index()
    {
        $semestres = semestre::with('carreras')->get();
        $carreras = carrera::where('estado', 'activa')->get();
        
        return view('admin.momentos-academicos.index', compact('semestres', 'carreras'));
    }

    // Activar momento académico
    public function activar(Request $request, $semestreId)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $semestre = semestre::findOrFail($semestreId);
        
        // Desactivar otros momentos académicos de las mismas carreras
        foreach ($semestre->carreras as $carrera) {
            $carrera->semestres()->update([
                'momento_academico_activo' => false
            ]);
        }

        // Activar el momento académico actual
        $semestre->update([
            'momento_academico_activo' => true,
            'fecha_inicio_inscripcion' => $request->fecha_inicio,
            'fecha_fin_inscripcion' => $request->fecha_fin,
        ]);

        $carreraNombre = $semestre->carreras->first()->nombre ?? 'Sin carrera';
        return back()->with('success', "Momento académico activado para {$semestre->nombre} de {$carreraNombre}");
    }

    // Desactivar momento académico
    public function desactivar($semestreId)
    {
        $semestre = semestre::findOrFail($semestreId);
        
        $semestre->update([
            'momento_academico_activo' => false,
            'fecha_inicio_inscripcion' => null,
            'fecha_fin_inscripcion' => null,
        ]);

        return back()->with('success', "Momento académico desactivado para {$semestre->nombre}");
    }

    // Obtener estadísticas de inscripción
    public function estadisticas($semestreId)
    {
        $semestre = semestre::with(['inscripciones.estudiante', 'inscripciones.materia'])->findOrFail($semestreId);
        
        $totalInscripciones = $semestre->inscripciones->count();
        $inscripcionesPorMateria = $semestre->inscripciones->groupBy('materia_id');
        
        return response()->json([
            'semestre' => $semestre->nombre,
            'total_inscripciones' => $totalInscripciones,
            'inscripciones_por_materia' => $inscripcionesPorMateria,
        ]);
    }
} 