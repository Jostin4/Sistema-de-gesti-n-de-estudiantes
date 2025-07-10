<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\materia;
use App\Models\semestre;
use App\Models\sección;

class materiasController extends Controller
{
    // Listar todas las materias
    public function index()
    {
        $materias = materia::with(['semestres.carreras'])->get();
        return view('materias.index', compact('materias'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $carreras = \App\Models\carrera::with('semestres')->get();
        return view('materias.create', compact('carreras'));
    }
    
    // Guardar nueva materia
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'semestre_id' => 'required|exists:semestres,id',
            'codigo' => 'nullable|string|max:10|unique:materias,codigo',
            'creditos' => 'nullable|integer|min:1|max:10',
        ]);
    
        // Crear la materia (sin carreras_id)
        $materia = materia::create([
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'creditos' => $request->creditos ?? 3,
            'estado' => 'Activo',
        ]);

        // Asociar la materia al semestre seleccionado
        $materia->semestres()->attach($request->semestre_id);
    
        return redirect()->route('materias.index')->with('success', 'Materia creada y asignada al semestre correctamente');
    }

    // Mostrar materia específica
    public function show($id)
    {
        $materia = materia::with(['semestres.carreras'])->findOrFail($id);
        return view('materias.show', compact('materia'));
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $materia = materia::findOrFail($id);
        $carreras = \App\Models\carrera::with('semestres')->get();
        return view('materias.edit', compact('materia', 'carreras'));
    }

    // Actualizar materia
    public function update(Request $request, $id)
    {
        $materia = materia::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        $materia->update($request->only(['nombre', 'estado']));
        
        return redirect()->route('materias.index')->with('success', 'Materia actualizada correctamente');
    }

    // Eliminar materia
    public function destroy($id)
    {
        $materia = materia::findOrFail($id);
        $materia->delete();
        
        return redirect()->route('materias.index')->with('success', 'Materia eliminada correctamente');
    }

    // Obtener materias por carrera y semestre (API)
    public function getMateriasByCarreraSemestre($carreraId, $semestreId)
    {
        $materias = materia::whereHas('semestres', function($query) use ($semestreId, $carreraId) {
                              $query->where('semestres.id', $semestreId)
                                    ->whereHas('carreras', function($subQuery) use ($carreraId) {
                                        $subQuery->where('carreras.id', $carreraId);
                                    });
                          })
                          ->get();
        
        return response()->json($materias);
    }
}
