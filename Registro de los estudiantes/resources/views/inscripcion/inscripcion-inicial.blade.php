@extends('layouts.app.layout')

@section('title', 'Inscripción Inicial')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full mb-6">
                <i class="fas fa-graduation-cap text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">¡Bienvenido a tu Inscripción!</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Selecciona la carrera que deseas estudiar y comienza tu camino académico
            </p>
        </div>

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

        <!-- Información del estudiante -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-user mr-3 text-blue-600"></i>
                    Información del Estudiante
                </h2>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $estudiante->nombre }} {{ $estudiante->apellido_paterno }} {{ $estudiante->apellido_materno }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Matrícula</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $estudiante->matricula }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $estudiante->correo }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Activo
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selección de Carrera -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-2xl font-bold text-gray-900">Selecciona tu Carrera</h2>
                <p class="text-gray-600 mt-1">Elige la carrera que deseas estudiar</p>
            </div>
            
            <div class="p-8">
                <form action="{{ route('inscripcion.procesar-inicial') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($carreras as $carrera)
                            <div class="relative">
                                <input type="radio" 
                                       name="carrera_id" 
                                       id="carrera_{{ $carrera->id }}" 
                                       value="{{ $carrera->id }}" 
                                       class="sr-only" 
                                       required>
                                <label for="carrera_{{ $carrera->id }}" 
                                       class="block w-full p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:shadow-md transition-all duration-200 bg-white">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-university text-white text-lg"></i>
                                        </div>
                                        <div class="w-6 h-6 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                            <div class="w-3 h-3 bg-blue-600 rounded-full hidden"></div>
                                        </div>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $carrera->nombre }}</h3>
                                    <p class="text-sm text-gray-600 mb-4">{{ $carrera->descripcion ?? 'Sin descripción disponible' }}</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span>{{ $carrera->duracion ?? '4 años' }}</span>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    @error('carrera_id')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror

                    <!-- Información adicional -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            ¿Qué incluye la inscripción?
                        </h3>
                        <ul class="space-y-2 text-blue-800">
                            <li class="flex items-center">
                                <i class="fas fa-check mr-2 text-green-600"></i>
                                Inscripción automática al primer semestre
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check mr-2 text-green-600"></i>
                                Asignación automática de materias básicas
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check mr-2 text-green-600"></i>
                                Acceso al sistema de notas y evaluaciones
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check mr-2 text-green-600"></i>
                                Seguimiento académico personalizado
                            </li>
                        </ul>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg">
                            <i class="fas fa-check mr-2"></i>
                            Confirmar Inscripción
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const radioButtons = document.querySelectorAll('input[name="carrera_id"]');
    const labels = document.querySelectorAll('label[for^="carrera_"]');
    
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            // Reset all labels
            labels.forEach(label => {
                label.classList.remove('border-blue-500', 'bg-blue-50');
                label.classList.add('border-gray-200', 'bg-white');
                const checkmark = label.querySelector('.w-3.h-3');
                checkmark.classList.add('hidden');
            });
            
            // Highlight selected label
            if (this.checked) {
                const label = document.querySelector(`label[for="${this.id}"]`);
                label.classList.remove('border-gray-200', 'bg-white');
                label.classList.add('border-blue-500', 'bg-blue-50');
                const checkmark = label.querySelector('.w-3.h-3');
                checkmark.classList.remove('hidden');
            }
        });
    });
});
</script>
@endpush
@endsection 