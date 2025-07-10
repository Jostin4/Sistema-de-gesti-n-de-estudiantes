<?php

use App\Http\Controllers\carrerasController;
use App\Http\Controllers\estudiantesController;
use App\Http\Controllers\materiasController;
use App\Http\Controllers\seccionesController;
use App\Http\Controllers\semestresController;
use Illuminate\Support\Facades\Route;

// Rutas principales
Route::resource('estudiantes', estudiantesController::class);
Route::resource('materias', materiasController::class);
Route::resource('secciones', seccionesController::class);
Route::resource('carreras', carrerasController::class);
Route::resource('semestres', semestresController::class);

// Acciones personalizadas
Route::delete('/recorridos/{recorrido}', [carrerasController::class, 'destroyInscripcion'])->name('recorridos.destroy');
Route::delete('/carreras/{carrera}/remove-estudiante/{estudiante}', [carrerasController::class, 'removeEstudiante'])->name('carreras.removeEstudiante');
Route::post('/carreras/{carrera}/add-estudiante', [carrerasController::class, 'addEstudiante'])->name('carreras.addEstudiante');

// Ruta para obtener semestres por materia
Route::get('/semestres-por-materia/{materia}', [\App\Http\Controllers\Api\MateriaApiController::class, 'semestres'])->name('api.semestres-por-materia');

