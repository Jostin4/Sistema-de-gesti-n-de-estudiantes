@extends('layouts.app.layout')

@section('title', 'Evaluaciones - ' . $materia->nombre)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('evaluaciones.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Mis Materias
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-sm font-medium text-gray-500">{{ $materia->nombre }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-sm font-medium text-gray-900">{{ $semestre->nombre }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-3xl font-bold text-gray-900 mt-4">Evaluaciones</h1>
                    <p class="text-gray-600 mt-2">Gestiona las evaluaciones de {{ $materia->nombre }} - {{ $semestre->nombre }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $porcentajeTotal }}%</div>
                            <div class="text-sm text-gray-500">Total Asignado</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $evaluaciones->count() }}</div>
                            <div class="text-sm text-gray-500">Evaluaciones</div>
                        </div>
                    </div>
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
                        <h3 class="text-lg font-medium text-green-800">¡Operación exitosa!</h3>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenido principal -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Lista de Evaluaciones</h2>
                        <p class="text-gray-600 mt-1">Gestiona las evaluaciones y sus porcentajes</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($porcentajeTotal >= 100)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-2">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                                    <span class="text-sm font-medium text-yellow-800">100% completado</span>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('evaluaciones.create', [$materia->id, $semestre->id]) }}" 
                               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg">
                                <i class="fas fa-plus mr-2"></i>
                                Crear Evaluación
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                @if($evaluaciones->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-tasks text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay evaluaciones creadas</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            Comienza creando la primera evaluación para esta materia y semestre.
                        </p>
                        @if($porcentajeTotal < 100)
                            <a href="{{ route('evaluaciones.create', [$materia->id, $semestre->id]) }}" 
                               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Crear Primera Evaluación
                            </a>
                        @endif
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($evaluaciones as $evaluacion)
                            <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-clipboard-check text-white text-lg"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $evaluacion->nombre }}</h3>
                                            <p class="text-sm text-gray-500">Evaluación del semestre</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-blue-600">{{ $evaluacion->porcentaje }}%</div>
                                            <div class="text-sm text-gray-500">Ponderación</div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('notas.cargar', $evaluacion->id) }}" 
                                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                                <i class="fas fa-edit mr-2"></i>
                                                Cargar Notas
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Barra de progreso -->
                    <div class="mt-8 bg-gray-50 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Progreso de Evaluaciones</h3>
                            <span class="text-sm font-medium text-gray-500">{{ $porcentajeTotal }}% completado</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-300" 
                                 style="width: {{ $porcentajeTotal }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-2">
                            <span>0%</span>
                            <span>100%</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Información adicional -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-3 text-blue-600"></i>
                    Información sobre Evaluaciones
                </h3>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Porcentajes</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                El total de porcentajes no puede superar 100%
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Se recomienda distribuir equitativamente
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Puedes crear múltiples evaluaciones
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Cargar Notas</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-edit text-blue-500 mr-2"></i>
                                Una vez creada la evaluación, podrás cargar notas
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-users text-blue-500 mr-2"></i>
                                Las notas se asignan por estudiante
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                                Se calculan promedios automáticamente
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 