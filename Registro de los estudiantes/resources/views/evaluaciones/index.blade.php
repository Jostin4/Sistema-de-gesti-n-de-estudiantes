@extends('layouts.app.layout')

@section('title', 'Mis Evaluaciones')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mb-6">
                <i class="fas fa-tasks text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Mis Evaluaciones</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Gestiona las evaluaciones de tus materias y carga las notas de tus estudiantes
            </p>
        </div>

        @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-green-800">¡Operación exitosa!</h3>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenido principal -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Mis Materias</h2>
                        <p class="text-gray-600 mt-1">Selecciona una materia para gestionar sus evaluaciones</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <input type="text" 
                                   id="searchMaterias" 
                                   placeholder="Buscar materias..." 
                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                @if($materias->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-book text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No tienes materias asignadas</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            Contacta al administrador para que te asigne materias y puedas comenzar a gestionar evaluaciones.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('dashboard') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                <i class="fas fa-home mr-2"></i>
                                Ir al Dashboard
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="materiasGrid">
                        @foreach($materias as $materia)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 materia-card">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-book text-white text-lg"></i>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-semibold text-gray-900 materia-nombre">{{ $materia->nombre }}</h3>
                                                <p class="text-sm text-gray-500">
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
                                            <i class="fas fa-circle mr-1 text-xs"></i>
                                            Activa
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-university mr-2 text-gray-400"></i>
                                            <span>
                                                @if($materia->semestres->count() > 0)
                                                    @php
                                                        $carreras = $materia->semestres->flatMap(function($semestre) {
                                                            return $semestre->carreras;
                                                        })->unique('id');
                                                    @endphp
                                                    {{ $carreras->pluck('nombre')->implode(', ') ?: 'Carrera no asignada' }}
                                                @else
                                                    Carrera no asignada
                                                @endif
                                            </span>
                                        </div>
                                        
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-users mr-2 text-gray-400"></i>
                                            <span>{{ $materia->estudiantes->count() ?? 0 }} estudiantes</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-6 pt-4 border-t border-gray-100">
                                        <button onclick="mostrarSemestres({{ $materia->id }}, '{{ $materia->nombre }}')" 
                                                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-2 px-4 rounded-lg hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center">
                                            <i class="fas fa-tasks mr-2"></i>
                                            Gestionar Evaluaciones
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Modal para seleccionar semestre -->
                    <div id="semestreModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Seleccionar Semestre</h3>
                                    <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-times text-xl"></i>
                                    </button>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="semestreSelect" class="block text-sm font-medium text-gray-700 mb-2">
                                        Selecciona el semestre:
                                    </label>
                                    <select id="semestreSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Cargando semestres...</option>
                                    </select>
                                </div>
                                
                                <div class="flex justify-end space-x-3">
                                    <button onclick="cerrarModal()" 
                                            class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors duration-200">
                                        Cancelar
                                    </button>
                                    <button onclick="irAEvaluaciones()" 
                                            class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                                        Continuar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Información adicional -->
        <div class="mt-12 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-3 text-blue-600"></i>
                    Información sobre Evaluaciones
                </h3>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center group">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-plus text-white text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Crear Evaluaciones</h4>
                        <p class="text-gray-600">Define las evaluaciones para cada materia con sus respectivos porcentajes</p>
                    </div>
                    
                    <div class="text-center group">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-edit text-white text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Cargar Notas</h4>
                        <p class="text-gray-600">Ingresa las calificaciones de tus estudiantes de manera eficiente</p>
                    </div>
                    
                    <div class="text-center group">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-chart-bar text-white text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Seguimiento</h4>
                        <p class="text-gray-600">Monitorea el progreso y rendimiento de tus estudiantes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let materiaActual = null;

function mostrarSemestres(materiaId, materiaNombre) {
    materiaActual = materiaId;
    document.getElementById('modalTitle').textContent = `Seleccionar Semestre - ${materiaNombre}`;
    document.getElementById('semestreModal').classList.remove('hidden');
    
    // Cargar semestres
    console.log('Cargando semestres para materia ID:', materiaId);
    fetch(`/api/semestres-por-materia/${materiaId}`)
        .then(res => {
            console.log('Respuesta del servidor:', res.status, res.statusText);
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            const select = document.getElementById('semestreSelect');
            select.innerHTML = '';
            
            if (data.length === 0) {
                select.innerHTML = '<option value="">No hay semestres disponibles</option>';
            } else {
                data.forEach(function(semestre) {
                    const option = document.createElement('option');
                    option.value = semestre.id;
                    option.textContent = semestre.nombre_completo || semestre.nombre;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error cargando semestres:', error);
            document.getElementById('semestreSelect').innerHTML = '<option value="">Error al cargar semestres: ' + error.message + '</option>';
        });
}

function cerrarModal() {
    document.getElementById('semestreModal').classList.add('hidden');
    materiaActual = null;
}

function irAEvaluaciones() {
    const semestreId = document.getElementById('semestreSelect').value;
    if (semestreId && materiaActual) {
        window.location.href = `/evaluaciones/${materiaActual}/${semestreId}`;
    } else {
        alert('Por favor selecciona un semestre');
    }
}

// Búsqueda en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchMaterias');
    const materiaCards = document.querySelectorAll('.materia-card');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        materiaCards.forEach(card => {
            const nombre = card.querySelector('.materia-nombre').textContent.toLowerCase();
            if (nombre.includes(searchTerm)) {
                card.style.display = 'block';
                card.style.animation = 'fadeIn 0.3s ease-out';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Cerrar modal al hacer clic fuera
    document.getElementById('semestreModal').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModal();
        }
    });

    // Cerrar modal con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal();
        }
    });
});
</script>

<style>
@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(10px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}
</style>
@endpush
@endsection 