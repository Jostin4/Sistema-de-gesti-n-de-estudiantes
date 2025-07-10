@extends('layouts.app.layout')

@section('title', 'Registrar Nueva Materia')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-book-open mr-3 text-indigo-500"></i>
                    Registrar Nueva Materia
                </h1>
                <p class="text-gray-600 mt-2">Completa el formulario para agregar una materia al sistema académico.</p>
            </div>
            <a href="{{ route('materias.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </a>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <form action="{{ route('materias.store') }}" method="POST" class="p-8 space-y-8">
                @csrf
                <!-- Nombre de la materia -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-book mr-2 text-indigo-500"></i>
                        Nombre de la Materia
                        <span class="ml-1 text-red-500">*</span>
                        <span class="ml-2 text-xs text-gray-400" title="Ejemplo: Matemáticas I">¿?</span>
                    </label>
                    <input type="text"
                           name="nombre"
                           id="nombre"
                           value="{{ old('nombre') }}"
                           required
                           maxlength="100"
                           placeholder="Ej: Matemáticas I"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Semestre -->
                <div>
                    <label for="semestre_id" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>
                        Semestre
                        <span class="ml-1 text-red-500">*</span>
                    </label>
                    <select name="semestre_id"
                            id="semestre_id"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 @error('semestre_id') border-red-500 @enderror">
                        <option value="">Seleccionar semestre</option>
                        @foreach($carreras as $carrera)
                            <optgroup label="{{ $carrera->nombre }}">
                                @foreach($carrera->semestres as $semestre)
                                    <option value="{{ $semestre->id }}" {{ old('semestre_id') == $semestre->id ? 'selected' : '' }}>
                                        {{ $semestre->nombre }} - {{ $carrera->nombre }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('semestre_id')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Espacio para futuros campos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-barcode mr-2 text-green-500"></i>
                            Código de Materia
                        </label>
                        <input type="text" name="codigo" id="codigo" maxlength="10" placeholder="Ej: MAT101"
                               value="{{ old('codigo') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('codigo') border-red-500 @enderror">
                        @error('codigo')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label for="creditos" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-coins mr-2 text-yellow-500"></i>
                            Créditos
                        </label>
                        <input type="number" name="creditos" id="creditos" min="1" max="10" placeholder="Ej: 5"
                               value="{{ old('creditos') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors duration-200 @error('creditos') border-red-500 @enderror">
                        @error('creditos')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('materias.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-semibold text-white bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Registrar Materia
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400 text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-blue-800 mb-2">Información importante</h3>
                <ul class="list-disc pl-5 space-y-1 text-blue-700 text-sm">
                    <li>La materia se creará y asignará automáticamente al semestre seleccionado.</li>
                    <li>Una vez creada, podrás asignar profesores a la materia.</li>
                    <li>Podrás gestionar evaluaciones y notas una vez creada.</li>
                    <li>El código de materia debe ser único (opcional).</li>
                    <li>Los créditos ayudan a calcular la carga académica del estudiante.</li>
                    <li>Si necesitas la materia en otro semestre, puedes duplicarla desde la lista.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Restaurar el semestre seleccionado si hay error de validación
document.addEventListener('DOMContentLoaded', function() {
    const semestreId = '{{ old("semestre_id") }}';
    if (semestreId) {
        document.getElementById('semestre_id').value = semestreId;
    }
});
</script>
@endpush
@endsection