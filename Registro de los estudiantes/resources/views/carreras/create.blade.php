{{-- resources/views/carreras/create.blade.php --}}
@extends('layouts.app.layout')

@section('title', 'Crear Nueva Carrera')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
                            <span class="text-sm font-medium text-gray-900">Crear Nueva</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 mt-4">Crear Nueva Carrera</h1>
            <p class="text-gray-600 mt-2">Agrega una nueva carrera a la institución con toda su información</p>
        </div>

        @if($errors->any())
            <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-red-800">Error de Validación</h3>
                        <ul class="mt-2 text-red-700 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulario principal -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-2xl font-bold text-gray-900">Información de la Carrera</h2>
                <p class="text-gray-600 mt-1">Completa todos los datos requeridos para crear la nueva carrera</p>
            </div>
            
            <form action="{{ route('carreras.store') }}" method="POST" class="p-8 space-y-8">
                @csrf
                
                <!-- Información Básica -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-university mr-2 text-blue-500"></i>
                        Información Básica
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-graduation-cap mr-1 text-blue-500"></i>
                                Nombre de la Carrera *
                            </label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre') }}" 
                                   placeholder="Ej: Ingeniería de Software"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('nombre') border-red-500 @enderror">
                            @error('nombre')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-barcode mr-1 text-green-500"></i>
                                Código de Carrera
                            </label>
                            <input type="text" 
                                   id="codigo" 
                                   name="codigo" 
                                   value="{{ old('codigo') }}" 
                                   placeholder="Ej: ISW-2024"
                                   maxlength="10"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('codigo') border-red-500 @enderror">
                            @error('codigo')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Configuración Académica -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>
                        Configuración Académica
                    </h3>
                    
                    <div>
                        <label for="nivel_educacion" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-graduation-cap mr-1 text-purple-500"></i>
                            Nivel Educativo *
                        </label>
                        <select id="nivel_educacion" 
                                name="nivel_educacion" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 @error('nivel_educacion') border-red-500 @enderror">
                            <option value="">Selecciona el nivel educativo</option>
                            <option value="Técnico" {{ old('nivel_educacion') == 'Técnico' ? 'selected' : '' }}>Técnico (6 semestres)</option>
                            <option value="Ingeniería" {{ old('nivel_educacion') == 'Ingeniería' ? 'selected' : '' }}>Ingeniería (10 semestres)</option>
                            <option value="Licenciatura" {{ old('nivel_educacion') == 'Licenciatura' ? 'selected' : '' }}>Licenciatura (8 semestres)</option>
                            <option value="Maestría" {{ old('nivel_educacion') == 'Maestría' ? 'selected' : '' }}>Maestría (4 semestres)</option>
                            <option value="Doctorado" {{ old('nivel_educacion') == 'Doctorado' ? 'selected' : '' }}>Doctorado (6 semestres)</option>
                        </select>
                        @error('nivel_educacion')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                            Los semestres se asignarán automáticamente según el nivel educativo seleccionado.
                        </p>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-align-left mr-2 text-orange-500"></i>
                        Descripción
                    </h3>
                    
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-info-circle mr-1 text-orange-500"></i>
                            Descripción de la Carrera
                        </label>
                        <textarea id="descripcion" 
                                  name="descripcion" 
                                  rows="4"
                                  placeholder="Describe brevemente la carrera, sus objetivos, perfil del egresado y características principales..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">
                            Esta descripción será visible para estudiantes y profesores. Máximo 1000 caracteres.
                        </p>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Información Importante
                    </h3>
                    <ul class="space-y-2 text-blue-800">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Una vez creada la carrera, podrás asociar semestres y materias
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Los estudiantes podrán inscribirse en esta carrera
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Puedes editar la información de la carrera en cualquier momento
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            El código de carrera es opcional pero recomendado para identificación
                        </li>
                    </ul>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('carreras.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 border border-transparent rounded-lg font-medium text-white hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Crear Carrera
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación en tiempo real del número de semestres
    const numeroSemestresInput = document.getElementById('numero_semestres');
    numeroSemestresInput.addEventListener('input', function() {
        const value = parseInt(this.value);
        if (value < 1) {
            this.setCustomValidity('El número de semestres debe ser al menos 1');
        } else if (value > 20) {
            this.setCustomValidity('El número de semestres no puede exceder 20');
        } else {
            this.setCustomValidity('');
        }
    });

    // Contador de caracteres para la descripción
    const descripcionTextarea = document.getElementById('descripcion');
    const maxLength = 1000;
    
    descripcionTextarea.addEventListener('input', function() {
        const remaining = maxLength - this.value.length;
        if (remaining < 0) {
            this.value = this.value.substring(0, maxLength);
        }
    });
});
</script>
@endpush
@endsection
