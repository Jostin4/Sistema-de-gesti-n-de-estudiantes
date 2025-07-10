@extends('layouts.app.layout')

@section('title', 'Cargar Notas')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full mb-4">
                <i class="fas fa-edit text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Cargar Notas - {{ $evaluacion->nombre ?? 'Evaluación' }}
            </h1>
            <p class="text-lg text-gray-600">
                {{ $evaluacion->materia->nombre ?? 'Materia' }} - 
                {{ $evaluacion->semestre->nombre ?? 'Semestre' }}
            </p>
        </div>

        @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-green-800">¡Notas guardadas!</h3>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-red-800">Error</h3>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-red-800">Error al guardar notas</h3>
                        <ul class="mt-2 text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Información de la evaluación -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-xl font-bold text-gray-900">Información de la Evaluación</h2>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-tasks text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-600">Evaluación</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $evaluacion->nombre ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-book text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-green-600">Materia</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $evaluacion->materia->nombre ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-alt text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-purple-600">Semestre</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $evaluacion->semestre->nombre ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($evaluacion->porcentaje)
                    <div class="mt-6 bg-yellow-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-percentage text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-yellow-600">Porcentaje de la Evaluación</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $evaluacion->porcentaje }}%</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Formulario de notas -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Calificaciones de Estudiantes</h2>
                        <p class="text-gray-600 mt-1">{{ $estudiantes->count() }} estudiantes inscritos</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <input type="text" 
                                   id="searchEstudiantes" 
                                   placeholder="Buscar estudiante..." 
                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                @if($estudiantes->count() > 0)
                    <form action="{{ route('notas.guardar', $evaluacion->id) }}" method="POST">
                        @csrf
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estudiante
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nota
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($estudiantes as $estudiante)
                                        <tr class="estudiante-row hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                            <i class="fas fa-user text-green-600"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 estudiante-nombre">
                                                            {{ $estudiante->nombre ?? 'N/A' }} 
                                                            {{ $estudiante->apellido_paterno ?? '' }} 
                                                            {{ $estudiante->apellido_materno ?? '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $estudiante->email ?? 'Sin email' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <input type="number" 
                                                           name="notas[{{ $estudiante->id }}]" 
                                                           class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                                           min="0" 
                                                           max="100" 
                                                           step="0.01" 
                                                           value="{{ $notas[$estudiante->id]->nota ?? '' }}"
                                                           placeholder="0.00">
                                                    <span class="text-sm text-gray-500">/ 100</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $notaActual = $notas[$estudiante->id]->nota ?? null;
                                                    $estado = $notaActual !== null ? ($notaActual >= 50 ? 'Aprobado' : 'Reprobado') : 'Sin calificar';
                                                    $colorClase = $notaActual !== null ? ($notaActual >= 50 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') : 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClase }}">
                                                    <i class="fas fa-circle mr-1 text-xs"></i>
                                                    {{ $estado }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-8 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                    <i class="fas fa-save mr-2"></i>
                                    Guardar Notas
                                </button>
                                
                                <a href="{{ url()->previous() }}" 
                                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Volver
                                </a>
                            </div>
                            
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                {{ $estudiantes->count() }} estudiantes
                            </div>
                        </div>
                    </form>
                @else
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-users text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay estudiantes inscritos</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            No hay estudiantes inscritos en esta materia y semestre. Verifica las inscripciones antes de cargar notas.
                        </p>
                        <a href="{{ url()->previous() }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchEstudiantes');
    const estudianteRows = document.querySelectorAll('.estudiante-row');

    // Búsqueda en tiempo real
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        estudianteRows.forEach(row => {
            const nombre = row.querySelector('.estudiante-nombre').textContent.toLowerCase();
            if (nombre.includes(searchTerm)) {
                row.style.display = 'table-row';
                row.style.animation = 'fadeIn 0.3s ease-out';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Validación de notas en tiempo real
    const notaInputs = document.querySelectorAll('input[name^="notas"]');
    notaInputs.forEach(input => {
        input.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (value < 0) this.value = 0;
            if (value > 100) this.value = 100;
        });
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