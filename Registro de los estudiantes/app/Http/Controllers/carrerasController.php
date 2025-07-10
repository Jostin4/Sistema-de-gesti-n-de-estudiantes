<?php

namespace App\Http\Controllers;

use App\Models\carrera;
use App\Models\estudiante;
use Illuminate\Http\Request;

class carrerasController extends Controller
{
    public function index()
    {
        $carreras = carrera::with('semestres')->get();
        return view('carreras.index', compact('carreras'));
    }

    public function create()
    {
        return view('carreras.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nivel_educacion' => 'required|in:Técnico,Ingeniería,Licenciatura,Maestría,Doctorado',
            'descripcion' => 'nullable|string|max:1000',
            'codigo' => 'nullable|string|max:10|unique:carreras,codigo',
        ], [
            'nombre.required' => 'El nombre de la carrera es obligatorio.',
            'nivel_educacion.required' => 'El nivel educativo es obligatorio.',
            'nivel_educacion.in' => 'El nivel educativo debe ser válido.',
            'codigo.unique' => 'Este código de carrera ya está en uso.',
        ]);

        $carrera = carrera::create([
            'nombre' => $request->nombre,
            'nivel_educacion' => $request->nivel_educacion,
            'descripcion' => $request->descripcion,
            'codigo' => $request->codigo,
            'estado' => true,
        ]);

        // Asignar semestres automáticamente según el nivel educativo
        $carrera->asignarSemestresAutomaticamente();

        return redirect()->route('carreras.show', $carrera->id)->with('success', 'Carrera creada exitosamente con ' . $carrera->numero_semestres . ' semestres asignados automáticamente');
    }

    public function edit($id)
    {
        $carrera = carrera::findOrFail($id);
        return view('carreras.edit', compact('carrera'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nivel_educacion' => 'required|in:Técnico,Ingeniería,Licenciatura,Maestría,Doctorado',
            'descripcion' => 'nullable|string|max:1000',
            'codigo' => 'nullable|string|max:10|unique:carreras,codigo,' . $id,
        ], [
            'nombre.required' => 'El nombre de la carrera es obligatorio.',
            'nivel_educacion.required' => 'El nivel educativo es obligatorio.',
            'nivel_educacion.in' => 'El nivel educativo debe ser válido.',
            'codigo.unique' => 'Este código de carrera ya está en uso.',
        ]);
        
        $carrera = carrera::findOrFail($id);
        
        // Si cambió el nivel educativo, reasignar semestres automáticamente
        if ($carrera->nivel_educacion !== $request->nivel_educacion) {
            $carrera->asignarSemestresAutomaticamente();
        }
        
        $carrera->update([
            'nombre' => $request->nombre,
            'nivel_educacion' => $request->nivel_educacion,
            'descripcion' => $request->descripcion,
            'codigo' => $request->codigo,
            'estado' => $request->has('estado'),
        ]);

        return redirect()->route('carreras.show', $carrera->id)->with('success', 'Carrera actualizada exitosamente');
    }

    // Método para asignar semestres manualmente (por si acaso)
    public function asignarSemestres($id)
    {
        $carrera = carrera::findOrFail($id);
        $semestresAsignados = $carrera->asignarSemestresAutomaticamente();
        
        return redirect()->route('carreras.show', $carrera->id)->with('success', 'Se asignaron ' . $semestresAsignados->count() . ' semestres automáticamente a la carrera');
    }

    public function show($id)
    {
        $carrera = carrera::with('semestres', 'estudiantes')->findOrFail($id);
        // Solo muestra los estudiantes que aún NO están inscritos
        $estudiantesDisponibles = estudiante::whereNotIn('id', $carrera->estudiantes->pluck('id'))->get();
        return view('carreras.show', compact('carrera', 'estudiantesDisponibles'));
    }
    
    public function addEstudiante(Request $request, $carreraId)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
        ]);
        $carrera = carrera::findOrFail($carreraId);
        $carrera->estudiantes()->attach($request->estudiante_id);
        return redirect()->route('carreras.show', $carrera->id)->with('success', 'Estudiante añadido correctamente.');
    }

    public function destroy($id)
    {
        $carrera = carrera::findOrFail($id);
        $carrera->delete();

        return redirect()->route('carreras.index')->with('success', 'Carrera eliminada exitosamente');
    }
    public function removeEstudiante($carreraId, $estudianteId)
    {
        $carrera = carrera::findOrFail($carreraId);
        $carrera->estudiantes()->detach($estudianteId);
        return redirect()->route('carreras.show', $carrera->id)->with('success', 'Estudiante removido correctamente.');
    }

    /**
     * Permite que un estudiante se inscriba a una carrera y automáticamente al primer semestre
     */
    public function inscribirEstudiante($carreraId)
    {
        // Verificar que el usuario autenticado es un estudiante
        $user = auth()->user();
        if (!$user || $user->role !== 'estudiante') {
            abort(403, 'Solo los estudiantes pueden inscribirse a carreras.');
        }

        // Obtener el registro de estudiante
        $estudiante = $user->estudiante;
        if (!$estudiante) {
            abort(403, 'No tienes un perfil de estudiante válido.');
        }

        $carrera = carrera::with(['semestres.materias'])->findOrFail($carreraId);

        // Verificar que la carrera esté activa
        if (!$carrera->estado) {
            return redirect()->route('carreras.show', $carrera->id)
                ->with('error', 'Esta carrera no está disponible para inscripciones.');
        }

        // Verificar que el estudiante no esté ya inscrito en esta carrera
        if ($carrera->estudiantes->contains($estudiante->id)) {
            return redirect()->route('carreras.show', $carrera->id)
                ->with('info', 'Ya estás inscrito en esta carrera.');
        }

        // Obtener el primer semestre de la carrera
        $primerSemestre = $carrera->semestres->sortBy('id')->first();
        
        if (!$primerSemestre) {
            return redirect()->route('carreras.show', $carrera->id)
                ->with('error', 'Esta carrera no tiene semestres disponibles.');
        }

        // Obtener todas las materias del primer semestre
        $materiasPrimerSemestre = $primerSemestre->materias;

        try {
            // Inscribir al estudiante en la carrera
            $carrera->estudiantes()->attach($estudiante->id);

            // Inscribir al estudiante en el primer semestre
            $estudiante->semestres()->attach($primerSemestre->id);

            // Inscribir al estudiante en todas las materias del primer semestre
            $materiasInscritas = [];
            foreach ($materiasPrimerSemestre as $materia) {
                // Verificar si ya existe la relación en materia_estudiante_semestres
                $existeInscripcion = \App\Models\MateriaEstudianteSemestre::where([
                    'estudiante_id' => $estudiante->id,
                    'materia_id' => $materia->id,
                    'semestre_id' => $primerSemestre->id
                ])->exists();

                if (!$existeInscripcion) {
                    \App\Models\MateriaEstudianteSemestre::create([
                        'estudiante_id' => $estudiante->id,
                        'materia_id' => $materia->id,
                        'semestre_id' => $primerSemestre->id,
                        'estado' => 'inscrito'
                    ]);
                    $materiasInscritas[] = $materia->nombre;
                }
            }

            // Preparar mensaje de éxito
            $mensaje = "¡Te has inscrito exitosamente en {$carrera->nombre}! ";
            $mensaje .= "Has sido asignado automáticamente al {$primerSemestre->nombre}";
            
            if (count($materiasInscritas) > 0) {
                $mensaje .= " y te has inscrito en " . count($materiasInscritas) . " materias: ";
                $mensaje .= implode(', ', $materiasInscritas);
            } else {
                $mensaje .= ". No hay materias disponibles en este semestre por el momento.";
            }

            return redirect()->route('carreras.show', $carrera->id)
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            return redirect()->route('carreras.show', $carrera->id)
                ->with('error', 'Error al procesar la inscripción. Por favor, intenta nuevamente.');
        }
    }
}
