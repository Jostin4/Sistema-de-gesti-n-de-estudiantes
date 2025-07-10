@extends('layouts.app.layout')

@section('title', 'Carrera: ' . $carrera->nombre)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb mejorado -->
        <nav class="mb-8" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('materia-semestre.index') }}" 
                       class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200">
                        <i class="fas fa-home mr-1"></i>
                        Inicio
                    </a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-900 font-semibold">{{ $carrera->nombre }}</span>
                </li>
            </ol>
        </nav>

        <!-- Header principal de la carrera -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8">
            <div class="px-8 py-6 bg-gradient-to-r from-blue-500 to-indigo-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">{{ $carrera->nombre }}</h1>
                            <p class="text-blue-100 text-lg">Gestión de materias y semestres</p>
                        </div>
                    </div>
                    <div class="text-right text-white">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <div class="text-2xl font-bold">{{ $carrera->semestres->count() }}</div>
                                <div class="text-blue-100 text-sm">Semestres</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold">{{ $carrera->materias->count() }}</div>
                                <div class="text-blue-100 text-sm">Materias</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Panel de semestres -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Semestres de {{ $carrera->nombre }}</h2>
                                <p class="text-gray-600 mt-1">Gestiona las materias por semestre</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('semestres.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>
                                    Nuevo Semestre
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        @if($carrera->semestres->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($carrera->semestres as $semestre)
                                    @php
                                        $materiasAsignadas = \App\Models\MateriaEstudianteSemestre::where('semestre_id', $semestre->id)
                                            ->distinct()
                                            ->count('materia_id');
                                    @endphp
                                    
                                    <div class="semestre-card bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border-2 border-green-200 hover:border-green-300 transition-all duration-300 cursor-pointer transform hover:-translate-y-1 hover:shadow-lg">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-calendar-alt text-green-600 text-lg"></i>
                                                </div>
                                                <div>
                                                    <h3 class="text-lg font-bold text-green-900">{{ $semestre->nombre }}</h3>
                                                    <p class="text-sm text-green-700">Semestre</p>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $semestre->estado }}
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-3 mb-6">
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-green-700">Materias asignadas:</span>
                                                <span class="font-bold text-green-900">{{ $materiasAsignadas }}</span>
                                            </div>
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-green-700">Total materias:</span>
                                                <span class="font-medium text-green-900">{{ $carrera->materias->count() }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex space-x-3">
                                            <a href="{{ route('materia-semestre.semestre', [$carrera->id, $semestre->id]) }}" 
                                               class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-lg text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                                <i class="fas fa-cog mr-2"></i>
                                                Gestionar Materias
                                            </a>
                                            <a href="{{ route('semestres.edit', $semestre->id) }}" 
                                               class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-16">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-calendar-alt text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay semestres asignados</h3>
                                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                                    Esta carrera no tiene semestres asignados. Primero debes crear semestres para esta carrera.
                                </p>
                                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                    <a href="{{ route('semestres.create') }}" 
                                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                        <i class="fas fa-plus mr-2"></i>
                                        Crear Primer Semestre
                                    </a>
                                    <a href="{{ route('semestres.index') }}" 
                                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                        <i class="fas fa-list mr-2"></i>
                                        Gestionar Semestres
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Panel lateral -->
            <div class="lg:col-span-1">
                <div class="space-y-8">
                    <!-- Información de la carrera -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                            <h3 class="text-lg font-medium text-gray-900">Información de la Carrera</h3>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">ID:</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $carrera->id }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Nombre:</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $carrera->nombre }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Semestres:</span>
                                <span class="text-sm font-semibold text-blue-600">{{ $carrera->semestres->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Materias:</span>
                                <span class="text-sm font-semibold text-green-600">{{ $carrera->materias->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones rápidas -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                            <h3 class="text-lg font-medium text-gray-900">Acciones Rápidas</h3>
                        </div>
                        
                        <div class="p-6 space-y-3">
                            <a href="{{ route('carreras.edit', $carrera->id) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-yellow-300 text-sm font-medium rounded-lg text-yellow-700 bg-yellow-50 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                                <i class="fas fa-edit mr-2"></i>
                                Editar Carrera
                            </a>
                            <a href="{{ route('materias.create') }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-lg text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Crear Materia
                            </a>
                            <a href="{{ route('materia-semestre.index') }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Volver al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materias de la carrera -->
        <div class="mt-12 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Materias de {{ $carrera->nombre }}</h3>
                        <p class="text-gray-600 mt-1">Todas las materias disponibles para esta carrera</p>
                    </div>
                    <a href="{{ route('materias.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Nueva Materia
                    </a>
                </div>
            </div>
            
            <div class="p-8">
                @if($carrera->materias->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($carrera->materias as $materia)
                            <div class="materia-card bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-purple-300 transition-all duration-300 cursor-pointer transform hover:-translate-y-1 hover:shadow-lg">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-book text-purple-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $materia->nombre }}</h4>
                                            <p class="text-sm text-gray-500">Materia</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $materia->estado === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $materia->estado }}
                                    </span>
                                </div>
                                
                                <div class="space-y-3 mb-4">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">Evaluaciones:</span>
                                        <span class="font-medium text-gray-900">{{ $materia->evaluaciones->count() }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">Semestres:</span>
                                        <span class="font-medium text-gray-900">{{ $materia->semestres->count() }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('materias.show', $materia->id) }}" 
                                       class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-purple-300 text-sm font-medium rounded-lg text-purple-700 bg-purple-50 hover:bg-purple-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        Ver
                                    </a>
                                    <a href="{{ route('materias.edit', $materia->id) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-book text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay materias asignadas</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            Esta carrera no tiene materias asignadas. Primero debes crear materias para esta carrera.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('materias.create') }}" 
                               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Crear Primera Materia
                            </a>
                            <a href="{{ route('materias.index') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                <i class="fas fa-list mr-2"></i>
                                Gestionar Materias
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const semestreCards = document.querySelectorAll('.semestre-card');
    const materiaCards = document.querySelectorAll('.materia-card');

    // Animaciones al hacer hover en las tarjetas
    semestreCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    materiaCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>

<style>
.semestre-card, .materia-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.semestre-card:hover, .materia-card:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
@endpush
@endsection 