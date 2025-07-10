@extends('layouts.app.layout')

@section('title', 'Crear Evaluación - ' . $materia->nombre)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('evaluaciones.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Mis Materias
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('evaluaciones.show', [$materia->id, $semestre->id]) }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                {{ $materia->nombre }}
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-900">Crear Evaluación</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 mt-4">Crear Nueva Evaluación</h1>
            <p class="text-gray-600 mt-2">Define una nueva evaluación para {{ $materia->nombre }} - {{ $semestre->nombre }}</p>
        </div>

        <!-- Contenido principal -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Información de la Evaluación</h2>
                        <p class="text-gray-600 mt-1">Completa los datos para crear la evaluación</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-2">
                            <div class="text-center">
                                <div class="text-lg font-bold text-blue-600">{{ 100 - $porcentajeTotal }}%</div>
                                <div class="text-sm text-blue-500">Disponible</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <form action="{{ route('evaluaciones.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="materia_id" value="{{ $materia->id }}">
                    <input type="hidden" name="semestre_id" value="{{ $semestre->id }}">
                    
                    <!-- Nombre de la evaluación -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-blue-500"></i>
                            Nombre de la Evaluación
                        </label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('nombre') border-red-500 @enderror" 
                               placeholder="Ej: Examen Parcial, Trabajo Práctico, etc."
                               required 
                               value="{{ old('nombre') }}">
                        @error('nombre')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Porcentaje -->
                    <div>
                        <label for="porcentaje" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-percentage mr-2 text-green-500"></i>
                            Porcentaje de Ponderación
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="porcentaje" 
                                   id="porcentaje"
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('porcentaje') border-red-500 @enderror" 
                                   min="1" 
                                   max="{{ 100 - $porcentajeTotal }}" 
                                   required 
                                   value="{{ old('porcentaje') }}"
                                   oninput="actualizarPorcentaje(this.value)">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="text-gray-500 text-sm font-medium">%</span>
                            </div>
                        </div>
                        <div class="mt-2 flex items-center justify-between text-sm">
                            <span class="text-gray-500">Porcentaje restante disponible: <span class="font-medium text-blue-600">{{ 100 - $porcentajeTotal }}%</span></span>
                            <span id="porcentajeRestante" class="text-gray-500">Restante: <span class="font-medium text-green-600">{{ 100 - $porcentajeTotal }}%</span></span>
                        </div>
                        @error('porcentaje')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Barra de progreso -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Distribución de Porcentajes</h3>
                            <span class="text-sm font-medium text-gray-500">
                                <span id="totalActual">{{ $porcentajeTotal }}%</span> + <span id="nuevoPorcentaje">0%</span> = <span id="totalFinal">{{ $porcentajeTotal }}%</span>
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4 relative">
                            <!-- Porcentaje actual -->
                            <div class="bg-blue-500 h-4 rounded-full transition-all duration-300" 
                                 style="width: {{ $porcentajeTotal }}%"></div>
                            <!-- Nuevo porcentaje (se actualiza con JavaScript) -->
                            <div id="nuevoPorcentajeBar" class="bg-green-500 h-4 rounded-full transition-all duration-300 absolute top-0 left-0" 
                                 style="width: 0%; left: {{ $porcentajeTotal }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-2">
                            <span>0%</span>
                            <span>100%</span>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('evaluaciones.show', [$materia->id, $semestre->id]) }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Crear Evaluación
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-lightbulb mr-3 text-green-600"></i>
                    Consejos para Crear Evaluaciones
                </h3>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Distribución Recomendada</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Exámenes parciales: 30-40%
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Trabajos prácticos: 20-30%
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Participación: 10-15%
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Examen final: 30-40%
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Consideraciones</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                El total no puede superar 100%
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                Puedes crear múltiples evaluaciones
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                Los porcentajes se validan automáticamente
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                Una vez creada, podrás cargar notas
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function actualizarPorcentaje(valor) {
    const porcentajeActual = {{ $porcentajeTotal }};
    const nuevoPorcentaje = parseInt(valor) || 0;
    const totalFinal = porcentajeActual + nuevoPorcentaje;
    const porcentajeRestante = 100 - totalFinal;
    
    // Actualizar textos
    document.getElementById('nuevoPorcentaje').textContent = nuevoPorcentaje + '%';
    document.getElementById('totalFinal').textContent = totalFinal + '%';
    document.getElementById('porcentajeRestante').innerHTML = 'Restante: <span class="font-medium text-green-600">' + porcentajeRestante + '%</span>';
    
    // Actualizar barra de progreso
    const nuevaBarra = document.getElementById('nuevoPorcentajeBar');
    nuevaBarra.style.width = nuevoPorcentaje + '%';
    
    // Cambiar color si excede 100%
    if (totalFinal > 100) {
        nuevaBarra.classList.remove('bg-green-500');
        nuevaBarra.classList.add('bg-red-500');
        document.getElementById('porcentajeRestante').innerHTML = 'Restante: <span class="font-medium text-red-600">' + porcentajeRestante + '%</span>';
    } else {
        nuevaBarra.classList.remove('bg-red-500');
        nuevaBarra.classList.add('bg-green-500');
    }
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const porcentajeInput = document.getElementById('porcentaje');
    if (porcentajeInput.value) {
        actualizarPorcentaje(porcentajeInput.value);
    }
});
</script>
@endpush
@endsection 