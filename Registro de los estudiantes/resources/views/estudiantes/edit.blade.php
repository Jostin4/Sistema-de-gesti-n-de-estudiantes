{{-- resources/views/estudiantes/edit.blade.php --}}
@extends('layouts.app.layout')

@section('title', 'Editar Estudiante')

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
                            <span class="text-sm font-medium text-gray-900">Editar Estudiante</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 mt-4">Editar Estudiante</h1>
            <p class="text-gray-600 mt-2">Modifica la información del estudiante {{ $estudiante->nombre }} {{ $estudiante->apellido_paterno }}</p>
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

        @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-green-800">Éxito</h3>
                        <p class="text-green-700">{{ session('success') }}</p>
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
            
            <form action="{{ route('estudiantes.update', $estudiante->id) }}" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PUT')
                
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
                                   value="{{ old('nombre', $estudiante->nombre) }}" 
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
                                   value="{{ old('segundo_nombre', $estudiante->segundo_nombre) }}" 
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
                                   value="{{ old('apellido_paterno', $estudiante->apellido_paterno) }}" 
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
                                   value="{{ old('apellido_materno', $estudiante->apellido_materno) }}" 
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
                                   value="{{ old('correo', $estudiante->correo) }}" 
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
                                   value="{{ old('telefono', $estudiante->telefono) }}" 
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
                                   value="{{ old('fecha_nacimiento', $estudiante->fecha_nacimiento) }}" 
                                   required
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
                                <option value="Masculino" {{ old('genero', $estudiante->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ old('genero', $estudiante->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ old('genero', $estudiante->genero) == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('genero')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-toggle-on mr-1 text-purple-500"></i>
                                Estado *
                            </label>
                            <select id="estado" 
                                    name="estado" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 @error('estado') border-red-500 @enderror">
                                <option value="">Selecciona el estado</option>
                                <option value="Activo" {{ old('estado', $estudiante->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ old('estado', $estudiante->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                <option value="Graduado" {{ old('estado', $estudiante->estado) == 'Graduado' ? 'selected' : '' }}>Graduado</option>
                            </select>
                            @error('estado')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="matricula" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-id-card mr-1 text-purple-500"></i>
                                Matrícula *
                            </label>
                            <input type="text" 
                                   id="matricula" 
                                   name="matricula" 
                                   value="{{ old('matricula', $estudiante->matricula) }}" 
                                   placeholder="Ej: 20230001"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 @error('matricula') border-red-500 @enderror">
                            @error('matricula')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('estudiantes.index') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Actualizar Estudiante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
