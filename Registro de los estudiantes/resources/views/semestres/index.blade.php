@extends('layouts.app.layout')

@section('title', 'Gestión Académica Integrada')
@section('page-title', 'Gestión de Carreras, Semestres y Materias')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestión Académica Integrada</h1>
                    <p class="text-gray-600 mt-2">Organiza y gestiona carreras, semestres y materias desde una sola interfaz</p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-3">
                    <a href="{{ route('semestres.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Nuevo Semestre
                    </a>
                    <a href="{{ route('materias.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:from-purple-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 shadow-lg">
                        <i class="fas fa-book mr-2"></i>
                        Nueva Materia
                    </a>
                    <a href="{{ route('carreras.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg font-semibold hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        <i class="fas fa-university mr-2"></i>
                        Ver Carreras
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-university text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Carreras</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\carrera::count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Semestres</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\semestre::count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-book text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Materias</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\materia::count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-orange-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Estudiantes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\estudiante::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 sm:mb-0">Filtros</h2>
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                    <select id="carrera-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todas las carreras</option>
                        @foreach(\App\Models\carrera::all() as $carrera)
                            <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                        @endforeach
                    </select>
                    <select id="semestre-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos los semestres</option>
                        @foreach(\App\Models\semestre::all() as $semestre)
                            <option value="{{ $semestre->id }}">{{ $semestre->nombre }}</option>
                        @endforeach
                    </select>
                    <select id="estado-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos los estados</option>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Carreras con sus Semestres -->
        <div class="space-y-8">
            @foreach(\App\Models\carrera::with(['semestres.materias'])->get() as $carrera)
            <div class="carrera-section bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden" data-carrera-id="{{ $carrera->id }}">
                <!-- Header de la Carrera -->
                <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-university text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $carrera->nombre }}</h2>
                                <div class="flex items-center space-x-4 mt-1">
                                    <span class="text-sm text-gray-600">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ $carrera->semestres->count() }}/{{ $carrera->numero_semestres }} semestres
                                    </span>
                                    <span class="text-sm text-gray-600">
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $carrera->estudiantes->count() }} estudiantes
                                    </span>
                                    <span class="text-sm text-gray-600">
                                        <i class="fas fa-book mr-1"></i>
                                        {{ $carrera->materias->count() }} materias
                                    </span>
                                    @if($carrera->estado)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Activa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Inactiva
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('carreras.show', $carrera->id) }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                <i class="fas fa-eye mr-2"></i>
                                Ver Detalles
                            </a>
                            <a href="{{ route('semestres.associateSemestresForm', $carrera->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <i class="fas fa-link mr-2"></i>
                                Gestionar Semestres
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Semestres de la Carrera -->
                <div class="p-8">
                    @if($carrera->semestres->count() > 0)
                        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($carrera->semestres->sortBy('nombre') as $semestre)
                            <div class="semestre-card bg-gray-50 rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-all duration-200" data-semestre-id="{{ $semestre->id }}">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-calendar-alt text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $semestre->nombre }}</h3>
                                            <p class="text-sm text-gray-600">{{ $semestre->materias->count() }} materias</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('materia-semestre.show', $semestre->id) }}" 
                                           class="text-purple-600 hover:text-purple-800 p-1 rounded-md hover:bg-purple-50 transition-colors duration-200"
                                           title="Gestionar materias">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                        <a href="{{ route('semestres.show', $semestre->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 p-1 rounded-md hover:bg-blue-50 transition-colors duration-200"
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('semestres.edit', $semestre->id) }}" 
                                           class="text-amber-600 hover:text-amber-800 p-1 rounded-md hover:bg-amber-50 transition-colors duration-200"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>

                                <!-- Materias del Semestre -->
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-gray-700 flex items-center">
                                            <i class="fas fa-book mr-2 text-purple-500"></i>
                                            Materias ({{ $semestre->materias->count() }})
                                        </h4>
                                        <button onclick="toggleMaterias({{ $semestre->id }})" 
                                                class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                            <i class="fas fa-chevron-down" id="icon-{{ $semestre->id }}"></i>
                                        </button>
                                    </div>
                                    
                                    <div id="materias-{{ $semestre->id }}" class="space-y-2 max-h-0 overflow-hidden transition-all duration-300">
                                        @if($semestre->materias->count() > 0)
                                            @foreach($semestre->materias->take(3) as $materia)
                                                <div class="flex items-center justify-between bg-white rounded-lg p-3 border border-gray-200">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-book text-purple-600 text-sm"></i>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900">{{ $materia->nombre }}</p>
                                                            <p class="text-xs text-gray-500">{{ $materia->evaluaciones->count() }} evaluaciones</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-1">
                                                        <a href="{{ route('materias.show', $materia->id) }}" 
                                                           class="text-blue-600 hover:text-blue-800 p-1 rounded-md hover:bg-blue-50 transition-colors duration-200"
                                                           title="Ver materia">
                                                            <i class="fas fa-eye text-xs"></i>
                                                        </a>
                                                        <a href="{{ route('evaluaciones.show', [$materia->id, $semestre->id]) }}" 
                                                           class="text-green-600 hover:text-green-800 p-1 rounded-md hover:bg-green-50 transition-colors duration-200"
                                                           title="Gestionar evaluaciones">
                                                            <i class="fas fa-tasks text-xs"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                            
                                            @if($semestre->materias->count() > 3)
                                                <div class="text-center">
                                                    <p class="text-xs text-gray-500">Y {{ $semestre->materias->count() - 3 }} materias más...</p>
                                                </div>
                                            @endif
                                        @else
                                            <div class="text-center py-4">
                                                <p class="text-sm text-gray-500">No hay materias asignadas</p>
                                                <a href="{{ route('materia-semestre.show', $semestre->id) }}" 
                                                   class="inline-flex items-center px-3 py-1 mt-2 text-xs font-medium text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                                    <i class="fas fa-plus mr-1"></i>
                                                    Asignar materias
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Acciones rápidas -->
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('materia-semestre.show', $semestre->id) }}" 
                                           class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-purple-600 text-white text-xs font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                                            <i class="fas fa-cog mr-1"></i>
                                            Gestionar
                                        </a>
                                        <a href="{{ route('evaluaciones.index') }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                            <i class="fas fa-tasks"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-calendar-alt text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay semestres asignados</h3>
                            <p class="text-gray-600 mb-8 max-w-md mx-auto">
                                Esta carrera aún no tiene semestres asignados. Asigna semestres para comenzar a gestionar las materias.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('semestres.associateSemestresForm', $carrera->id) }}" 
                                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    <i class="fas fa-link mr-2"></i>
                                    Asignar Semestres
                                </a>
                                <a href="{{ route('semestres.create') }}" 
                                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>
                                    Crear Semestre
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtros
    const carreraFilter = document.getElementById('carrera-filter');
    const semestreFilter = document.getElementById('semestre-filter');
    const estadoFilter = document.getElementById('estado-filter');
    const carreraSections = document.querySelectorAll('.carrera-section');
    const semestreCards = document.querySelectorAll('.semestre-card');

    function aplicarFiltros() {
        const carreraSeleccionada = carreraFilter.value;
        const semestreSeleccionado = semestreFilter.value;
        const estadoSeleccionado = estadoFilter.value;

        carreraSections.forEach(section => {
            const carreraId = section.getAttribute('data-carrera-id');
            let mostrarCarrera = !carreraSeleccionada || carreraId === carreraSeleccionada;
            
            if (mostrarCarrera) {
                section.style.display = 'block';
                
                // Filtrar semestres dentro de la carrera
                const semestresEnCarrera = section.querySelectorAll('.semestre-card');
                semestresEnCarrera.forEach(card => {
                    const semestreId = card.getAttribute('data-semestre-id');
                    let mostrarSemestre = !semestreSeleccionado || semestreId === semestreSeleccionado;
                    
                    if (mostrarSemestre) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            } else {
                section.style.display = 'none';
            }
        });
    }

    carreraFilter.addEventListener('change', aplicarFiltros);
    semestreFilter.addEventListener('change', aplicarFiltros);
    estadoFilter.addEventListener('change', aplicarFiltros);

    // Función para mostrar/ocultar materias
    window.toggleMaterias = function(semestreId) {
        const materiasContainer = document.getElementById(`materias-${semestreId}`);
        const icon = document.getElementById(`icon-${semestreId}`);
        
        if (materiasContainer.classList.contains('max-h-0')) {
            materiasContainer.classList.remove('max-h-0');
            materiasContainer.classList.add('max-h-96');
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            materiasContainer.classList.remove('max-h-96');
            materiasContainer.classList.add('max-h-0');
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    };
});
</script>

<style>
.semestre-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.semestre-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.carrera-section {
    transition: all 0.3s ease-in-out;
}

.carrera-section:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
@endpush
@endsection 