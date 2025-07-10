@extends('layouts.app.layout')

@section('title', 'Mis Notas')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Mis Notas</h1>
                    <p class="text-gray-600 mt-2">Historial completo de tus calificaciones académicas</p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-3">
                    <a href="{{ route('estudiante.dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg font-semibold hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver a Mis Cursos
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Evaluaciones</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $notas->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Aprobadas</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $notas->where('nota', '>=', 7)->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Reprobadas</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $notas->where('nota', '<', 7)->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Promedio</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($notas->avg('nota'), 1) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 sm:mb-0">Filtros</h2>
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                    <select id="semestre-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos los semestres</option>
                        @foreach($notas->pluck('evaluacion.semestre.nombre')->unique() as $semestre)
                            <option value="{{ $semestre }}">{{ $semestre }}</option>
                        @endforeach
                    </select>
                    <select id="materia-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todas las materias</option>
                        @foreach($notas->pluck('evaluacion.materia.nombre')->unique() as $materia)
                            <option value="{{ $materia }}">{{ $materia }}</option>
                        @endforeach
                    </select>
                    <select id="estado-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos los estados</option>
                        <option value="aprobado">Aprobado</option>
                        <option value="reprobado">Reprobado</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Lista de Notas -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-chart-bar mr-3 text-blue-600"></i>
                    Historial de Notas
                </h3>
                <p class="text-gray-600 mt-1">Todas tus calificaciones organizadas por semestre y materia</p>
            </div>
            
            <div class="p-8">
                @if($notas->count() > 0)
                    <div class="space-y-8">
                        @foreach($notasAgrupadas as $grupo => $notasGrupo)
                            @php
                                $semestreMateria = explode(' - ', $grupo);
                                $semestre = $semestreMateria[0];
                                $materia = $semestreMateria[1];
                                $promedioMateria = $notasGrupo->avg('nota');
                            @endphp
                            
                            <div class="nota-grupo bg-gray-50 rounded-xl border border-gray-200 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-book text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">{{ $materia }}</h4>
                                            <p class="text-sm text-gray-600">{{ $semestre }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Promedio</p>
                                        <p class="text-2xl font-bold {{ $promedioMateria >= 50 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($promedioMateria, 1) }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($notasGrupo as $nota)
                                        <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow duration-200">
                                            <div class="flex items-center justify-between mb-2">
                                                <h5 class="font-medium text-gray-900">{{ $nota->evaluacion->nombre }}</h5>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $nota->nota >= 50 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    <i class="fas fa-circle mr-1 text-xs"></i>
                                                    {{ $nota->nota >= 50 ? 'Aprobado' : 'Reprobado' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <div class="text-sm text-gray-500">
                                                    <p>{{ $nota->evaluacion->porcentaje }}% del total</p>
                                                    <p>{{ $nota->created_at->format('d/m/Y') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-3xl font-bold {{ $nota->nota >= 50 ? 'text-green-600' : 'text-red-600' }}">
                                                        {{ number_format($nota->nota, 1) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-clipboard-list text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay notas registradas</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            Aún no tienes notas registradas en el sistema. Las notas aparecerán aquí una vez que los profesores las carguen.
                        </p>
                        <a href="{{ route('estudiante.dashboard') }}" 
                           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver a Mis Cursos
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
    const semestreFilter = document.getElementById('semestre-filter');
    const materiaFilter = document.getElementById('materia-filter');
    const estadoFilter = document.getElementById('estado-filter');
    const notaGrupos = document.querySelectorAll('.nota-grupo');

    function aplicarFiltros() {
        const semestreSeleccionado = semestreFilter.value;
        const materiaSeleccionada = materiaFilter.value;
        const estadoSeleccionado = estadoFilter.value;

        notaGrupos.forEach(grupo => {
            const semestre = grupo.querySelector('p.text-sm.text-gray-600').textContent;
            const materia = grupo.querySelector('h4.text-lg.font-semibold').textContent;
            const promedio = parseFloat(grupo.querySelector('.text-2xl.font-bold').textContent);
            
            let mostrarGrupo = true;
            
            if (semestreSeleccionado && semestre !== semestreSeleccionado) {
                mostrarGrupo = false;
            }
            
            if (materiaSeleccionada && materia !== materiaSeleccionada) {
                mostrarGrupo = false;
            }
            
            if (estadoSeleccionado) {
                if (estadoSeleccionado === 'aprobado' && promedio < 7) {
                    mostrarGrupo = false;
                } else if (estadoSeleccionado === 'reprobado' && promedio >= 7) {
                    mostrarGrupo = false;
                }
            }
            
            grupo.style.display = mostrarGrupo ? 'block' : 'none';
        });
    }

    semestreFilter.addEventListener('change', aplicarFiltros);
    materiaFilter.addEventListener('change', aplicarFiltros);
    estadoFilter.addEventListener('change', aplicarFiltros);
});
</script>

<style>
.nota-grupo {
    transition: all 0.3s ease-in-out;
}

.nota-grupo:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
@endpush
@endsection 