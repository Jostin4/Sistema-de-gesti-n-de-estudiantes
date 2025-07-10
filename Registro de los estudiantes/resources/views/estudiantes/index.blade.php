@extends('layouts.app.layout')

@section('title', 'Gestión de Estudiantes')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestión de Estudiantes</h1>
                    <p class="text-gray-600 mt-2">Administra y gestiona todos los estudiantes de la institución</p>
                </div>
                <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('estudiantes.exportar') }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>
                        Exportar
                    </a>
                    <a href="{{ route('estudiantes.create') }}" 
                       class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 border border-transparent rounded-lg font-medium text-white hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Nuevo Estudiante
                    </a>
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
                        <h3 class="text-lg font-medium text-green-800">Éxito</h3>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-graduate text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Estudiantes</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $estudiantes->total() }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +12% este mes
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-male text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Hombres</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $estudiantes->where('genero', 'Masculino')->count() }}</p>
                        <p class="text-xs text-blue-600 mt-1">
                            {{ $estudiantes->total() > 0 ? round(($estudiantes->where('genero', 'Masculino')->count() / $estudiantes->total()) * 100, 1) : 0 }}% del total
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-pink-400 to-pink-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-female text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Mujeres</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $estudiantes->where('genero', 'Femenino')->count() }}</p>
                        <p class="text-xs text-pink-600 mt-1">
                            {{ $estudiantes->total() > 0 ? round(($estudiantes->where('genero', 'Femenino')->count() / $estudiantes->total()) * 100, 1) : 0 }}% del total
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-university text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Con Carrera</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $estudiantes->whereNotNull('carreras')->count() }}</p>
                        <p class="text-xs text-purple-600 mt-1">
                            {{ $estudiantes->total() > 0 ? round(($estudiantes->whereNotNull('carreras')->count() / $estudiantes->total()) * 100, 1) : 0 }}% asignados
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Búsqueda y Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <form action="{{ route('estudiantes.buscar') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="q" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search mr-1 text-blue-500"></i>
                            Buscar
                        </label>
                        <input type="text" 
                               id="q" 
                               name="q" 
                               value="{{ request('q') }}" 
                               placeholder="Nombre, apellido, matrícula o correo"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>
                    
                    <div>
                        <label for="genero" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-venus-mars mr-1 text-purple-500"></i>
                            Género
                        </label>
                        <select id="genero" 
                                name="genero" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200">
                            <option value="">Todos los géneros</option>
                            <option value="Masculino" {{ request('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ request('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ request('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-toggle-on mr-1 text-green-500"></i>
                            Estado
                        </label>
                        <select id="estado" 
                                name="estado" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">
                            <option value="">Todos los estados</option>
                            <option value="Activo" {{ request('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactivo" {{ request('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Buscar
                    </button>
                    
                    @if(request('q') || request('genero') || request('estado'))
                        <a href="{{ route('estudiantes.index') }}" 
                           class="inline-flex items-center px-4 py-3 bg-gray-600 border border-transparent rounded-lg font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Limpiar Filtros
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabla de estudiantes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Lista de Estudiantes</h3>
                    <span class="text-sm text-gray-500">{{ $estudiantes->total() }} estudiantes encontrados</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-user mr-1"></i>
                                Estudiante
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-envelope mr-1"></i>
                                Contacto
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-graduation-cap mr-1"></i>
                                Carrera
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-toggle-on mr-1"></i>
                                Estado
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-calendar mr-1"></i>
                                Registro
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-cogs mr-1"></i>
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($estudiantes as $estudiante)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-400 to-indigo-600 flex items-center justify-center">
                                            <span class="text-white font-semibold text-lg">
                                                {{ strtoupper(substr($estudiante->nombre, 0, 1) . substr($estudiante->apellido_paterno, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $estudiante->nombre }} {{ $estudiante->apellido_paterno }} {{ $estudiante->apellido_materno }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-id-card mr-1"></i>
                                            {{ $estudiante->matricula }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-birthday-cake mr-1"></i>
                                            {{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->age }} años
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <i class="fas fa-envelope mr-1 text-blue-500"></i>
                                    {{ $estudiante->correo }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-phone mr-1 text-green-500"></i>
                                    {{ $estudiante->telefono }}
                                </div>
                                @if($estudiante->user)
                                    <div class="text-xs text-green-600 mt-1">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Tiene cuenta de usuario
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($estudiante->carreras->count() > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($estudiante->carreras->unique('id') as $carrera)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-graduation-cap mr-1"></i>
                                                {{ $carrera->nombre }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">
                                        <i class="fas fa-minus-circle mr-1"></i>
                                        Sin asignar
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $estadoColors = [
                                        'Activo' => 'bg-green-100 text-green-800',
                                        'Inactivo' => 'bg-red-100 text-red-800'
                                    ];
                                    $estadoIcons = [
                                        'Activo' => 'fas fa-check-circle',
                                        'Inactivo' => 'fas fa-times-circle'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $estadoColors[$estudiante->estado] ?? 'bg-gray-100 text-gray-800' }}">
                                    <i class="{{ $estadoIcons[$estudiante->estado] ?? 'fas fa-circle' }} mr-1"></i>
                                    {{ ucfirst($estudiante->estado) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div>
                                    <i class="fas fa-calendar-plus mr-1"></i>
                                    {{ $estudiante->created_at->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $estudiante->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('estudiantes.show', $estudiante->id) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                       title="Ver detalles">
                                        <i class="fas fa-eye mr-1"></i>
                                        Ver
                                    </a>
                                    <a href="{{ route('estudiantes.edit', $estudiante->id) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-amber-700 bg-amber-100 hover:bg-amber-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors duration-200"
                                       title="Editar">
                                        <i class="fas fa-edit mr-1"></i>
                                        Editar
                                    </a>
                                    <form action="{{ route('estudiantes.destroy', $estudiante->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                                onclick="return confirm('¿Estás seguro de eliminar este estudiante? Esta acción no se puede deshacer.')"
                                                title="Eliminar">
                                            <i class="fas fa-trash mr-1"></i>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-user-graduate text-gray-400 text-3xl"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay estudiantes</h3>
                                    <p class="text-gray-500 mb-6 max-w-md">Comienza registrando el primer estudiante de la institución. Podrás gestionar su información académica y seguimiento.</p>
                                    <a href="{{ route('estudiantes.create') }}" 
                                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 border border-transparent rounded-lg font-medium text-white hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg">
                                        <i class="fas fa-plus mr-2"></i>
                                        Registrar Primer Estudiante
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            @if($estudiantes->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $estudiantes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit del formulario cuando cambien los filtros
    const filterSelects = document.querySelectorAll('select[name="genero"], select[name="estado"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
    
    // Búsqueda con debounce
    const searchInput = document.getElementById('q');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.closest('form').submit();
        }, 500);
    });
});
</script>
@endpush
@endsection
