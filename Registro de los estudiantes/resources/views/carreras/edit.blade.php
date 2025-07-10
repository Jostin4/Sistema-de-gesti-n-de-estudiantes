{{-- resources/views/carreras/edit.blade.php --}}
@extends('layouts.app.layout')

@section('title', 'Editar Carrera')

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
                            <a href="{{ route('carreras.show', $carrera->id) }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                {{ $carrera->nombre }}
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-900">Editar</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 mt-4">Editar Carrera</h1>
            <p class="text-gray-600 mt-2">Modifica la información de "{{ $carrera->nombre }}"</p>
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

        <!-- Información Actual -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                Información Actual
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Estudiantes</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $carrera->estudiantes->count() }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Semestres</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $carrera->semestres->count() }}/{{ $carrera->numero_semestres }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Materias</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $carrera->materias->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Formulario principal -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-2xl font-bold text-gray-900">Modificar Información</h2>
                <p class="text-gray-600 mt-1">Actualiza los datos de la carrera según sea necesario</p>
            </div>
            
            <form action="{{ route('carreras.update', $carrera->id) }}" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PUT')
                
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
                                   value="{{ old('nombre', $carrera->nombre) }}" 
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
                                   value="{{ old('codigo', $carrera->codigo) }}" 
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
                            <option value="Técnico" {{ old('nivel_educacion', $carrera->nivel_educacion) == 'Técnico' ? 'selected' : '' }}>Técnico (6 semestres)</option>
                            <option value="Ingeniería" {{ old('nivel_educacion', $carrera->nivel_educacion) == 'Ingeniería' ? 'selected' : '' }}>Ingeniería (10 semestres)</option>
                            <option value="Licenciatura" {{ old('nivel_educacion', $carrera->nivel_educacion) == 'Licenciatura' ? 'selected' : '' }}>Licenciatura (8 semestres)</option>
                            <option value="Maestría" {{ old('nivel_educacion', $carrera->nivel_educacion) == 'Maestría' ? 'selected' : '' }}>Maestría (4 semestres)</option>
                            <option value="Doctorado" {{ old('nivel_educacion', $carrera->nivel_educacion) == 'Doctorado' ? 'selected' : '' }}>Doctorado (6 semestres)</option>
                        </select>
                        @error('nivel_educacion')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        
                        <!-- Información sobre semestres actuales -->
                        <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                <span class="text-sm text-blue-800">
                                    <strong>Semestres actuales:</strong> {{ $carrera->semestres->count() }}/{{ $carrera->numero_semestres }}
                                </span>
                            </div>
                        </div>
                        
                        <p class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                            Al cambiar el nivel educativo, los semestres se reasignarán automáticamente según el nuevo nivel.
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
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $carrera->descripcion) }}</textarea>
                        @error('descripcion')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <div class="mt-2 flex justify-between items-center">
                            <p class="text-sm text-gray-500">
                                Esta descripción será visible para estudiantes y profesores. Máximo 1000 caracteres.
                            </p>
                            <span id="char-count" class="text-sm text-gray-400">0/1000</span>
                        </div>
                    </div>
                </div>

                <!-- Estado de la Carrera -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-toggle-on mr-2 text-green-500"></i>
                        Estado de la Carrera
                    </h3>
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <input type="checkbox" 
                                       id="estado" 
                                       name="estado" 
                                       value="1" 
                                       {{ old('estado', $carrera->estado) ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            </div>
                            <div class="ml-3">
                                <label for="estado" class="text-sm font-medium text-gray-900">
                                    Carrera Activa
                                </label>
                                <p class="text-sm text-gray-600 mt-1">
                                    Marca esta opción si la carrera está activa y disponible para inscripciones. 
                                    Si la desmarcas, los estudiantes no podrán inscribirse en esta carrera.
                                </p>
                            </div>
                        </div>
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
                            Los cambios se aplicarán inmediatamente a todos los estudiantes inscritos
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Si cambias el número de semestres, revisa las asociaciones existentes
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            El código de carrera debe ser único en toda la institución
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Puedes desactivar la carrera sin perder la información
                        </li>
                    </ul>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div class="flex space-x-3">
                        <a href="{{ route('carreras.show', $carrera->id) }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <i class="fas fa-eye mr-2"></i>
                            Ver Detalles
                        </a>
                        <a href="{{ route('carreras.index') }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>
                    </div>
                    <button type="submit" 
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 border border-transparent rounded-lg font-medium text-white hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Actualizar Carrera
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
    const semestresAsociados = {{ $carrera->semestres->count() }};
    
    numeroSemestresInput.addEventListener('input', function() {
        const value = parseInt(this.value);
        if (value < 1) {
            this.setCustomValidity('El número de semestres debe ser al menos 1');
        } else if (value > 20) {
            this.setCustomValidity('El número de semestres no puede exceder 20');
        } else if (value < semestresAsociados) {
            this.setCustomValidity(`No puedes reducir a menos de ${semestresAsociados} semestres porque ya tienes semestres asociados`);
        } else {
            this.setCustomValidity('');
        }
    });

    // Contador de caracteres para la descripción
    const descripcionTextarea = document.getElementById('descripcion');
    const charCount = document.getElementById('char-count');
    const maxLength = 1000;
    
    function updateCharCount() {
        const remaining = maxLength - descripcionTextarea.value.length;
        charCount.textContent = `${descripcionTextarea.value.length}/${maxLength}`;
        
        if (remaining < 0) {
            descripcionTextarea.value = descripcionTextarea.value.substring(0, maxLength);
            charCount.textContent = `${maxLength}/${maxLength}`;
        }
        
        // Cambiar color según el uso
        if (descripcionTextarea.value.length > maxLength * 0.9) {
            charCount.className = 'text-sm text-red-500';
        } else if (descripcionTextarea.value.length > maxLength * 0.7) {
            charCount.className = 'text-sm text-yellow-500';
        } else {
            charCount.className = 'text-sm text-gray-400';
        }
    }
    
    descripcionTextarea.addEventListener('input', updateCharCount);
    updateCharCount(); // Inicializar contador
});
</script>
@endpush
@endsection
