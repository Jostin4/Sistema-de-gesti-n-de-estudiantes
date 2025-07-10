@extends('layouts.app.layout')

@section('title', 'Gestionar Materias - ' . $semestre->nombre)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb mejorado -->
        <nav class="mb-8" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('materia-semestre.index') }}" 
                       class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200">
                        <i class="fas fa-home mr-1"></i>
                        Inicio
                    </a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('materia-semestre.carrera', $carrera->id) }}" 
                       class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200">
                        {{ $carrera->nombre }}
                    </a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-900 font-semibold">{{ $semestre->nombre }}</span>
                </li>
            </ol>
        </nav>

        <!-- Header principal -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8">
            <div class="px-8 py-6 bg-gradient-to-r from-indigo-500 to-purple-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">{{ $semestre->nombre }}</h1>
                            <p class="text-indigo-100 text-lg">{{ $carrera->nombre }}</p>
                        </div>
                    </div>
                    <div class="text-right text-white">
                        <div class="text-2xl font-bold">{{ count($materiasAsignadas) }}</div>
                        <div class="text-indigo-100">Materias asignadas</div>
                    </div>
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
                        <h3 class="text-lg font-medium text-green-800">¡Asignación exitosa!</h3>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenido principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Panel de materias disponibles -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Materias Disponibles</h2>
                                <p class="text-gray-600 mt-1">Selecciona las materias que se dictarán en {{ $semestre->nombre }}</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <input type="text" 
                                           id="searchMaterias" 
                                           placeholder="Buscar materias..." 
                                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                </div>
                                <button id="selectAll" 
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                    <i class="fas fa-check-double mr-2"></i>
                                    Seleccionar Todo
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        @if($materiasCarrera->count() > 0)
                            <form id="materiasForm" action="{{ route('materia-semestre.asignar', [$carrera->id, $semestre->id]) }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="materiasContainer">
                                    @foreach($materiasCarrera as $materia)
                                        <div class="materia-card bg-gray-50 rounded-xl p-6 border-2 border-transparent hover:border-indigo-200 transition-all duration-300 cursor-pointer transform hover:-translate-y-1 hover:shadow-lg"
                                             data-materia-id="{{ $materia->id }}"
                                             data-materia-nombre="{{ strtolower($materia->nombre) }}">
                                            <div class="flex items-start space-x-4">
                                                <div class="flex-shrink-0">
                                                    <input type="checkbox" 
                                                           name="materias[]" 
                                                           value="{{ $materia->id }}" 
                                                           id="materia_{{ $materia->id }}"
                                                           class="materia-checkbox h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                           {{ in_array($materia->id, $materiasAsignadas) ? 'checked' : '' }}>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <label for="materia_{{ $materia->id }}" class="text-lg font-semibold text-gray-900 cursor-pointer hover:text-indigo-700 transition-colors duration-200">
                                                            {{ $materia->nombre }}
                                                        </label>
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $materia->estado === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            <i class="fas fa-circle mr-1 text-xs"></i>
                                                            {{ $materia->estado }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-500 mb-3">
                                                        <i class="fas fa-university mr-2"></i>
                                                        @if($materia->semestres->count() > 0)
                                                            @php
                                                                $carreras = $materia->semestres->flatMap(function($semestre) {
                                                                    return $semestre->carreras;
                                                                })->unique('id');
                                                            @endphp
                                                            {{ $carreras->pluck('nombre')->implode(', ') ?: 'Sin carrera' }}
                                                        @else
                                                            Sin carrera
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                            <span class="flex items-center">
                                                                <i class="fas fa-users mr-1"></i>
                                                                {{ $materia->evaluaciones->count() }} evaluaciones
                                                            </span>
                                                            <span class="flex items-center">
                                                                <i class="fas fa-calendar mr-1"></i>
                                                                {{ $materia->semestres->count() }} semestres
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            <a href="{{ route('materias.show', $materia->id) }}" 
                                                               class="text-indigo-600 hover:text-indigo-800 transition-colors duration-200">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('evaluaciones.show', [$materia->id, $semestre->id]) }}" 
                                                               class="text-green-600 hover:text-green-800 transition-colors duration-200">
                                                                <i class="fas fa-tasks"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        @else
                            <div class="text-center py-16">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-book text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay materias disponibles</h3>
                                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                                    No hay materias creadas para la carrera "{{ $carrera->nombre }}". Primero debes crear materias.
                                </p>
                                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                    <a href="{{ route('materias.create') }}" 
                                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                        <i class="fas fa-plus mr-2"></i>
                                        Crear Primera Materia
                                    </a>
                                    <a href="{{ route('materias.index') }}" 
                                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                        <i class="fas fa-list mr-2"></i>
                                        Gestionar Materias
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Panel de resumen y acciones -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 sticky top-8">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                        <h3 class="text-lg font-medium text-gray-900">Resumen y Acciones</h3>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Estadísticas -->
                        <div class="space-y-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-blue-700">Total de materias:</span>
                                    <span class="text-lg font-bold text-blue-900">{{ $materiasCarrera->count() }}</span>
                                </div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-green-700">Materias asignadas:</span>
                                    <span id="contadorAsignadas" class="text-lg font-bold text-green-900">{{ count($materiasAsignadas) }}</span>
                                </div>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-purple-700">Materias disponibles:</span>
                                    <span id="contadorDisponibles" class="text-lg font-bold text-purple-900">{{ $materiasCarrera->count() - count($materiasAsignadas) }}</span>
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        <!-- Materias seleccionadas -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                Materias Seleccionadas:
                            </h4>
                            <div id="materiasSeleccionadas" class="space-y-2 max-h-48 overflow-y-auto">
                                @foreach($materiasAsignadas as $materiaId)
                                    @php $materia = $materiasCarrera->find($materiaId) @endphp
                                    @if($materia)
                                        <div class="flex items-center justify-between bg-green-50 rounded-lg p-3 border border-green-200 animate-fade-in">
                                            <span class="text-sm font-medium text-green-900">${materia.nombre}</span>
                                            <i class="fas fa-check text-green-600"></i>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        <!-- Acciones -->
                        <div class="space-y-3">
                            <button type="submit" 
                                    form="materiasForm"
                                    class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-save mr-2"></i>
                                Guardar Asignaciones
                            </button>
                            
                            <button type="button" 
                                    id="clearSelection"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                <i class="fas fa-times mr-2"></i>
                                Limpiar Selección
                            </button>
                        </div>

                        <!-- Acciones adicionales -->
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Acciones Rápidas</h4>
                            <div class="space-y-2">
                                <a href="{{ route('evaluaciones.index') }}" 
                                   class="block w-full text-left px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-tasks mr-2"></i>
                                    Gestionar Evaluaciones
                                </a>
                                <a href="{{ route('materias.create') }}" 
                                   class="block w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>
                                    Crear Nueva Materia
                                </a>
                                <a href="{{ route('materia-semestre.carrera', $carrera->id) }}" 
                                   class="block w-full text-left px-4 py-2 text-sm text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Volver a {{ $carrera->nombre }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de materias actualmente asignadas -->
        @if(count($materiasAsignadas) > 0)
            <div class="mt-12 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-check-circle mr-3 text-green-600"></i>
                        Materias Actualmente Asignadas
                    </h3>
                    <p class="text-gray-600 mt-1">Estas son las materias que se dictarán en {{ $semestre->nombre }}</p>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($materiasCarrera->whereIn('id', $materiasAsignadas) as $materia)
                            <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6 hover:border-green-300 transition-all duration-200">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-book text-green-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-green-900">{{ $materia->nombre }}</h4>
                                            <p class="text-sm text-green-700">
                                                @if($materia->semestres->count() > 0)
                                                    @php
                                                        $carreras = $materia->semestres->flatMap(function($semestre) {
                                                            return $semestre->carreras;
                                                        })->unique('id');
                                                    @endphp
                                                    {{ $carreras->pluck('nombre')->implode(', ') ?: 'Sin carrera' }}
                                                @else
                                                    Sin carrera
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Asignada
                                    </span>
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-green-700">Evaluaciones:</span>
                                        <span class="font-medium text-green-900">{{ $materia->evaluaciones->count() }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-green-700">Estado:</span>
                                        <span class="font-medium text-green-900">{{ $materia->estado }}</span>
                                    </div>
                                </div>
                                
                                <div class="mt-4 flex space-x-2">
                                    <a href="{{ route('evaluaciones.show', [$materia->id, $semestre->id]) }}" 
                                       class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-green-300 text-sm font-medium rounded-lg text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                        <i class="fas fa-tasks mr-1"></i>
                                        Evaluaciones
                                    </a>
                                    <a href="{{ route('materias.show', $materia->id) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const materiasContainer = document.getElementById('materiasContainer');
    const materiasSeleccionadas = document.getElementById('materiasSeleccionadas');
    const contadorAsignadas = document.getElementById('contadorAsignadas');
    const contadorDisponibles = document.getElementById('contadorDisponibles');
    const searchInput = document.getElementById('searchMaterias');
    const selectAllBtn = document.getElementById('selectAll');
    const clearSelectionBtn = document.getElementById('clearSelection');
    const checkboxes = document.querySelectorAll('.materia-checkbox');
    const materiaCards = document.querySelectorAll('.materia-card');

    // Función para actualizar contadores
    function actualizarContadores() {
        const checked = document.querySelectorAll('.materia-checkbox:checked').length;
        const total = checkboxes.length;
        
        contadorAsignadas.textContent = checked;
        contadorDisponibles.textContent = total - checked;
    }

    // Función para actualizar lista de materias seleccionadas
    function actualizarMateriasSeleccionadas() {
        const materiasSeleccionadasArray = [];
        
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const materiaCard = checkbox.closest('.materia-card');
                const nombre = materiaCard.querySelector('label').textContent.trim();
                materiasSeleccionadasArray.push({ id: checkbox.value, nombre: nombre });
            }
        });

        materiasSeleccionadas.innerHTML = materiasSeleccionadasArray.map(materia => `
            <div class="flex items-center justify-between bg-green-50 rounded-lg p-3 border border-green-200 animate-fade-in">
                <span class="text-sm font-medium text-green-900">${materia.nombre}</span>
                <i class="fas fa-check text-green-600"></i>
            </div>
        `).join('');
    }

    // Event listeners para checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const materiaCard = this.closest('.materia-card');
            
            if (this.checked) {
                materiaCard.classList.add('border-indigo-300', 'bg-indigo-50', 'shadow-lg');
                materiaCard.classList.remove('border-transparent', 'bg-gray-50');
            } else {
                materiaCard.classList.remove('border-indigo-300', 'bg-indigo-50', 'shadow-lg');
                materiaCard.classList.add('border-transparent', 'bg-gray-50');
            }
            
            actualizarContadores();
            actualizarMateriasSeleccionadas();
        });
    });

    // Búsqueda en tiempo real
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        materiaCards.forEach(card => {
            const nombre = card.getAttribute('data-materia-nombre');
            if (nombre.includes(searchTerm)) {
                card.style.display = 'block';
                card.style.animation = 'fadeInUp 0.3s ease-out';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Seleccionar todo
    selectAllBtn.addEventListener('click', function() {
        const unchecked = document.querySelectorAll('.materia-checkbox:not(:checked)');
        unchecked.forEach(checkbox => {
            checkbox.checked = true;
            checkbox.dispatchEvent(new Event('change'));
        });
    });

    // Limpiar selección
    clearSelectionBtn.addEventListener('click', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
            checkbox.dispatchEvent(new Event('change'));
        });
    });

    // Inicializar
    actualizarContadores();
    actualizarMateriasSeleccionadas();

    // Aplicar estilos iniciales
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            const materiaCard = checkbox.closest('.materia-card');
            materiaCard.classList.add('border-indigo-300', 'bg-indigo-50', 'shadow-lg');
            materiaCard.classList.remove('border-transparent', 'bg-gray-50');
        }
    });
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInUp {
    from { 
        opacity: 0; 
        transform: translateY(20px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

.materia-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.materia-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
@endpush
@endsection 