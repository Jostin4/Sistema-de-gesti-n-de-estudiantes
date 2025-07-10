{{-- resources/views/carreras/show.blade.php --}}
@extends('layouts.app.layout')

@section('title', 'Detalles de la Carrera')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('carreras.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Carreras
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-900">{{ $carrera->nombre }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $carrera->nombre }}</h1>
                    <p class="text-gray-600 mt-2">{{ $carrera->descripcion ?? 'Sin descripción' }}</p>
                </div>
                <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('carreras.edit', $carrera->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </a>
                    <form action="{{ route('carreras.destroy', $carrera->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta carrera?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                            <i class="fas fa-trash mr-2"></i>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-green-800">Éxito</h3>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-red-800">Error</h3>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-8 bg-blue-50 border border-blue-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-blue-800">Información</h3>
                        <p class="text-blue-700">{{ session('info') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Sección de Inscripción para Estudiantes -->
        @if(auth()->check() && auth()->user()->role === 'estudiante')
            @php
                $estudiante = auth()->user()->estudiante;
                $estaInscrito = $estudiante ? $estudiante->estaInscritoEnCarrera($carrera->id) : false;
                $semestreActual = $estudiante ? $estudiante->getSemestreActual($carrera->id) : null;
            @endphp
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-user-graduate mr-3 text-purple-500"></i>
                            Mi Estado en esta Carrera
                        </h2>
                        <p class="text-gray-600 mt-1">Información sobre tu inscripción y progreso académico</p>
                    </div>
                    @if(!$estaInscrito)
                        <form action="{{ route('carreras.inscribirme', $carrera->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:from-purple-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 shadow-lg"
                                    onclick="return confirm('¿Estás seguro de que quieres inscribirte en {{ $carrera->nombre }}? Serás asignado automáticamente al primer semestre.')">
                                <i class="fas fa-plus mr-2"></i>
                                Inscribirme en esta Carrera
                            </button>
                        </form>
                    @endif
                </div>
                
                <div class="mt-6">
                    @if($estaInscrito)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-green-800">¡Estás inscrito!</h3>
                                    <p class="text-green-700">
                                        Te encuentras inscrito en <strong>{{ $carrera->nombre }}</strong>
                                        @if($semestreActual)
                                            y cursando el <strong>{{ $semestreActual->nombre }}</strong>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        @if($semestreActual)
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-calendar-alt text-blue-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-blue-800">Semestre Actual</p>
                                            <p class="text-lg font-semibold text-blue-900">{{ $semestreActual->nombre }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-book text-purple-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-purple-800">Materias Inscritas</p>
                                            <p class="text-lg font-semibold text-purple-900">
                                                @php
                                                    $materiasInscritas = \App\Models\MateriaEstudianteSemestre::where([
                                                        'estudiante_id' => $estudiante->id,
                                                        'semestre_id' => $semestreActual->id
                                                    ])->count();
                                                @endphp
                                                {{ $materiasInscritas }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-chart-line text-orange-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-orange-800">Progreso</p>
                                            <p class="text-lg font-semibold text-orange-900">
                                                @php
                                                    $semestresCompletados = $carrera->semestres->where('id', '<=', $semestreActual->id)->count();
                                                    $progreso = round(($semestresCompletados / $carrera->numero_semestres) * 100);
                                                @endphp
                                                {{ $progreso }}%
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lista de Materias Inscritas -->
                            @php
                                $materiasInscritas = \App\Models\MateriaEstudianteSemestre::where([
                                    'estudiante_id' => $estudiante->id,
                                    'semestre_id' => $semestreActual->id
                                ])->with('materia')->get();
                            @endphp

                            @if($materiasInscritas->count() > 0)
                                <div class="mt-6">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <i class="fas fa-list-check mr-2 text-green-500"></i>
                                        Mis Materias del {{ $semestreActual->nombre }}
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($materiasInscritas as $inscripcion)
                                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-book text-green-600"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="font-semibold text-gray-900">{{ $inscripcion->materia->nombre }}</h5>
                                                            <p class="text-sm text-gray-500">{{ $inscripcion->materia->codigo ?? 'Sin código' }}</p>
                                                        </div>
                                                    </div>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Inscrito
                                                    </span>
                                                </div>
                                                <div class="flex items-center justify-between text-sm text-gray-600">
                                                    <span>
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ $inscripcion->created_at->format('d/m/Y') }}
                                                    </span>
                                                    <span>
                                                        <i class="fas fa-credit-card mr-1"></i>
                                                        {{ $inscripcion->materia->creditos ?? 3 }} créditos
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-yellow-800">Sin materias inscritas</h4>
                                            <p class="text-sm text-yellow-700">
                                                No tienes materias inscritas en el {{ $semestreActual->nombre }}. 
                                                Contacta a tu coordinador académico.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mt-6">
                                <a href="{{ route('estudiante.dashboard') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    <i class="fas fa-graduation-cap mr-2"></i>
                                    Ver Mis Cursos
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-gray-400 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-800">No estás inscrito</h3>
                                    <p class="text-gray-600">
                                        Para cursar materias de <strong>{{ $carrera->nombre }}</strong>, 
                                        primero debes inscribirte en la carrera. Al hacerlo, serás asignado 
                                        automáticamente al primer semestre.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Estadísticas Generales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Estudiantes</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $carrera->estudiantes->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-book text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Materias</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $carrera->materias->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Semestres</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $carrera->semestres->count() }}/{{ $carrera->numero_semestres }}</p>
                        <p class="text-xs text-purple-600 mt-1">{{ $carrera->progreso_semestres }}% completado</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-orange-400 to-orange-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Estado</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $carrera->esta_completa ? 'Completa' : 'En Progreso' }}</p>
                        <p class="text-xs text-orange-600 mt-1">{{ $carrera->semestres_faltantes }} semestres faltantes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información General -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-info-circle mr-3 text-blue-500"></i>
                Información General
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Código de Carrera</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $carrera->codigo ?? 'Sin código' }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Nivel Educativo</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $carrera->nivel_educacion ?? 'No especificado' }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Número de Semestres</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $carrera->numero_semestres }} semestres</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Estado</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $carrera->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fas {{ $carrera->estado ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                        {{ $carrera->estado ? 'Activa' : 'Inactiva' }}
                    </span>
                </div>
            </div>
            @if($carrera->descripcion)
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Descripción</h3>
                    <p class="text-gray-900">{{ $carrera->descripcion }}</p>
                </div>
            @endif
        </div>

        <!-- Progreso de Semestres -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-calendar-check mr-3 text-green-500"></i>
                    Progreso de Semestres
                </h2>
                <div class="flex gap-3">
                    <form action="{{ route('carreras.asignar-semestres', $carrera->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                onclick="return confirm('¿Estás seguro de reasignar los semestres automáticamente? Esto puede afectar las materias ya asignadas.')">
                            <i class="fas fa-magic mr-2"></i>
                            Reasignar Semestres
                        </button>
                    </form>
                    <a href="{{ route('semestres.associateSemestresForm', $carrera->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Gestionar Semestres
                    </a>
                </div>
            </div>
            
            <!-- Barra de Progreso -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Progreso: {{ $carrera->progreso_semestres }}%</span>
                    <span class="text-sm text-gray-500">{{ $carrera->semestres->count() }} de {{ $carrera->numero_semestres }} semestres</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all duration-300" 
                         style="width: {{ $carrera->progreso_semestres }}%"></div>
                </div>
            </div>

            @if($carrera->semestres->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($carrera->semestres as $semestre)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 hover:bg-green-100 transition-colors duration-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-semibold text-green-800">{{ $semestre->nombre ?? 'Semestre ' . $semestre->id }}</h3>
                                    <p class="text-xs text-green-600 mt-1">{{ $semestre->materias->count() ?? 0 }} materias</p>
                                </div>
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay semestres asociados</h3>
                    <p class="text-gray-500 mb-4">Asocia semestres a esta carrera para comenzar a configurar el plan de estudios.</p>
                    <a href="{{ route('semestres.associateSemestresForm', $carrera->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Asociar Semestres
                    </a>
                </div>
            @endif
        </div>

        <!-- Estudiantes Inscritos -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-users mr-3 text-blue-500"></i>
                    Estudiantes Inscritos
                </h2>
                <span class="text-sm text-gray-500">{{ $carrera->estudiantes->count() }} estudiantes</span>
            </div>

            @if($carrera->estudiantes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-user mr-1"></i>
                                    Estudiante
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-envelope mr-1"></i>
                                    Contacto
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-id-card mr-1"></i>
                                    Matrícula
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Fecha Inscripción
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-cogs mr-1"></i>
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($carrera->estudiantes as $estudiante)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-indigo-600 flex items-center justify-center">
                                                    <span class="text-white font-semibold text-sm">
                                                        {{ strtoupper(substr($estudiante->nombre, 0, 1) . substr($estudiante->apellido_paterno, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $estudiante->nombre }} {{ $estudiante->apellido_paterno }} {{ $estudiante->apellido_materno }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $estudiante->genero }} • {{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->age }} años
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $estudiante->correo }}</div>
                                        <div class="text-sm text-gray-500">{{ $estudiante->telefono }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-id-card mr-1"></i>
                                            {{ $estudiante->matricula }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $estudiante->pivot->created_at ? $estudiante->pivot->created_at->format('d/m/Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('estudiantes.show', $estudiante->id) }}" 
                                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                               title="Ver detalles">
                                                <i class="fas fa-eye mr-1"></i>
                                                Ver
                                            </a>
                                            <form action="{{ route('carreras.removeEstudiante', [$carrera->id, $estudiante->id]) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de remover a este estudiante de la carrera?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                                        title="Remover estudiante">
                                                    <i class="fas fa-user-minus mr-1"></i>
                                                    Remover
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay estudiantes inscritos</h3>
                    <p class="text-gray-500 mb-4">Agrega estudiantes a esta carrera para comenzar a gestionar su progreso académico.</p>
                </div>
            @endif

            <!-- Formulario para agregar estudiante -->
            @if($estudiantesDisponibles->count() > 0)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user-plus mr-2 text-green-500"></i>
                        Agregar Estudiante a la Carrera
                    </h3>
                    <form action="{{ route('carreras.addEstudiante', $carrera->id) }}" method="POST" class="flex flex-col sm:flex-row items-center gap-4">
                        @csrf
                        <div class="flex-grow w-full sm:w-auto">
                            <label for="estudiante_id" class="sr-only">Seleccionar un estudiante</label>
                            <select name="estudiante_id" id="estudiante_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" required>
                                <option value="">Selecciona un estudiante</option>
                                @foreach($estudiantesDisponibles as $estudiante)
                                    <option value="{{ $estudiante->id }}">
                                        {{ $estudiante->nombre }} {{ $estudiante->apellido_paterno }} {{ $estudiante->apellido_materno }} - {{ $estudiante->matricula }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estudiante_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 w-full sm:w-auto">
                            <i class="fas fa-plus mr-2"></i>
                            Agregar Estudiante
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
