{{-- resources/views/estudiantes/create.blade.php --}}
@extends('layouts.app.layout')

@section('title', 'Registrar Nuevo Estudiante')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('estudiantes.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Estudiantes
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-900">Registrar Nuevo</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 mt-4">Registrar Nuevo Estudiante</h1>
            <p class="text-gray-600 mt-2">Completa la información del estudiante para registrarlo en el sistema</p>
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

        <!-- Formulario principal -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-2xl font-bold text-gray-900">Información Personal</h2>
                <p class="text-gray-600 mt-1">Datos básicos del estudiante</p>
            </div>
            
            <form action="{{ route('estudiantes.store') }}" method="POST" class="p-8 space-y-8">
                @csrf
                
                <!-- Información Personal -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Datos Personales
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-1 text-blue-500"></i>
                                Nombre *
                            </label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre') }}" 
                                   placeholder="Primer nombre"
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
                            <label for="segundo_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-1 text-gray-400"></i>
                                Segundo Nombre
                            </label>
                            <input type="text" 
                                   id="segundo_nombre" 
                                   name="segundo_nombre" 
                                   value="{{ old('segundo_nombre') }}" 
                                   placeholder="Segundo nombre (opcional)"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('segundo_nombre') border-red-500 @enderror">
                            @error('segundo_nombre')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="apellido_paterno" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-1 text-blue-500"></i>
                                Apellido Paterno *
                            </label>
                            <input type="text" 
                                   id="apellido_paterno" 
                                   name="apellido_paterno" 
                                   value="{{ old('apellido_paterno') }}" 
                                   placeholder="Apellido paterno"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('apellido_paterno') border-red-500 @enderror">
                            @error('apellido_paterno')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="apellido_materno" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-1 text-blue-500"></i>
                                Apellido Materno *
                            </label>
                            <input type="text" 
                                   id="apellido_materno" 
                                   name="apellido_materno" 
                                   value="{{ old('apellido_materno') }}" 
                                   placeholder="Apellido materno"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('apellido_materno') border-red-500 @enderror">
                            @error('apellido_materno')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-address-book mr-2 text-green-500"></i>
                        Información de Contacto
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="correo" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-1 text-green-500"></i>
                                Correo Electrónico *
                            </label>
                            <input type="email" 
                                   id="correo" 
                                   name="correo" 
                                   value="{{ old('correo') }}" 
                                   placeholder="ejemplo@correo.com"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('correo') border-red-500 @enderror">
                            @error('correo')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone mr-1 text-green-500"></i>
                                Teléfono *
                            </label>
                            <input type="tel" 
                                   id="telefono" 
                                   name="telefono" 
                                   value="{{ old('telefono') }}" 
                                   placeholder="(555) 123-4567"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('telefono') border-red-500 @enderror">
                            @error('telefono')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-purple-500"></i>
                        Información Adicional
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar mr-1 text-purple-500"></i>
                                Fecha de Nacimiento *
                            </label>
                            <input type="date" 
                                   id="fecha_nacimiento" 
                                   name="fecha_nacimiento" 
                                   value="{{ old('fecha_nacimiento') }}" 
                                   required
                                   max="{{ date('Y-m-d', strtotime('-16 years')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 @error('fecha_nacimiento') border-red-500 @enderror">
                            @error('fecha_nacimiento')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="genero" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-venus-mars mr-1 text-purple-500"></i>
                                Género *
                            </label>
                            <select id="genero" 
                                    name="genero" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 @error('genero') border-red-500 @enderror">
                                <option value="">Selecciona el género</option>
                                <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('genero')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="carrera_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-graduation-cap mr-1 text-purple-500"></i>
                                Carrera
                            </label>
                            <select id="carrera_id" 
                                    name="carrera_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 @error('carrera_id') border-red-500 @enderror">
                                <option value="">Selecciona una carrera (opcional)</option>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->id }}" {{ old('carrera_id') == $carrera->id ? 'selected' : '' }}>
                                        {{ $carrera->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('carrera_id')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dirección -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 text-orange-500"></i>
                        Dirección (Opcional)
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-home mr-1 text-orange-500"></i>
                                Dirección
                            </label>
                            <input type="text" 
                                   id="direccion" 
                                   name="direccion" 
                                   value="{{ old('direccion') }}" 
                                   placeholder="Calle, número, colonia"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200">
                        </div>
                        
                        <div>
                            <label for="ciudad" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-city mr-1 text-orange-500"></i>
                                Ciudad
                            </label>
                            <input type="text" 
                                   id="ciudad" 
                                   name="ciudad" 
                                   value="{{ old('ciudad') }}" 
                                   placeholder="Ciudad"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200">
                        </div>
                        
                        <div>
                            <label for="codigo_postal" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-mail-bulk mr-1 text-orange-500"></i>
                                Código Postal
                            </label>
                            <input type="text" 
                                   id="codigo_postal" 
                                   name="codigo_postal" 
                                   value="{{ old('codigo_postal') }}" 
                                   placeholder="12345"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200">
                        </div>
                    </div>
                </div>

                <!-- Crear Usuario -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user-plus mr-2 text-indigo-500"></i>
                        Cuenta de Usuario
                    </h3>
                    
                    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <input type="checkbox" 
                                       id="crear_usuario" 
                                       name="crear_usuario" 
                                       value="1" 
                                       {{ old('crear_usuario') ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            </div>
                            <div class="ml-3">
                                <label for="crear_usuario" class="text-sm font-medium text-indigo-900">
                                    Crear cuenta de usuario para el estudiante
                                </label>
                                <p class="text-sm text-indigo-700 mt-1">
                                    Esto permitirá al estudiante acceder al sistema con su correo electrónico
                                </p>
                            </div>
                        </div>
                        
                        <div id="password_fields" class="mt-6 space-y-4" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-lock mr-1 text-indigo-500"></i>
                                        Contraseña *
                                    </label>
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Mínimo 8 caracteres"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-lock mr-1 text-indigo-500"></i>
                                        Confirmar Contraseña *
                                    </label>
                                    <input type="password" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Repite la contraseña"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                </div>
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
                            La matrícula se generará automáticamente
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Los campos marcados con * son obligatorios
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Si creas una cuenta de usuario, el estudiante podrá acceder al sistema
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            La fecha de nacimiento debe ser de al menos 16 años atrás
                        </li>
                    </ul>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('estudiantes.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Registrar Estudiante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const crearUsuarioCheckbox = document.getElementById('crear_usuario');
    const passwordFields = document.getElementById('password_fields');
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');

    // Mostrar/ocultar campos de contraseña
    crearUsuarioCheckbox.addEventListener('change', function() {
        if (this.checked) {
            passwordFields.style.display = 'block';
            passwordInput.required = true;
            passwordConfirmationInput.required = true;
        } else {
            passwordFields.style.display = 'none';
            passwordInput.required = false;
            passwordConfirmationInput.required = false;
            passwordInput.value = '';
            passwordConfirmationInput.value = '';
        }
    });

    // Validación de contraseña en tiempo real
    passwordConfirmationInput.addEventListener('input', function() {
        if (passwordInput.value !== this.value) {
            this.setCustomValidity('Las contraseñas no coinciden');
        } else {
            this.setCustomValidity('');
        }
    });

    // Validación de fecha de nacimiento
    const fechaNacimiento = document.getElementById('fecha_nacimiento');
    const fechaMinima = new Date();
    fechaMinima.setFullYear(fechaMinima.getFullYear() - 16);
    fechaNacimiento.max = fechaMinima.toISOString().split('T')[0];

    // Validación de teléfono
    const telefonoInput = document.getElementById('telefono');
    telefonoInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9+\-\s\(\)]/g, '');
    });

    // Validación de nombres (solo letras y espacios)
    const nombreInputs = ['nombre', 'segundo_nombre', 'apellido_paterno', 'apellido_materno'];
    nombreInputs.forEach(function(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
            });
        }
    });
});
</script>
@endpush
@endsection
