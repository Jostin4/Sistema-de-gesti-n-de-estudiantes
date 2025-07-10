{{-- resources/views/estudiantes/show.blade.php --}}
@extends('layouts.app.layout')

@section('title', 'Detalles del Estudiante')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $estudiante->nombre }} {{ $estudiante->apellido_paterno }}</h1>
                    <p class="mt-2 text-sm text-gray-600">Matrícula: {{ $estudiante->matricula }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('estudiantes.edit', $estudiante->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </a>
                    <a href="{{ route('estudiantes.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información Personal -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700">
                        <h2 class="text-xl font-semibold text-white">Información Personal</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                                    <dd class="mt-1 text-lg text-gray-900">
                                        {{ $estudiante->nombre }} {{ $estudiante->segundo_nombre }} {{ $estudiante->apellido_paterno }} {{ $estudiante->apellido_materno }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Nacimiento</dt>
                                    <dd class="mt-1 text-lg text-gray-900">
                                        <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                        {{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->format('d/m/Y') }}
                                        <span class="text-sm text-gray-500 ml-2">
                                            ({{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->age }} años)
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Género</dt>
                                    <dd class="mt-1 text-lg text-gray-900">
                                        <i class="fas fa-user mr-2 text-gray-400"></i>
                                        {{ $estudiante->genero }}
                                    </dd>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Correo Electrónico</dt>
                                    <dd class="mt-1 text-lg text-gray-900">
                                        <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                        {{ $estudiante->correo }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                    <dd class="mt-1 text-lg text-gray-900">
                                        <i class="fas fa-phone mr-2 text-gray-400"></i>
                                        {{ $estudiante->telefono ?? 'No registrado' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                    <dd class="mt-1">
                                        @if($estudiante->estado === 'Activo')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-2"></i>
                                                Inactivo
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas Académicas -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700">
                        <h2 class="text-xl font-semibold text-white">Estadísticas Académicas</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600">{{ number_format($promedioGeneral, 1) }}</div>
                                <div class="text-sm text-gray-500">Promedio General</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600">{{ $totalNotas }}</div>
                                <div class="text-sm text-gray-500">Total de Notas</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-purple-600">{{ number_format($porcentajeAprobacion, 1) }}%</div>
                                <div class="text-sm text-gray-500">% Aprobación</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-yellow-600">{{ $materias->count() }}</div>
                                <div class="text-sm text-gray-500">Materias Inscritas</div>
                            </div>
                        </div>
                        
                        <!-- Gráfico de progreso -->
                        <div class="mt-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Progreso de Aprobación</span>
                                <span class="text-sm text-gray-500">{{ $notasAprobadas }}/{{ $totalNotas }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $totalNotas > 0 ? ($notasAprobadas / $totalNotas) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carreras -->
                @if($carreras->count() > 0)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-600 to-purple-700">
                        <h2 class="text-xl font-semibold text-white">Carreras</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($carreras as $carrera)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-university text-purple-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $carrera->nombre }}</h3>
                                        <p class="text-sm text-gray-500">Carrera activa</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    Activa
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Semestres -->
                @if($semestres->count() > 0)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-600 to-indigo-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-white">Semestres</h2>
                            <div class="flex space-x-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $semestresAprobados->count() }} Aprobados
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ $semestresEnCurso->count() }} En Curso
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($semestres as $semestre)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-graduation-cap text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $semestre->nombre }}</h3>
                                        <p class="text-sm text-gray-500">Semestre académico</p>
                                    </div>
                                </div>
                                @if($semestresAprobados->contains($semestre))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        Aprobado
                                    </span>
                                @elseif($semestresEnCurso->contains($semestre))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        En Curso
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-minus mr-1"></i>
                                        Sin Notas
                                    </span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Materias -->
                @if($materias->count() > 0)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-600 to-yellow-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-white">Materias</h2>
                            <div class="flex space-x-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $materiasAprobadas->count() }} Aprobadas
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $materiasReprobadas->count() }} Reprobadas
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($materias as $materia)
                            @php
                                $notasMateria = $notas->where('evaluacion.materia_id', $materia->id);
                                $promedioMateria = $notasMateria->count() > 0 ? $notasMateria->avg('nota') : 0;
                                $estadoMateria = $notasMateria->count() > 0 ? ($promedioMateria >= 50 ? 'Aprobada' : 'Reprobada') : 'Sin Notas';
                            @endphp
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-book text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $materia->nombre }}</h3>
                                        <p class="text-sm text-gray-500">
                                            Promedio: {{ number_format($promedioMateria, 1) }} | 
                                            Notas: {{ $notasMateria->count() }}
                                        </p>
                                    </div>
                                </div>
                                @if($estadoMateria === 'Aprobada')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        Aprobada
                                    </span>
                                @elseif($estadoMateria === 'Reprobada')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>
                                        Reprobada
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-minus mr-1"></i>
                                        Sin Notas
                                    </span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Resumen Rápido -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-600 to-gray-700">
                        <h3 class="text-lg font-semibold text-white">Resumen Rápido</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Carreras</span>
                            <span class="text-2xl font-bold text-gray-900">{{ $carreras->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Semestres</span>
                            <span class="text-2xl font-bold text-gray-900">{{ $semestres->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Materias</span>
                            <span class="text-2xl font-bold text-gray-900">{{ $materias->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Evaluaciones</span>
                            <span class="text-2xl font-bold text-gray-900">{{ $totalNotas }}</span>
                        </div>
                    </div>
                </div>

                <!-- Estado Académico -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700">
                        <h3 class="text-lg font-semibold text-white">Estado Académico</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="text-center">
                            <div class="text-4xl font-bold {{ $promedioGeneral >= 50 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($promedioGeneral, 1) }}
                            </div>
                            <div class="text-sm text-gray-500">Promedio General</div>
                            <div class="mt-2">
                                @if($promedioGeneral >= 50)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-trophy mr-1"></i>
                                        Excelente
                                    </span>
                                @elseif($promedioGeneral >= 30)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Necesita Mejorar
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Crítico
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700">
                        <h3 class="text-lg font-semibold text-white">Acciones</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('estudiantes.edit', $estudiante->id) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Editar Estudiante
                        </a>
                        
                        <a href="{{ route('estudiantes.index') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <i class="fas fa-list mr-2"></i>
                            Volver a la Lista
                        </a>
                        
                        <form action="{{ route('estudiantes.destroy', $estudiante->id) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este estudiante? Esta acción no se puede deshacer.')"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                <i class="fas fa-trash mr-2"></i>
                                Eliminar Estudiante
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
