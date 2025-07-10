<?php

namespace App\Http\Controllers;

use App\Models\carrera;
use App\Models\semestre;
use App\Models\recorrido_academico;
use App\Models\estudiante;
use App\Models\materia;
use App\Models\sección;
use Illuminate\Http\Request;

class semestresController extends Controller
{

    public function create()
    {
        $carreras = carrera::all();
        return view('semestres.create', compact('carreras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'carrera_id' => 'required|exists:carreras,id',
        ]);

        // Crear el semestre
        $semestre = semestre::create([
            'nombre' => $request->nombre,
        ]);

        // Asociar el semestre con la carrera
        $carrera = carrera::findOrFail($request->carrera_id);
        $carrera->semestres()->attach($semestre->id);

        return redirect()->route('semestres.index')->with('success', 'Semestre creado exitosamente');
    }

    public function associateSemestresForm(carrera $carrera)
    {
        $semestres = semestre::all();  // Obtener todos los semestres disponibles
        $carreraSemestres = $carrera->semestres->pluck('id')->toArray();  // IDs de semestres ya asociados

        return view('semestres.associate_semestres', compact('carrera', 'semestres', 'carreraSemestres'));
    }

    /**
     * Asocia (sincroniza) los semestres seleccionados con una carrera.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\carrera  $carrera
     * @return \Illuminate\Http\RedirectResponse
     */
    public function associateSemestres(Request $request, carrera $carrera)
    {
        $request->validate([
            'semestres' => 'nullable|array',
            'semestres.*' => 'exists:semestres,id',  // Asegura que los IDs existen en la tabla semestres
        ]);

        // Sincroniza los semestres. Esto adjuntará los nuevos, desvinculará los eliminados
        // y mantendrá los existentes.
        $carrera->semestres()->sync($request->input('semestres', []));

        return redirect()->route('carreras.show', $carrera->id)->with('success', 'Semestres asociados exitosamente.');
    }

    public function unifiedAction(Request $request, carrera $carrera)
    {
        $action = $request->input('action_type');
        $ids = $request->input('semestres', []);

        if ($action === 'associate') {
            // Asociar los semestres seleccionados a la carrera
            $carrera->semestres()->sync($ids);
            return back()->with('success', 'Semestres asociados correctamente.');
        } elseif ($action === 'delete') {
            // Eliminar los semestres seleccionados
            if (!empty($ids)) {
                semestre::whereIn('id', $ids)->delete();
                return back()->with('success', 'Semestres eliminados correctamente.');
            }
            return back()->with('error', 'No seleccionaste ningún semestre para eliminar.');
        } else {
            return back()->with('error', 'Acción no reconocida.');
        }
    }

    public function index()
    {
        // La vista unificada ya carga las relaciones necesarias directamente
        // No necesitamos pasar datos adicionales desde el controlador
        return view('semestres.index');
    }

    public function show($id)
    {
        $semestre = semestre::findOrFail($id);
        $recorridos = recorrido_academico::with(['estudiante', 'materia', 'seccion'])->where('semestres_id', $id)->get();
        $estudiantes = estudiante::all();
        $materias = materia::all();
        // Si tienes secciones:
        $secciones = sección::all();

        return view('semestres.show', compact('semestre', 'recorridos', 'estudiantes', 'materias', 'secciones'));
    }

    public function inscribir(Request $request, $semestreId)
    {
        $request->validate([
            'estudiantes_id' => 'required|exists:estudiantes,id',
            'materias_id' => 'required|exists:materias,id',
            // Valida sección si corresponde
        ]);
        recorido_academico::create([
            'estudiantes_id' => $request->estudiantes_id,
            'materias_id' => $request->materias_id,
            'semestres_id' => $semestreId,
            'secciones_id' => $request->secciones_id ?? null,
            'calificacion' => null,
            'estado' => 'inscrito',
        ]);
        return back()->with('success', 'Inscripción realizada correctamente');
    }

    public function destroyInscripcion($recorridoId)
    {
        recorido_academico::findOrFail($recorridoId)->delete();
        return back()->with('success', 'Inscripción eliminada');
    }

    public function massDestroy(Request $request)
    {
        $ids = $request->input('semestres_to_delete', []);
        if (!empty($ids)) {
            semestre::whereIn('id', $ids)->delete();
            return back()->with('success', 'Semestres eliminados exitosamente.');
        }
        return back()->with('error', 'No seleccionaste ningún semestre para eliminar.');
    }

    public function destroy($id)
    {
        $semestre = semestre::findOrFail($id);
        $semestre->delete();

        return redirect()->route('semestres.index')->with('success', 'Semestre eliminado exitosamente.');
    }
}
