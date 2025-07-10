<?php

namespace App\Http\Controllers;

use App\Models\carrera;
use App\Models\semestre;
use App\Models\materia;
use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MateriaSemestreController extends Controller
{
    public function index(Request $request)
    {
        $carreras = carrera::with(['semestres.materias'])->get();
        $semestres = semestre::with(['materias', 'carreras'])->get();
        $materias = materia::with(['profesores', 'semestres'])->get();
        $profesores = Profesor::with(['materias'])->get();

        // Filtrar por semestre si se especifica
        $semestreSeleccionado = null;
        if ($request->has('semestre')) {
            $semestreSeleccionado = semestre::with(['materias', 'carreras'])->find($request->semestre);
        }

        return view('materia-semestre.index', compact('carreras', 'semestres', 'materias', 'profesores', 'semestreSeleccionado'));
    }

    public function show($semestreId)
    {
        $semestre = semestre::with(['materias.profesores', 'carreras', 'estudiantes'])->findOrFail($semestreId);
        $materiasDisponibles = materia::whereDoesntHave('semestres', function($query) use ($semestreId) {
            $query->where('semestre_id', $semestreId);
        })->get();
        $profesores = Profesor::all();

        return view('materia-semestre.show', compact('semestre', 'materiasDisponibles', 'profesores'));
    }

    public function asignarMateria(Request $request, $semestreId)
    {
        $request->validate([
            'materia_id' => 'required|exists:materias,id',
            'profesor_id' => 'nullable|exists:profesors,id',
            'creditos' => 'nullable|integer|min:1|max:10',
            'horas_teoricas' => 'nullable|integer|min:0',
            'horas_practicas' => 'nullable|integer|min:0',
        ]);

        $semestre = semestre::findOrFail($semestreId);
        $materia = materia::findOrFail($request->materia_id);

        // Verificar si la materia ya está asignada al semestre
        if ($semestre->materias->contains($materia->id)) {
            return back()->with('error', 'Esta materia ya está asignada al semestre.');
        }

        // Asignar materia al semestre
        $semestre->materias()->attach($materia->id, [
            'creditos' => $request->creditos ?? $materia->creditos,
            'horas_teoricas' => $request->horas_teoricas ?? 0,
            'horas_practicas' => $request->horas_practicas ?? 0,
        ]);

        // Asignar profesor si se especifica
        if ($request->profesor_id) {
            $materia->profesores()->attach($request->profesor_id);
        }

        return back()->with('success', 'Materia asignada exitosamente al semestre.');
    }

    public function removerMateria($semestreId, $materiaId)
    {
        $semestre = semestre::findOrFail($semestreId);
        $materia = materia::findOrFail($materiaId);

        // Verificar si hay estudiantes inscritos en esta materia
        $estudiantesInscritos = DB::table('materia_estudiante_semestres')
            ->where('materia_id', $materiaId)
            ->where('semestre_id', $semestreId)
            ->count();

        if ($estudiantesInscritos > 0) {
            return back()->with('error', 'No se puede remover la materia porque hay estudiantes inscritos.');
        }

        $semestre->materias()->detach($materiaId);

        return back()->with('success', 'Materia removida exitosamente del semestre.');
    }

    public function asignarProfesor(Request $request, $semestreId, $materiaId)
    {
        $request->validate([
            'profesor_id' => 'required|exists:profesors,id',
        ]);

        $materia = materia::findOrFail($materiaId);
        $profesor = Profesor::findOrFail($request->profesor_id);

        // Verificar si el profesor ya está asignado
        if ($materia->profesores->contains($profesor->id)) {
            return back()->with('error', 'Este profesor ya está asignado a la materia.');
        }

        $materia->profesores()->attach($profesor->id);

        return back()->with('success', 'Profesor asignado exitosamente a la materia.');
    }

    public function removerProfesor($semestreId, $materiaId, $profesorId)
    {
        $materia = materia::findOrFail($materiaId);
        $materia->profesores()->detach($profesorId);

        return back()->with('success', 'Profesor removido exitosamente de la materia.');
    }

    public function actualizarConfiguracion(Request $request, $semestreId, $materiaId)
    {
        $request->validate([
            'creditos' => 'required|integer|min:1|max:10',
            'horas_teoricas' => 'nullable|integer|min:0',
            'horas_practicas' => 'nullable|integer|min:0',
            'prerrequisitos' => 'nullable|string',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $semestre = semestre::findOrFail($semestreId);
        
        // Actualizar la configuración de la materia en el semestre
        $semestre->materias()->updateExistingPivot($materiaId, [
            'creditos' => $request->creditos,
            'horas_teoricas' => $request->horas_teoricas ?? 0,
            'horas_practicas' => $request->horas_practicas ?? 0,
            'prerrequisitos' => $request->prerrequisitos,
            'descripcion' => $request->descripcion,
        ]);

        return back()->with('success', 'Configuración de la materia actualizada exitosamente.');
    }

    public function estadisticas($semestreId)
    {
        $semestre = semestre::with(['materias', 'estudiantes', 'carreras'])->findOrFail($semestreId);
        
        $estadisticas = [
            'total_materias' => $semestre->materias->count(),
            'total_estudiantes' => $semestre->estudiantes->count(),
            'total_creditos' => $semestre->materias->sum('creditos'),
            'promedio_creditos' => $semestre->materias->count() > 0 ? round($semestre->materias->avg('creditos'), 1) : 0,
            'materias_con_profesor' => $semestre->materias->filter(function($materia) {
                return $materia->profesores->count() > 0;
            })->count(),
            'materias_sin_profesor' => $semestre->materias->filter(function($materia) {
                return $materia->profesores->count() == 0;
            })->count(),
            'carreras_asociadas' => $semestre->carreras->count(),
        ];

        return response()->json($estadisticas);
    }

    public function exportarHorario($semestreId)
    {
        $semestre = semestre::with(['materias.profesores'])->findOrFail($semestreId);
        
        // Aquí puedes implementar la lógica para exportar el horario
        // Por ejemplo, generar un PDF o Excel con el horario del semestre
        
        return back()->with('success', 'Horario exportado exitosamente.');
    }

    public function validarCargaAcademica($semestreId)
    {
        $semestre = semestre::with(['materias'])->findOrFail($semestreId);
        
        $errores = [];
        $advertencias = [];

        // Validar que todas las materias tengan profesor asignado
        foreach ($semestre->materias as $materia) {
            if ($materia->profesores->count() == 0) {
                $errores[] = "La materia '{$materia->nombre}' no tiene profesor asignado.";
            }
        }

        // Validar que el total de créditos sea razonable
        $totalCreditos = $semestre->materias->sum('creditos');
        if ($totalCreditos > 25) {
            $advertencias[] = "El semestre tiene {$totalCreditos} créditos, que es una carga académica alta.";
        } elseif ($totalCreditos < 15) {
            $advertencias[] = "El semestre tiene {$totalCreditos} créditos, que es una carga académica baja.";
        }

        // Validar que no haya materias duplicadas
        $materiasIds = $semestre->materias->pluck('id')->toArray();
        if (count($materiasIds) !== count(array_unique($materiasIds))) {
            $errores[] = "Hay materias duplicadas en el semestre.";
        }

        return response()->json([
            'errores' => $errores,
            'advertencias' => $advertencias,
            'valido' => empty($errores),
        ]);
    }
} 