<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\estudiante;
use App\Models\carrera;
use App\Models\semestre;
use App\Models\materia;
use App\Models\Nota;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class estudiantesController extends Controller
{
    public function index()
    {
        $estudiantes = estudiante::with(['carreras' => function($query) {
            $query->distinct();
        }, 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('estudiantes.index', compact('estudiantes'));
    }

    public function create()
    {
        $carreras = carrera::where('estado', true)->get();
        return view('estudiantes.create', compact('carreras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'segundo_nombre' => 'nullable|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellido_paterno' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellido_materno' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'fecha_nacimiento' => 'required|date|before:today|after:1900-01-01',
            'correo' => 'required|email|max:255|unique:estudiantes,correo',
            'telefono' => 'required|string|max:20|regex:/^[0-9+\-\s\(\)]+$/',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'direccion' => 'nullable|string|max:500',
            'ciudad' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:10',
            'carrera_id' => 'nullable|exists:carreras,id',
            'crear_usuario' => 'boolean',
            'password' => 'required_if:crear_usuario,1|nullable|string|min:8|confirmed',
        ], [
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido_paterno.regex' => 'El apellido paterno solo puede contener letras y espacios.',
            'apellido_materno.regex' => 'El apellido materno solo puede contener letras y espacios.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'fecha_nacimiento.after' => 'La fecha de nacimiento debe ser posterior a 1900.',
            'correo.unique' => 'Este correo electrónico ya está registrado.',
            'telefono.regex' => 'El teléfono debe contener solo números, espacios, guiones y paréntesis.',
            'genero.in' => 'El género debe ser Masculino, Femenino u Otro.',
            'password.required_if' => 'La contraseña es requerida cuando se crea un usuario.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        DB::beginTransaction();
        try {
            // Generar matrícula automática
            $matricula = $this->generarMatricula($request->apellido_paterno, $request->nombre);

            // Crear estudiante
            $estudiante = estudiante::create([
                'nombre' => ucwords(strtolower($request->nombre)),
                'segundo_nombre' => $request->segundo_nombre ? ucwords(strtolower($request->segundo_nombre)) : null,
                'apellido_paterno' => ucwords(strtolower($request->apellido_paterno)),
                'apellido_materno' => ucwords(strtolower($request->apellido_materno)),
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'correo' => strtolower($request->correo),
                'telefono' => $request->telefono,
                'genero' => $request->genero,
                'matricula' => $matricula,
                'direccion' => $request->direccion,
                'ciudad' => $request->ciudad,
                'estado_direccion' => $request->estado,
                'codigo_postal' => $request->codigo_postal,
                'estado_estudiante' => 'Activo',
            ]);

            // Crear usuario si se solicita
            if ($request->crear_usuario) {
                $user = User::create([
                    'name' => $estudiante->nombre . ' ' . $estudiante->apellido_paterno,
                    'email' => $estudiante->correo,
                    'password' => Hash::make($request->password),
                    'role' => 'estudiante',
                ]);

                $estudiante->update(['user_id' => $user->id]);
            }

            // Asignar carrera si se selecciona
            if ($request->carrera_id) {
                $estudiante->carreras()->attach($request->carrera_id);
            }

            DB::commit();

            $mensaje = 'Estudiante creado exitosamente';
            if ($request->crear_usuario) {
                $mensaje .= ' y cuenta de usuario generada';
            }

            return redirect()->route('estudiantes.show', $estudiante->id)
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Error al crear el estudiante: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $estudiante = estudiante::with(['carreras', 'user'])->findOrFail($id);
        $carreras = carrera::where('estado', true)->get();
        return view('estudiantes.edit', compact('estudiante', 'carreras'));
    }

    public function update(Request $request, $id)
    {
        $estudiante = estudiante::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'segundo_nombre' => 'nullable|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellido_paterno' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellido_materno' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'fecha_nacimiento' => 'required|date|before:today|after:1900-01-01',
            'correo' => 'required|email|max:255|unique:estudiantes,correo,' . $id,
            'telefono' => 'required|string|max:20|regex:/^[0-9+\-\s\(\)]+$/',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'direccion' => 'nullable|string|max:500',
            'ciudad' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:10',
            'carrera_id' => 'nullable|exists:carreras,id',
            'estado_estudiante' => 'required|in:Activo,Inactivo',
        ], [
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido_paterno.regex' => 'El apellido paterno solo puede contener letras y espacios.',
            'apellido_materno.regex' => 'El apellido materno solo puede contener letras y espacios.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'fecha_nacimiento.after' => 'La fecha de nacimiento debe ser posterior a 1900.',
            'correo.unique' => 'Este correo electrónico ya está registrado.',
            'telefono.regex' => 'El teléfono debe contener solo números, espacios, guiones y paréntesis.',
            'genero.in' => 'El género debe ser Masculino, Femenino u Otro.',
        ]);

        DB::beginTransaction();
        try {
            $estudiante->update([
                'nombre' => ucwords(strtolower($request->nombre)),
                'segundo_nombre' => $request->segundo_nombre ? ucwords(strtolower($request->segundo_nombre)) : null,
                'apellido_paterno' => ucwords(strtolower($request->apellido_paterno)),
                'apellido_materno' => ucwords(strtolower($request->apellido_materno)),
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'correo' => strtolower($request->correo),
                'telefono' => $request->telefono,
                'genero' => $request->genero,
                'direccion' => $request->direccion,
                'ciudad' => $request->ciudad,
                'estado' => $request->estado,
                'codigo_postal' => $request->codigo_postal,
                'estado' => $request->estado_estudiante,
            ]);

            // Actualizar carreras
            if ($request->carrera_id) {
                $estudiante->carreras()->sync([$request->carrera_id]);
            } else {
                $estudiante->carreras()->detach();
            }

            // Actualizar usuario si existe
            if ($estudiante->user) {
                $estudiante->user->update([
                    'name' => $estudiante->nombre . ' ' . $estudiante->apellido_paterno,
                    'email' => $estudiante->correo,
                ]);
            }

            DB::commit();

            return redirect()->route('estudiantes.show', $estudiante->id)
                ->with('success', 'Estudiante actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Error al actualizar el estudiante: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $estudiante = estudiante::with(['carreras', 'semestres', 'materias', 'notas.evaluacion.materia', 'user'])->findOrFail($id);
        
        // Obtener carreras del estudiante sin duplicados
        $carreras = $estudiante->carreras()->distinct()->get();
        
        // Obtener semestres del estudiante sin duplicados
        $semestres = $estudiante->semestres()->distinct()->get();
        
        // Obtener materias del estudiante sin duplicados
        $materias = $estudiante->materias()->distinct()->get();
        
        // Obtener todas las notas del estudiante
        $notas = $estudiante->notas;
        
        // Calcular estadísticas
        $totalNotas = $notas->count();
        $promedioGeneral = $estudiante->promedio_general;
        $notasAprobadas = $notas->where('nota', '>=', 50)->count();
        $notasReprobadas = $totalNotas - $notasAprobadas;
        $porcentajeAprobacion = $totalNotas > 0 ? ($notasAprobadas / $totalNotas) * 100 : 0;
        
        // Obtener materias aprobadas y reprobadas
        $materiasAprobadas = collect();
        $materiasReprobadas = collect();
        
        foreach ($materias as $materia) {
            $notasMateria = $notas->where('evaluacion.materia_id', $materia->id);
            if ($notasMateria->count() > 0) {
                $promedioMateria = $notasMateria->avg('nota');
                if ($promedioMateria >= 50) {
                    $materiasAprobadas->push([
                        'materia' => $materia,
                        'promedio' => $promedioMateria,
                        'notas' => $notasMateria
                    ]);
                } else {
                    $materiasReprobadas->push([
                        'materia' => $materia,
                        'promedio' => $promedioMateria,
                        'notas' => $notasMateria
                    ]);
                }
            }
        }
        
        // Obtener semestres aprobados y en curso
        $semestresAprobados = collect();
        $semestresEnCurso = collect();
        
        foreach ($semestres as $semestre) {
            $materiasSemestre = $materias->where('pivot.semestre_id', $semestre->id);
            $todasAprobadas = true;
            $tieneNotas = false;
            
            foreach ($materiasSemestre as $materia) {
                $notasMateria = $notas->where('evaluacion.materia_id', $materia->id);
                if ($notasMateria->count() > 0) {
                    $tieneNotas = true;
                    $promedioMateria = $notasMateria->avg('nota');
                    if ($promedioMateria < 50) {
                        $todasAprobadas = false;
                    }
                }
            }
            
            if ($tieneNotas) {
                if ($todasAprobadas) {
                    $semestresAprobados->push($semestre);
                } else {
                    $semestresEnCurso->push($semestre);
                }
            }
        }
        
        return view('estudiantes.show', compact(
            'estudiante', 
            'carreras', 
            'semestres', 
            'materias', 
            'notas', 
            'promedioGeneral', 
            'totalNotas', 
            'notasAprobadas', 
            'notasReprobadas', 
            'porcentajeAprobacion',
            'materiasAprobadas',
            'materiasReprobadas',
            'semestresAprobados',
            'semestresEnCurso'
        ));
    }

    public function destroy($id)
    {
        $estudiante = estudiante::with('user')->findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Eliminar usuario asociado si existe
            if ($estudiante->user) {
                $estudiante->user->delete();
            }
            
            $estudiante->delete();
            
            DB::commit();
            return redirect()->route('estudiantes.index')->with('success', 'Estudiante eliminado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al eliminar el estudiante: ' . $e->getMessage());
        }
    }

    // Método para generar matrícula automática
    private function generarMatricula($apellido, $nombre)
    {
        $anio = date('Y');
        $apellidoInicial = strtoupper(substr($apellido, 0, 1));
        $nombreInicial = strtoupper(substr($nombre, 0, 1));
        
        // Buscar el último número de matrícula del año actual
        $ultimaMatricula = estudiante::where('matricula', 'like', $anio . $apellidoInicial . $nombreInicial . '%')
            ->orderBy('matricula', 'desc')
            ->first();
        
        if ($ultimaMatricula) {
            $ultimoNumero = intval(substr($ultimaMatricula->matricula, -4));
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }
        
        return $anio . $apellidoInicial . $nombreInicial . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
    }

    // Método para buscar estudiantes
    public function buscar(Request $request)
    {
        $query = $request->get('q');
        $genero = $request->get('genero');
        $estado = $request->get('estado');
        
        $estudiantes = estudiante::with(['carreras', 'user']);
        
        // Filtro de búsqueda por texto
        if ($query) {
            $estudiantes->where(function($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                  ->orWhere('apellido_paterno', 'like', "%{$query}%")
                  ->orWhere('apellido_materno', 'like', "%{$query}%")
                  ->orWhere('matricula', 'like', "%{$query}%")
                  ->orWhere('correo', 'like', "%{$query}%");
            });
        }
        
        // Filtro por género
        if ($genero) {
            $estudiantes->where('genero', $genero);
        }
        
        // Filtro por estado
        if ($estado) {
            $estudiantes->where('estado_estudiante', $estado);
        }
        
        $estudiantes = $estudiantes->orderBy('created_at', 'desc')->paginate(15);
        
        return view('estudiantes.index', compact('estudiantes', 'query', 'genero', 'estado'));
    }

    // Método para exportar estudiantes
    public function exportar()
    {
        $estudiantes = estudiante::with(['carreras', 'user'])->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="estudiantes.csv"',
        ];
        
        $callback = function() use ($estudiantes) {
            $file = fopen('php://output', 'w');
            
            // Encabezados
            fputcsv($file, [
                'Matrícula', 'Nombre', 'Apellido Paterno', 'Apellido Materno', 
                'Fecha Nacimiento', 'Correo', 'Teléfono', 'Género', 
                'Carrera', 'Estado', 'Fecha Registro'
            ]);
            
            // Datos
            foreach ($estudiantes as $estudiante) {
                fputcsv($file, [
                    $estudiante->matricula,
                    $estudiante->nombre,
                    $estudiante->apellido_paterno,
                    $estudiante->apellido_materno,
                    $estudiante->fecha_nacimiento,
                    $estudiante->correo,
                    $estudiante->telefono,
                    $estudiante->genero,
                    $estudiante->carreras->first()->nombre ?? 'Sin carrera',
                    $estudiante->estado,
                    $estudiante->created_at->format('d/m/Y')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
