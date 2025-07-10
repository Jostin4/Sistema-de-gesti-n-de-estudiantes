<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\EstudianteNotasController;
use App\Http\Controllers\MateriaEstudianteSemestreController;
use App\Http\Controllers\estudianteController;
use App\Http\Controllers\materiasController;
use App\Http\Controllers\semestresController;
use App\Http\Controllers\seccionesController;
use App\Http\Controllers\carrerasController;
use App\Http\Controllers\estudiantesController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\MomentoAcademicoController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'loginView'])->name('loginView');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'registerView'])->name('registerView');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas para administradores
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Gestión de profesores
    Route::get('/profesores', [\App\Http\Controllers\ProfesorController::class, 'index'])->name('profesores.index');
    Route::get('/profesores/create', [\App\Http\Controllers\ProfesorController::class, 'create'])->name('profesores.create');
    Route::post('/profesores', [\App\Http\Controllers\ProfesorController::class, 'store'])->name('profesores.store');
    Route::get('/profesores/{profesor}/edit', [\App\Http\Controllers\ProfesorController::class, 'edit'])->name('profesores.edit');
    Route::put('/profesores/{profesor}', [\App\Http\Controllers\ProfesorController::class, 'update'])->name('profesores.update');
    Route::delete('/profesores/{profesor}', [\App\Http\Controllers\ProfesorController::class, 'destroy'])->name('profesores.destroy');
    Route::get('/profesores/{profesor}/materias', [\App\Http\Controllers\ProfesorController::class, 'materias'])->name('profesores.materias');
    Route::post('/profesores/{profesor}/materias', [\App\Http\Controllers\ProfesorController::class, 'asignarMaterias'])->name('profesores.asignarMaterias');
    
    // Gestión de carreras
    Route::resource('carreras', carrerasController::class);
    Route::post('/carreras/{carrera}/asignar-semestres', [carrerasController::class, 'asignarSemestres'])->name('carreras.asignar-semestres');
    
    // Inscripción de estudiantes a carreras
    Route::post('/carreras/{carrera}/inscribirme', [carrerasController::class, 'inscribirEstudiante'])->name('carreras.inscribirme');
    
    // Gestión de estudiantes
    Route::get('/estudiantes/buscar', [estudiantesController::class, 'buscar'])->name('estudiantes.buscar');
    Route::get('/estudiantes/exportar', [estudiantesController::class, 'exportar'])->name('estudiantes.exportar');
    Route::resource('estudiantes', estudiantesController::class);
    
    // Gestión de semestres
    Route::resource('semestres', semestresController::class);
    
    // Asociación de semestres con carreras
    Route::get('/carreras/{carrera}/associate-semestres', [semestresController::class, 'associateSemestresForm'])->name('semestres.associateSemestresForm');
    Route::post('/carreras/{carrera}/associate-semestres', [semestresController::class, 'associateSemestres'])->name('semestres.associateSemestres');
    Route::post('/carreras/{carrera}/unified-action', [semestresController::class, 'unifiedAction'])->name('semestres.unifiedAction');
    
    // Gestión de estudiantes en semestres
    Route::post('/semestres/{semestre}/inscribir', [semestresController::class, 'inscribir'])->name('semestres.inscribir');
    Route::delete('/semestres/{semestre}/remove-estudiante/{estudiante}', [semestresController::class, 'removeEstudiante'])->name('semestres.removeEstudiante');
    Route::delete('/semestres/mass-destroy', [semestresController::class, 'massDestroy'])->name('semestres.massDestroy');
    
    // Gestión de materias
    Route::resource('materias', materiasController::class);
    
    // Gestión de materias por semestre
    Route::prefix('materia-semestre')->name('materia-semestre.')->group(function () {
        Route::get('/', [\App\Http\Controllers\MateriaSemestreController::class, 'index'])->name('index');
        Route::get('/{semestre}', [\App\Http\Controllers\MateriaSemestreController::class, 'show'])->name('show');
        
        // Gestión de materias
        Route::post('/{semestre}/asignar-materia', [\App\Http\Controllers\MateriaSemestreController::class, 'asignarMateria'])->name('asignar-materia');
        Route::delete('/{semestre}/remover-materia/{materia}', [\App\Http\Controllers\MateriaSemestreController::class, 'removerMateria'])->name('remover-materia');
        
        // Gestión de profesores
        Route::post('/{semestre}/{materia}/asignar-profesor', [\App\Http\Controllers\MateriaSemestreController::class, 'asignarProfesor'])->name('asignar-profesor');
        Route::delete('/{semestre}/{materia}/remover-profesor/{profesor}', [\App\Http\Controllers\MateriaSemestreController::class, 'removerProfesor'])->name('remover-profesor');
        
        // Configuración de materias
        Route::put('/{semestre}/{materia}/configuracion', [\App\Http\Controllers\MateriaSemestreController::class, 'actualizarConfiguracion'])->name('configuracion');
        
        // Estadísticas y validaciones
        Route::get('/{semestre}/estadisticas', [\App\Http\Controllers\MateriaSemestreController::class, 'estadisticas'])->name('estadisticas');
        Route::get('/{semestre}/validar-carga', [\App\Http\Controllers\MateriaSemestreController::class, 'validarCargaAcademica'])->name('validar-carga');
        Route::get('/{semestre}/exportar-horario', [\App\Http\Controllers\MateriaSemestreController::class, 'exportarHorario'])->name('exportar-horario');
    });
    
    // Asignación de materias a estudiantes en semestres
    Route::get('/materia-estudiante-semestre', [\App\Http\Controllers\MateriaEstudianteSemestreController::class, 'index'])->name('materiaEstudianteSemestre.index');
    Route::post('/materia-estudiante-semestre', [\App\Http\Controllers\MateriaEstudianteSemestreController::class, 'store'])->name('materiaEstudianteSemestre.store');
});

// Rutas para profesores y administradores (evaluaciones y notas)
Route::middleware(['auth', 'role:admin,profesor'])->group(function () {
    // Rutas para evaluaciones
    Route::get('/evaluaciones', [\App\Http\Controllers\EvaluacionController::class, 'index'])->name('evaluaciones.index');
    Route::post('/evaluaciones', [\App\Http\Controllers\EvaluacionController::class, 'store'])->name('evaluaciones.store');
    Route::get('/evaluaciones/{materia}/{semestre}', [\App\Http\Controllers\EvaluacionController::class, 'show'])->name('evaluaciones.show');
    Route::get('/evaluaciones/{materia}/{semestre}/create', [\App\Http\Controllers\EvaluacionController::class, 'create'])->name('evaluaciones.create');
    
    // Rutas para notas
    Route::get('/notas', [\App\Http\Controllers\NotaController::class, 'index'])->name('notas.index');
    Route::get('/notas/{evaluacion}/cargar', [\App\Http\Controllers\NotaController::class, 'cargar'])->name('notas.cargar');
    Route::post('/notas/{evaluacion}/guardar', [\App\Http\Controllers\NotaController::class, 'guardar'])->name('notas.guardar');
});

// Rutas para estudiantes
Route::middleware(['auth', 'role:estudiante'])->group(function () {
    Route::get('/mis-cursos', [\App\Http\Controllers\EstudianteNotasController::class, 'index'])->name('estudiante.dashboard');
    Route::get('/mis-cursos/{semestre}', [\App\Http\Controllers\EstudianteNotasController::class, 'semestre'])->name('estudiante.semestre');
    Route::get('/mis-cursos/{semestre}/{materia}', [\App\Http\Controllers\EstudianteNotasController::class, 'materia'])->name('estudiante.materia');
    Route::get('/mis-notas', [\App\Http\Controllers\EstudianteNotasController::class, 'notas'])->name('estudiante.notas');
});

// Rutas para estudiantes (inscripción y reinscripción)
Route::middleware(['auth', 'role:estudiante'])->group(function () {
    // Inscripción inicial
    Route::get('/inscripcion-inicial', [\App\Http\Controllers\InscripcionController::class, 'inscripcionInicial'])->name('inscripcion.inicial');
    Route::post('/inscripcion-inicial', [\App\Http\Controllers\InscripcionController::class, 'procesarInscripcionInicial'])->name('inscripcion.procesar-inicial');
    
    // Reinscripción
    Route::get('/reinscripcion', [\App\Http\Controllers\InscripcionController::class, 'reinscripcion'])->name('reinscripcion.index');
    Route::get('/reinscripcion/{semestre}/materias', [\App\Http\Controllers\InscripcionController::class, 'materiasDisponibles'])->name('reinscripcion.materias');
    Route::post('/reinscripcion', [\App\Http\Controllers\InscripcionController::class, 'procesarReinscripcion'])->name('reinscripcion.procesar');
    
    // Historial de inscripciones
    Route::get('/historial-inscripciones', [\App\Http\Controllers\InscripcionController::class, 'historial'])->name('inscripcion.historial');
});

// Rutas para administradores (gestión de momentos académicos)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/momentos-academicos', [\App\Http\Controllers\MomentoAcademicoController::class, 'index'])->name('momentos-academicos.index');
    Route::post('/momentos-academicos/{semestre}/activar', [\App\Http\Controllers\MomentoAcademicoController::class, 'activar'])->name('momentos-academicos.activar');
    Route::post('/momentos-academicos/{semestre}/desactivar', [\App\Http\Controllers\MomentoAcademicoController::class, 'desactivar'])->name('momentos-academicos.desactivar');
});

// Rutas generales (para todos los usuarios autenticados)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Ruta para obtener semestres por materia (API)
Route::get('/api/semestres-por-materia/{materia}', [\App\Http\Controllers\Api\MateriaApiController::class, 'semestres'])->name('api.semestres-por-materia');

require __DIR__.'/api.php';
