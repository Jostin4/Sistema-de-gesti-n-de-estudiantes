@extends('layouts.app.layout')

@section('title', 'Reinscripción')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full mb-6">
                <i class="fas fa-sync-alt text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Reinscripción Académica</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Selecciona las materias que deseas cursar en el próximo semestre
            </p>
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

        @if(session('info'))
            <div class="mb-8 bg-blue-50 border border-blue-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-blue-800">Información</h3>
                        <p class="text-blue-700">{{ session('info') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Información del estudiante -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-user mr-3 text-blue-600"></i>
                    Información Académica
                </h2>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-graduation-cap text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $estudiante->carreras->first()->nombre ?? 'Sin carrera' }}</h3>
                        <p class="text-sm text-gray-500">Carrera</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-book text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $estudiante->getCreditosAprobados() }}</h3>
                        <p class="text-sm text-gray-500">Créditos Aprobados</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-check-circle text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $estudiante->getMateriasAprobadas()->count() }}</h3>
                        <p class="text-sm text-gray-500">Materias Aprobadas</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $estudiante->getMateriasReprobadas()->count() }}</h3>
                        <p class="text-sm text-gray-500">Materias Pendientes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Momentos Académicos Activos -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-2xl font-bold text-gray-900">Momentos Académicos Disponibles</h2>
                <p class="text-gray-600 mt-1">Selecciona el semestre para ver las materias disponibles</p>
            </div>
            
            <div class="p-8">
                @if($semestresActivos->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-clock text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay momentos académicos activos</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            Actualmente no hay períodos de reinscripción abiertos. Contacta a la administración para más información.
                        </p>
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <i class="fas fa-home mr-2"></i>
                            Volver al Dashboard
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($semestresActivos as $semestre)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-calendar-alt text-white text-lg"></i>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $semestre->nombre }}</h3>
                                                <p class="text-sm text-gray-500">{{ $semestre->carrera->nombre ?? 'Sin carrera' }}</p>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-circle mr-1 text-xs"></i>
                                            Activo
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-3 mb-6">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                            <span>Inicio: {{ $semestre->fecha_inicio_inscripcion->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-calendar-check mr-2 text-gray-400"></i>
                                            <span>Fin: {{ $semestre->fecha_fin_inscripcion->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-clock mr-2 text-gray-400"></i>
                                            <span>{{ $semestre->fecha_fin_inscripcion->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('reinscripcion.materias', $semestre->id) }}" 
                                       class="w-full bg-gradient-to-r from-purple-500 to-indigo-600 text-white py-2 px-4 rounded-lg hover:from-purple-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center">
                                        <i class="fas fa-arrow-right mr-2"></i>
                                        Ver Materias Disponibles
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Información adicional -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-3 text-purple-600"></i>
                    Información sobre Reinscripción
                </h3>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Requisitos de Reinscripción</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Aprobar al menos 70% de las materias del semestre anterior
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Tener créditos académicos suficientes
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Estar dentro del período de inscripción
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                No tener materias con estado de retirado
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Proceso de Selección</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-list text-blue-500 mr-2"></i>
                                Revisar materias disponibles por semestre
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-clock text-blue-500 mr-2"></i>
                                Seleccionar horarios que se ajusten a tu disponibilidad
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-users text-blue-500 mr-2"></i>
                                Verificar cupos disponibles en cada sección
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-blue-500 mr-2"></i>
                                Confirmar inscripción antes del cierre del período
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 