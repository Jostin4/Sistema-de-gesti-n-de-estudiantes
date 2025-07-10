<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\estudiante;
use App\Models\carrera;
use App\Models\semestre;
use App\Models\materia;
use App\Models\Seccion;
use App\Models\InscripcionMateria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InscripcionController extends Controller
{
    // Mostrar vista de inscripción inicial (para estudiantes nuevos)
    public function inscripcionInicial()
    {
        $estudiante = estudiante::where('user_id', Auth::id())->first();
        
        if (!$estudiante) {
            return redirect()->route('dashboard')->with('error', 'No se encontró tu perfil de estudiante.');
        }

        // Verificar si ya está inscrito a una carrera
        if ($estudiante->carreras->count() > 0) {
            return redirect()->route('dashboard')->with('info', 'Ya estás inscrito a una carrera.');
        }

        $carreras = carrera::where('estado', 'activa')->get();
        return view('inscripcion.inscripcion-inicial', compact('carreras', 'estudiante'));
    }

    // Procesar inscripción inicial
    public function procesarInscripcionInicial(Request $request)
    {
        $request->validate([
            'carrera_id' => 'required|exists:carreras,id',
        ]);

        $estudiante = estudiante::where('user_id', Auth::id())->first();
        
        if (!$estudiante) {
            return back()->with('error', 'No se encontró tu perfil de estudiante.');
        }

        DB::beginTransaction();
        try {
            // Inscribir a la carrera
            $estudiante->carreras()->attach($request->carrera_id);

            // Obtener el primer semestre de la carrera
            $primerSemestre = semestre::whereHas('carrera', function($query) use ($request) {
                $query->where('carreras.id', $request->carrera_id);
            })->orderBy('id')->first();

            if ($primerSemestre) {
                // Inscribir al primer semestre
                $estudiante->semestres()->attach($primerSemestre->id);

                // Inscribir automáticamente a todas las materias del primer semestre
                $materiasPrimerSemestre = materia::whereHas('semestres', function($query) use ($primerSemestre) {
                    $query->where('semestres.id', $primerSemestre->id);
                })->get();

                foreach ($materiasPrimerSemestre as $materia) {
                    // Obtener la primera sección disponible
                    $seccion = Seccion::where('materia_id', $materia->id)
                        ->where('semestre_id', $primerSemestre->id)
                        ->where('activa', true)
                        ->where('cupo_actual', '<', 'cupo_maximo')
                        ->first();

                    if ($seccion) {
                        InscripcionMateria::create([
                            'estudiante_id' => $estudiante->id,
                            'materia_id' => $materia->id,
                            'semestre_id' => $primerSemestre->id,
                            'seccion_id' => $seccion->id,
                            'estado' => 'inscrito'
                        ]);

                        $seccion->incrementarCupo();
                    }
                }
            }

            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Inscripción realizada exitosamente. ¡Bienvenido a tu carrera!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al procesar la inscripción: ' . $e->getMessage());
        }
    }

    // Mostrar vista de reinscripción
    public function reinscripcion()
    {
        $estudiante = estudiante::where('user_id', Auth::id())->first();
        
        if (!$estudiante) {
            return redirect()->route('dashboard')->with('error', 'No se encontró tu perfil de estudiante.');
        }

        // Obtener semestres con momento académico activo
        $semestresActivos = semestre::where('momento_academico_activo', true)
            ->whereHas('carrera', function($query) use ($estudiante) {
                $query->whereIn('carreras.id', $estudiante->carreras->pluck('id'));
            })
            ->get();

        if ($semestresActivos->isEmpty()) {
            return redirect()->route('dashboard')->with('info', 'No hay momentos académicos activos para reinscripción.');
        }

        return view('inscripcion.reinscripcion', compact('estudiante', 'semestresActivos'));
    }

    // Mostrar materias disponibles para reinscripción
    public function materiasDisponibles($semestreId)
    {
        $estudiante = estudiante::where('user_id', Auth::id())->first();
        $semestre = semestre::findOrFail($semestreId);

        // Verificar si el momento académico está activo
        if (!$semestre->isMomentoAcademicoActivo()) {
            return back()->with('error', 'El momento académico no está activo.');
        }

        // Verificar si puede inscribir materias del siguiente semestre
        if (!$estudiante->puedeInscribirSiguienteSemestre($semestreId)) {
            return back()->with('error', 'No cumples con los requisitos para inscribir materias del siguiente semestre.');
        }

        // Obtener materias disponibles
        $materiasDisponibles = $estudiante->getMateriasDisponiblesReinscripcion($semestreId);
        
        // Obtener secciones disponibles para cada materia
        $materiasConSecciones = [];
        foreach ($materiasDisponibles as $materia) {
            $secciones = Seccion::where('materia_id', $materia->id)
                ->where('semestre_id', $semestreId)
                ->where('activa', true)
                ->where('cupo_actual', '<', 'cupo_maximo')
                ->get();
            
            $materiasConSecciones[] = [
                'materia' => $materia,
                'secciones' => $secciones
            ];
        }

        return view('inscripcion.materias-disponibles', compact('estudiante', 'semestre', 'materiasConSecciones'));
    }

    // Procesar reinscripción
    public function procesarReinscripcion(Request $request)
    {
        $request->validate([
            'semestre_id' => 'required|exists:semestres,id',
            'materias' => 'required|array',
            'materias.*.materia_id' => 'required|exists:materias,id',
            'materias.*.seccion_id' => 'required|exists:secciones,id',
        ]);

        $estudiante = estudiante::where('user_id', Auth::id())->first();
        $semestre = semestre::findOrFail($request->semestre_id);

        // Verificar si el momento académico está activo
        if (!$semestre->isMomentoAcademicoActivo()) {
            return back()->with('error', 'El momento académico no está activo.');
        }

        DB::beginTransaction();
        try {
            foreach ($request->materias as $materiaData) {
                $seccion = Seccion::findOrFail($materiaData['seccion_id']);
                
                // Verificar cupo disponible
                if (!$seccion->tieneCupoDisponible()) {
                    throw new \Exception("No hay cupo disponible en la sección {$seccion->nombre} de {$seccion->materia->nombre}");
                }

                // Crear inscripción
                InscripcionMateria::create([
                    'estudiante_id' => $estudiante->id,
                    'materia_id' => $materiaData['materia_id'],
                    'semestre_id' => $semestre->id,
                    'seccion_id' => $seccion->id,
                    'estado' => 'inscrito'
                ]);

                // Incrementar cupo
                $seccion->incrementarCupo();
            }

            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Reinscripción realizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al procesar la reinscripción: ' . $e->getMessage());
        }
    }

    // Mostrar historial de inscripciones del estudiante
    public function historial()
    {
        $estudiante = estudiante::where('user_id', Auth::id())->first();
        
        if (!$estudiante) {
            return redirect()->route('dashboard')->with('error', 'No se encontró tu perfil de estudiante.');
        }

        $inscripciones = $estudiante->inscripcionesMaterias()
            ->with(['materia', 'semestre', 'seccion'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('inscripcion.historial', compact('estudiante', 'inscripciones'));
    }
} 