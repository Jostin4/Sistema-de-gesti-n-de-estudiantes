<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profesor;
use App\Models\User;
use App\Models\materia;
use Illuminate\Support\Facades\Hash;

class ProfesorController extends Controller
{
    // Mostrar lista de profesores
    public function index()
    {
        $profesores = Profesor::with('user')->get();
        return view('profesores.index', compact('profesores'));
    }

    // Mostrar formulario de creación de profesor
    public function create()
    {
        return view('profesores.create');
    }

    // Guardar nuevo profesor y usuario asociado
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'profesor',
        ]);

        Profesor::create([
            'user_id' => $user->id,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
        ]);

        return redirect()->route('profesores.index')->with('success', 'Profesor creado correctamente');
    }

    // Mostrar materias asignadas y formulario para asignar más
    public function materias($profesorId)
    {
        $profesor = Profesor::findOrFail($profesorId);
        $materias = materia::all();
        $materiasAsignadas = $profesor->materias->pluck('id')->toArray();
        return view('profesores.materias', compact('profesor', 'materias', 'materiasAsignadas'));
    }

    // Asignar materias a un profesor
    public function asignarMaterias(Request $request, $profesorId)
    {
        $profesor = Profesor::findOrFail($profesorId);
        $request->validate([
            'materias' => 'array',
        ]);
        $profesor->materias()->sync($request->materias ?? []);
        return redirect()->route('profesores.materias', $profesorId)->with('success', 'Materias asignadas correctamente');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $profesor = Profesor::with('user')->findOrFail($id);
        return view('profesores.edit', compact('profesor'));
    }

    // Actualizar profesor
    public function update(Request $request, $id)
    {
        $profesor = Profesor::with('user')->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $profesor->user->id,
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
        ]);

        $profesor->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $profesor->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
        ]);

        return redirect()->route('profesores.index')->with('success', 'Profesor actualizado correctamente');
    }

    // Eliminar profesor
    public function destroy($id)
    {
        $profesor = Profesor::with('user')->findOrFail($id);
        $profesor->user->delete(); // Esto también eliminará el profesor por la relación
        return redirect()->route('profesores.index')->with('success', 'Profesor eliminado correctamente');
    }
}
