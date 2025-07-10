@extends('layouts.app.layout')

@section('title', 'Gestión de Materias - ' . $semestre->nombre)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('materia-semestre.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-purple-600">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Gestión de Materias
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
            <p class="text-gray-600 mt-2">Gestión de materias y configuración del semestre</p>
        </div>

        <!-- Información del Semestre -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Información Principal -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
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
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Materias Asignadas</label>
                                    <p class="text-2xl font-bold text-purple-600">{{ $semestre->materias->count() }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Total Créditos</label>
                                    <p class="text-2xl font-bold text-green-600">{{ $semestre->materias->sum('creditos') }}</p>
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
                        <button onclick="openAsignarMateriaModal()" 
                                class="w-full flex items-center justify-center px-4 py-3 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Asignar Materia
                        </button>
                        <a href="{{ route('semestres.show', $semestre->id) }}" 
                           class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <i class="fas fa-eye mr-2"></i>
                            Ver Semestre
                        </a>
                        <button onclick="validarCargaAcademica()" 
                                class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <i class="fas fa-check-circle mr-2"></i>
                            Validar Carga
                        </button>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Estadísticas</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Materias con Profesor</span>
                            <span class="text-lg font-semibold text-green-600">{{ $semestre->materias->filter(function($m) { return $m->profesores->count() > 0; })->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Materias sin Profesor</span>
                            <span class="text-lg font-semibold text-red-600">{{ $semestre->materias->filter(function($m) { return $m->profesores->count() == 0; })->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Promedio Créditos</span>
                            <span class="text-lg font-semibold text-gray-900">
                                {{ $semestre->materias->count() > 0 ? round($semestre->materias->avg('creditos'), 1) : 0 }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Materias Activas</span>
                            <span class="text-lg font-semibold text-blue-600">{{ $semestre->materias->where('estado', true)->count() }}</span>
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
                    <button onclick="openAsignarMateriaModal()" 
                            class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Asignar Materia
                    </button>
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
                                    <button onclick="openConfiguracionModal({{ $materia->id }})" 
                                            class="text-blue-600 hover:text-blue-800 p-1 rounded-md hover:bg-blue-50 transition-colors duration-200"
                                            title="Configurar">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <button onclick="openAsignarProfesorModal({{ $materia->id }})" 
                                            class="text-green-600 hover:text-green-800 p-1 rounded-md hover:bg-green-50 transition-colors duration-200"
                                            title="Asignar profesor">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </button>
                                    <form action="{{ route('materia-semestre.remover-materia', [$semestre->id, $materia->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 p-1 rounded-md hover:bg-red-50 transition-colors duration-200"
                                                onclick="return confirm('¿Estás seguro de remover esta materia del semestre?')"
                                                title="Remover">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
                                    <div class="flex items-center space-x-1">
                                        @if($materia->profesores->count() > 0)
                                            @foreach($materia->profesores->take(2) as $profesor)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ substr($profesor->nombre, 0, 1) }}{{ substr($profesor->apellido, 0, 1) }}
                                                </span>
                                            @endforeach
                                            @if($materia->profesores->count() > 2)
                                                <span class="text-xs text-gray-500">+{{ $materia->profesores->count() - 2 }}</span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation mr-1"></i>
                                                Sin prof.
                                            </span>
                                        @endif
                                    </div>
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
                        <button onclick="openAsignarMateriaModal()" 
                                class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Asignar Primera Materia
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Materias Disponibles -->
        @if($materiasDisponibles->count() > 0)
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                <h2 class="text-2xl font-bold text-gray-900">Materias Disponibles</h2>
                <p class="text-gray-600 mt-1">Materias que puedes asignar a este semestre</p>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($materiasDisponibles->take(6) as $materia)
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-all duration-200">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $materia->nombre }}</h3>
                                <p class="text-sm text-gray-600">{{ $materia->codigo }}</p>
                            </div>
                        </div>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Créditos</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $materia->creditos }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Estado</span>
                                @if($materia->estado)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Activa
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inactiva
                                    </span>
                                @endif
                            </div>
                        </div>

                        <button onclick="asignarMateriaRapida({{ $materia->id }})" 
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            <i class="fas fa-plus mr-1"></i>
                            Asignar
                        </button>
                    </div>
                    @endforeach
                </div>
                
                @if($materiasDisponibles->count() > 6)
                    <div class="text-center mt-6">
                        <p class="text-gray-500">Y {{ $materiasDisponibles->count() - 6 }} materias más disponibles</p>
                        <button onclick="openAsignarMateriaModal()" 
                                class="inline-flex items-center mt-3 px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            <i class="fas fa-list mr-1"></i>
                            Ver Todas
                        </button>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Asignar Materia -->
<div id="asignarMateriaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Asignar Materia al Semestre</h3>
            <form action="{{ route('materia-semestre.asignar-materia', $semestre->id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="materia_id" class="block text-sm font-medium text-gray-700 mb-2">Materia</label>
                        <select id="materia_id" name="materia_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Seleccionar materia</option>
                            @foreach($materiasDisponibles as $materia)
                                <option value="{{ $materia->id }}">{{ $materia->nombre }} ({{ $materia->codigo }}) - {{ $materia->creditos }} créditos</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="profesor_id" class="block text-sm font-medium text-gray-700 mb-2">Profesor (Opcional)</label>
                        <select id="profesor_id" name="profesor_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Sin profesor</option>
                            @foreach($profesores as $profesor)
                                <option value="{{ $profesor->id }}">{{ $profesor->nombre }} {{ $profesor->apellido }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="creditos" class="block text-sm font-medium text-gray-700 mb-2">Créditos</label>
                        <input type="number" id="creditos" name="creditos" min="1" max="10" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeAsignarMateriaModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                        Asignar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openAsignarMateriaModal() {
    document.getElementById('asignarMateriaModal').classList.remove('hidden');
}

function closeAsignarMateriaModal() {
    document.getElementById('asignarMateriaModal').classList.add('hidden');
}

function asignarMateriaRapida(materiaId) {
    if (confirm('¿Deseas asignar esta materia al semestre?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("materia-semestre.asignar-materia", $semestre->id) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const materiaInput = document.createElement('input');
        materiaInput.type = 'hidden';
        materiaInput.name = 'materia_id';
        materiaInput.value = materiaId;
        
        form.appendChild(csrfToken);
        form.appendChild(materiaInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function validarCargaAcademica() {
    fetch('{{ route("materia-semestre.validar-carga", $semestre->id) }}')
        .then(response => response.json())
        .then(data => {
            let mensaje = '';
            if (data.errores.length > 0) {
                mensaje += 'Errores encontrados:\n' + data.errores.join('\n') + '\n\n';
            }
            if (data.advertencias.length > 0) {
                mensaje += 'Advertencias:\n' + data.advertencias.join('\n');
            }
            if (data.valido && data.errores.length === 0) {
                mensaje = '✅ La carga académica es válida.';
            }
            alert(mensaje || 'No se encontraron problemas.');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al validar la carga académica.');
        });
}

// Cerrar modales al hacer clic fuera de ellos
document.getElementById('asignarMateriaModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAsignarMateriaModal();
    }
});
</script>
@endpush
@endsection 