@extends('layouts.app.layout')

@section('title', 'Detalles del Semestre')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('semestres.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Semestres
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-900">{{ $semestre->nombre }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 mt-4">{{ $semestre->nombre }}</h1>
            <p class="text-gray-600 mt-2">Gestión de materias y estudiantes del semestre</p>
        </div>

        <!-- Información del Semestre -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Información Principal -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900">Información del Semestre</h2>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Nombre</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $semestre->nombre }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">ID</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $semestre->id }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Carreras Asociadas</label>
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($semestre->carreras as $carrera)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-university mr-1"></i>
                                                {{ $carrera->nombre }}
                                            </span>
                                        @empty
                                            <span class="text-gray-500">No hay carreras asociadas</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Materias</label>
                                    <p class="text-2xl font-bold text-purple-600">{{ $semestre->materias->count() }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Estudiantes</label>
                                    <p class="text-2xl font-bold text-green-600">{{ $semestre->estudiantes->count() }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Estado</label>
                                    @if($semestre->momento_academico_activo)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-pause-circle mr-1"></i>
                                            Inactivo
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
                    <div class="space-y-3">
                        <a href="{{ route('materia-semestre.index') }}?semestre={{ $semestre->id }}" 
                           class="w-full flex items-center justify-center px-4 py-3 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Gestionar Materias
                        </a>
                        <a href="{{ route('semestres.edit', $semestre->id) }}" 
                           class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Editar Semestre
                        </a>
                        <a href="{{ route('carreras.index') }}" 
                           class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <i class="fas fa-university mr-2"></i>
                            Ver Carreras
                        </a>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Estadísticas</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Créditos</span>
                            <span class="text-lg font-semibold text-gray-900">{{ $semestre->materias->sum('creditos') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Promedio Créditos</span>
                            <span class="text-lg font-semibold text-gray-900">
                                {{ $semestre->materias->count() > 0 ? round($semestre->materias->avg('creditos'), 1) : 0 }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Materias Activas</span>
                            <span class="text-lg font-semibold text-green-600">{{ $semestre->materias->where('estado', true)->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materias del Semestre -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Materias del Semestre</h2>
                        <p class="text-gray-600 mt-1">Gestiona las materias asignadas a este semestre</p>
                    </div>
                    <a href="{{ route('materia-semestre.index') }}?semestre={{ $semestre->id }}" 
                       class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Gestionar Materias
                    </a>
                </div>
            </div>
            
            <div class="p-8">
                @if($semestre->materias->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($semestre->materias as $materia)
                        <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-all duration-200">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-book text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $materia->nombre }}</h3>
                                        <p class="text-sm text-gray-600">{{ $materia->codigo }}</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('materias.show', $materia->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 p-1 rounded-md hover:bg-blue-50 transition-colors duration-200"
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('materias.edit', $materia->id) }}" 
                                       class="text-amber-600 hover:text-amber-800 p-1 rounded-md hover:bg-amber-50 transition-colors duration-200"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Créditos</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $materia->creditos }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Estado</span>
                                    @if($materia->estado)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Activa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Inactiva
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Profesores</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $materia->profesores->count() }}</span>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex space-x-2">
                                    <a href="{{ route('materias.show', $materia->id) }}" 
                                       class="flex-1 text-center px-3 py-2 text-xs bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        Ver
                                    </a>
                                    <a href="{{ route('evaluaciones.index') }}?materia={{ $materia->id }}" 
                                       class="flex-1 text-center px-3 py-2 text-xs bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors duration-200">
                                        <i class="fas fa-clipboard-list mr-1"></i>
                                        Evaluaciones
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay materias asignadas</h3>
                        <p class="text-gray-500 mb-6">Este semestre aún no tiene materias configuradas.</p>
                        <a href="{{ route('materia-semestre.index') }}?semestre={{ $semestre->id }}" 
                           class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Asignar Materias
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Estudiantes del Semestre -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Estudiantes del Semestre</h2>
                        <p class="text-gray-600 mt-1">Estudiantes inscritos en este semestre</p>
                    </div>
                    <button onclick="openInscriptionModal()" 
                            class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                        <i class="fas fa-user-plus mr-2"></i>
                        Inscribir Estudiante
                    </button>
                </div>
            </div>
            
            <div class="p-8">
                @if($semestre->estudiantes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estudiante
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Carrera
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Materias Inscritas
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($semestre->estudiantes as $estudiante)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                                    <span class="text-white font-semibold text-sm">
                                                        {{ substr($estudiante->nombre, 0, 1) }}{{ substr($estudiante->apellido, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $estudiante->nombre }} {{ $estudiante->apellido }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $estudiante->matricula }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if($estudiante->carreras->count() > 0)
                                                {{ $estudiante->carreras->first()->nombre }}
                                            @else
                                                <span class="text-gray-500">Sin carrera</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($estudiante->estado_estudiante === 'Activo')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $estudiante->materias->where('pivot.semestre_id', $semestre->id)->count() }} materias
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('estudiantes.show', $estudiante->id) }}" 
                                               class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-50 transition-colors duration-200"
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('semestres.removeEstudiante', [$semestre->id, $estudiante->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition-colors duration-200"
                                                        onclick="return confirm('¿Estás seguro de remover este estudiante del semestre?')"
                                                        title="Remover">
                                                    <i class="fas fa-user-minus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay estudiantes inscritos</h3>
                        <p class="text-gray-500 mb-6">Este semestre aún no tiene estudiantes inscritos.</p>
                        <button onclick="openInscriptionModal()" 
                                class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                            <i class="fas fa-user-plus mr-2"></i>
                            Inscribir Primer Estudiante
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Inscripción -->
<div id="inscriptionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Inscribir Estudiante</h3>
            <form action="{{ route('semestres.inscribir', $semestre->id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="estudiantes_id" class="block text-sm font-medium text-gray-700 mb-2">Estudiante</label>
                        <select id="estudiantes_id" name="estudiantes_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccionar estudiante</option>
                            @foreach($estudiantes as $estudiante)
                                <option value="{{ $estudiante->id }}">{{ $estudiante->nombre }} {{ $estudiante->apellido }} - {{ $estudiante->matricula }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="materias_id" class="block text-sm font-medium text-gray-700 mb-2">Materia</label>
                        <select id="materias_id" name="materias_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccionar materia</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id }}">{{ $materia->nombre }} ({{ $materia->codigo }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="secciones_id" class="block text-sm font-medium text-gray-700 mb-2">Sección (Opcional)</label>
                        <select id="secciones_id" name="secciones_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sin sección</option>
                            @foreach($secciones as $seccion)
                                <option value="{{ $seccion->id }}">{{ $seccion->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeInscriptionModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Inscribir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openInscriptionModal() {
    document.getElementById('inscriptionModal').classList.remove('hidden');
}

function closeInscriptionModal() {
    document.getElementById('inscriptionModal').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera de él
document.getElementById('inscriptionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeInscriptionModal();
    }
});
</script>
@endpush
@endsection