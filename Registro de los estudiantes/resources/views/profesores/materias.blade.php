@extends('layouts.app.layout')

@section('title', 'Asignar Materias - ' . $profesor->nombre . ' ' . $profesor->apellido)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Asignar Materias</h1>
                        <p class="text-lg text-gray-600">Profesor: {{ $profesor->nombre }} {{ $profesor->apellido }}</p>
                    </div>
                </div>
                <a href="{{ route('profesores.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenido principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Panel de materias disponibles -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Materias Disponibles</h3>
                            <div class="flex items-center space-x-2">
                                <div class="relative">
                                    <input type="text" 
                                           id="searchMaterias" 
                                           placeholder="Buscar materias..." 
                                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                </div>
                                <button id="selectAll" 
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                    <i class="fas fa-check-double mr-1"></i>
                                    Seleccionar Todo
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <form id="materiasForm" action="{{ route('profesores.asignarMaterias', $profesor->id) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="materiasContainer">
                                @foreach($materias as $materia)
                                    <div class="materia-item bg-gray-50 rounded-lg p-4 border-2 border-transparent hover:border-indigo-200 transition-all duration-200 cursor-pointer"
                                         data-materia-id="{{ $materia->id }}"
                                         data-materia-nombre="{{ strtolower($materia->nombre) }}">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <input type="checkbox" 
                                                       name="materias[]" 
                                                       value="{{ $materia->id }}" 
                                                       id="materia_{{ $materia->id }}"
                                                       class="materia-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                       {{ in_array($materia->id, $materiasAsignadas) ? 'checked' : '' }}>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <label for="materia_{{ $materia->id }}" class="text-sm font-medium text-gray-900 cursor-pointer">
                                                        {{ $materia->nombre }}
                                                    </label>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $materia->estado === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $materia->estado }}
                                                    </span>
                                                </div>
                                                <div class="mt-1 flex items-center text-xs text-gray-500">
                                                    <i class="fas fa-university mr-1"></i>
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
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Panel de resumen y acciones -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Resumen</h3>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Estadísticas -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Total de materias:</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $materias->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Materias asignadas:</span>
                                <span id="contadorAsignadas" class="text-sm font-semibold text-indigo-600">{{ count($materiasAsignadas) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Materias disponibles:</span>
                                <span id="contadorDisponibles" class="text-sm font-semibold text-green-600">{{ $materias->count() - count($materiasAsignadas) }}</span>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        <!-- Materias seleccionadas -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Materias Seleccionadas:</h4>
                            <div id="materiasSeleccionadas" class="space-y-2 max-h-48 overflow-y-auto">
                                @foreach($materiasAsignadas as $materiaId)
                                    @php $materia = $materias->find($materiaId) @endphp
                                    @if($materia)
                                        <div class="flex items-center justify-between bg-indigo-50 rounded-md p-2">
                                            <span class="text-sm text-indigo-900">{{ $materia->nombre }}</span>
                                            <i class="fas fa-check text-indigo-600"></i>
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
                                    class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <i class="fas fa-save mr-2"></i>
                                Guardar Asignaciones
                            </button>
                            
                            <button type="button" 
                                    id="clearSelection"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                <i class="fas fa-times mr-2"></i>
                                Limpiar Selección
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    const materiaItems = document.querySelectorAll('.materia-item');

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
                const materiaItem = checkbox.closest('.materia-item');
                const nombre = materiaItem.querySelector('label').textContent.trim();
                materiasSeleccionadasArray.push({ id: checkbox.value, nombre: nombre });
            }
        });

        materiasSeleccionadas.innerHTML = materiasSeleccionadasArray.map(materia => `
            <div class="flex items-center justify-between bg-indigo-50 rounded-md p-2 animate-fade-in">
                <span class="text-sm text-indigo-900">${materia.nombre}</span>
                <i class="fas fa-check text-indigo-600"></i>
            </div>
        `).join('');
    }

    // Event listeners para checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const materiaItem = this.closest('.materia-item');
            
            if (this.checked) {
                materiaItem.classList.add('border-indigo-300', 'bg-indigo-50');
                materiaItem.classList.remove('border-transparent', 'bg-gray-50');
            } else {
                materiaItem.classList.remove('border-indigo-300', 'bg-indigo-50');
                materiaItem.classList.add('border-transparent', 'bg-gray-50');
            }
            
            actualizarContadores();
            actualizarMateriasSeleccionadas();
        });
    });

    // Búsqueda en tiempo real
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        materiaItems.forEach(item => {
            const nombre = item.getAttribute('data-materia-nombre');
            if (nombre.includes(searchTerm)) {
                item.style.display = 'block';
                item.style.animation = 'fadeIn 0.3s ease-in-out';
            } else {
                item.style.display = 'none';
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
            const materiaItem = checkbox.closest('.materia-item');
            materiaItem.classList.add('border-indigo-300', 'bg-indigo-50');
            materiaItem.classList.remove('border-transparent', 'bg-gray-50');
        }
    });
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

.materia-item {
    transition: all 0.2s ease-in-out;
}

.materia-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
</style>
@endpush
@endsection 