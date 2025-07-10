@extends('layouts.app.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Header del dashboard -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-gray-600 mt-1">
                        @if(auth()->user()->role === 'admin')
                            Panel de administración del sistema académico
                        @elseif(auth()->user()->role === 'profesor')
                            Panel de gestión de tus materias y evaluaciones
                        @elseif(auth()->user()->role === 'estudiante')
                            Panel de seguimiento de tu progreso académico
                        @else
                            Bienvenido al sistema de gestión académica
                        @endif
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                    <span class="text-sm text-gray-500">Sistema Activo</span>
                </div>
            </div>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @if(auth()->user()->role === 'admin')
                <!-- Estadísticas para Administrador -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-university text-indigo-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Carreras</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\carrera::count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-graduate text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Estudiantes</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\estudiante::count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Profesores</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Profesor::count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Materias</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\materia::count() }}</p>
                        </div>
                    </div>
                </div>
            @elseif(auth()->user()->role === 'profesor')
                <!-- Estadísticas para Profesor -->
                @php
                    $profesor = \App\Models\Profesor::where('user_id', auth()->id())->first();
                    $materias = $profesor ? $profesor->materias : collect();
                    $evaluaciones = $materias->flatMap(function($materia) {
                        return $materia->evaluaciones;
                    });
                    $notas = $evaluaciones->flatMap(function($evaluacion) {
                        return $evaluacion->notas;
                    });
                @endphp
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Mis Materias</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $materias->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tasks text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Evaluaciones</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $evaluaciones->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-bar text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Notas Cargadas</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $notas->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Estudiantes</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $materias ? $materias->flatMap->estudiantes->unique('id')->count() : 0 }}</p>
                        </div>
                    </div>
                </div>
            @elseif(auth()->user()->role === 'estudiante')
                <!-- Estadísticas para Estudiante -->
                @php
                    $estudiante = \App\Models\estudiante::where('user_id', auth()->id())->first();
                    $materiasInscritas = $estudiante ? $estudiante->materias : collect();
                    $notas = collect();
                    $promedio = 0;
                    
                    if ($estudiante && $materiasInscritas->count() > 0) {
                        $notas = $materiasInscritas->flatMap(function($materia) use ($estudiante) {
                            return $materia->evaluaciones->flatMap(function($evaluacion) use ($estudiante) {
                                return $evaluacion->notas->where('estudiante_id', $estudiante->id);
                            });
                        });
                        $promedio = $notas->count() > 0 ? $notas->avg('nota') : 0;
                    }
                @endphp
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Materias Inscritas</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $materiasInscritas->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-line text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Promedio General</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($promedio, 1) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clipboard-check text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Evaluaciones</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $notas->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-trophy text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Estado</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $promedio >= 50 ? 'Aprobado' : 'En Riesgo' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Acciones rápidas -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                @if(auth()->user()->role === 'admin')
                    Acciones de Administración
                @elseif(auth()->user()->role === 'profesor')
                    Mis Acciones
                @elseif(auth()->user()->role === 'estudiante')
                    Acciones Disponibles
                @else
                    Acciones Rápidas
                @endif
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @if(auth()->user()->role === 'admin')
                    <!-- Acciones para Administrador -->
                    <a href="{{ route('carreras.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-university text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Gestionar Carreras</p>
                            <p class="text-sm text-gray-500">Ver y editar carreras</p>
                        </div>
                    </a>

                    <a href="{{ route('estudiantes.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-user-graduate text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Gestionar Estudiantes</p>
                            <p class="text-sm text-gray-500">Ver y editar estudiantes</p>
                        </div>
                    </a>

                    <a href="{{ route('materias.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-book text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Gestionar Materias</p>
                            <p class="text-sm text-gray-500">Ver y editar materias</p>
                        </div>
                    </a>

                    <a href="{{ route('profesores.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-chalkboard-teacher text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Gestionar Profesores</p>
                            <p class="text-sm text-gray-500">Ver y editar profesores</p>
                        </div>
                    </a>

                    <a href="{{ route('materia-semestre.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-link text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Asignar Materias</p>
                            <p class="text-sm text-gray-500">Asignar materias a semestres</p>
                        </div>
                    </a>

                    <a href="{{ route('evaluaciones.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-tasks text-red-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Evaluaciones</p>
                            <p class="text-sm text-gray-500">Gestionar evaluaciones</p>
                        </div>
                    </a>

                @elseif(auth()->user()->role === 'profesor')
                    <!-- Acciones para Profesor -->
                    <a href="{{ route('evaluaciones.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-tasks text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Mis Materias</p>
                            <p class="text-sm text-gray-500">Gestionar evaluaciones de mis materias</p>
                        </div>
                    </a>

                    <a href="{{ route('notas.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-chart-bar text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Ver Notas</p>
                            <p class="text-sm text-gray-500">Consultar todas las notas</p>
                        </div>
                    </a>

                    <a href="{{ route('evaluaciones.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-edit text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Cargar Notas</p>
                            <p class="text-sm text-gray-500">Ingresar calificaciones</p>
                        </div>
                    </a>

                @elseif(auth()->user()->role === 'estudiante')
                    <!-- Acciones para Estudiante -->
                    <a href="{{ route('estudiante.dashboard') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-graduation-cap text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Mis Cursos</p>
                            <p class="text-sm text-gray-500">Ver materias inscritas</p>
                        </div>
                    </a>

                    <a href="{{ route('estudiante.notas') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-chart-line text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Mis Notas</p>
                            <p class="text-sm text-gray-500">Consultar calificaciones</p>
                        </div>
                    </a>

                    <a href="{{ route('estudiante.dashboard') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-alt text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Mi Semestre</p>
                            <p class="text-sm text-gray-500">Ver progreso académico</p>
                        </div>
                    </a>
                @endif
            </div>
        </div>

        <!-- Información adicional según el rol -->
        @if(auth()->user()->role === 'profesor')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    Información para Profesores
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Puedes gestionar evaluaciones solo para las materias que te han sido asignadas</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Accede a "Mis Materias" para ver todas tus asignaciones</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Desde ahí podrás crear evaluaciones y cargar notas</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Consulta el historial de notas en "Ver Notas"</span>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->role === 'estudiante')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-green-600"></i>
                    Información para Estudiantes
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Consulta tus materias inscritas en "Mis Cursos"</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Revisa tus calificaciones en "Mis Notas"</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Monitorea tu progreso académico</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Contacta a tu profesor si tienes dudas</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
