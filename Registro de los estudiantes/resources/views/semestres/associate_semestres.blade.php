{{-- resources/views/carreras/associate_semestres.blade.php --}}
@extends('layouts.app.layout') {{-- Extiende el layout principal del dashboard --}}

@section('title', 'Asociar Semestres') {{-- Título específico para esta página --}}

@section('navbar')
    @include('components.navbar') {{-- Incluye el componente de la barra de navegación --}}
@endsection

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-gray-800">Gestionar Semestres para: {{ $carrera->nombre }}</h1>
            <a href="{{ route('carreras.show', $carrera->id) }}" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-md font-semibold hover:bg-gray-300 transition-colors duration-200">
                Volver a Detalles de Carrera
            </a>
        </div>

        <form action="{{ route('semestres.unifiedAction', $carrera->id) }}" method="POST" class="space-y-6">
            @csrf {{-- Directiva CSRF de Laravel para seguridad --}}

            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">Seleccionar Semestres</label>
                <div class="border border-gray-300 rounded-md p-4 bg-gray-50 max-h-80 overflow-y-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($semestres as $semestre)
                        <label for="semestre_{{ $semestre->id }}" class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer transition-all duration-200
                            {{ in_array($semestre->id, $carreraSemestres) ? 'bg-indigo-100 border-indigo-500 shadow-md' : 'bg-white hover:bg-gray-100 hover:border-indigo-300' }}">
                            <input type="checkbox"
                                   id="semestre_{{ $semestre->id }}"
                                   name="semestres[]"
                                   value="{{ $semestre->id }}"
                                   class="form-checkbox h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500 mr-3"
                                   {{ in_array($semestre->id, $carreraSemestres) ? 'checked' : '' }}>
                            <span class="text-gray-800 font-medium">{{ $semestre->nombre }}</span>
                        </label>
                    @empty
                        <p class="text-gray-600 col-span-full text-center">No hay semestres disponibles para asociar.</p>
                    @endforelse
                </div>
                <p class="text-gray-500 text-xs mt-2">Marca las casillas para asociar o desasociar semestres. Haz clic en "Asociar" para guardar los cambios o "Eliminar" para borrar los semestres seleccionados de forma permanente.</p>
                @error('semestres')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between items-center gap-4 mt-6">
                <!-- Botón de Seleccionar/Deseleccionar Todos -->
                <button type="button" id="toggle-all-semestres"
                        class="bg-gray-600 text-white py-2 px-4 rounded-md font-semibold text-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Seleccionar Todos
                </button>

                <!-- Botones de Acción -->
                <div class="flex gap-2">
                    <a href="{{ route('semestres.create') }}"
                        class="bg-blue-600 text-white py-2 px-6 rounded-md font-semibold text-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Añadir Semestre
                    </a>
                    <button type="submit" name="action_type" value="associate"
                            class="bg-indigo-600 text-white py-2 px-6 rounded-md font-semibold text-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Asociar/Desasociar
                    </button>
                    <button type="submit" name="action_type" value="delete"
                            class="bg-red-600 text-white py-2 px-6 rounded-md font-semibold text-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                            onclick="return confirm('¿Estás seguro de que quieres eliminar los semestres seleccionados de forma permanente? Esta acción no se puede deshacer.')">
                        Eliminar Semestres
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    @parent {{-- Mantiene los scripts del layout padre si los hubiera --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleAllButton = document.getElementById('toggle-all-semestres');
            const checkboxes = document.querySelectorAll('input[name="semestres[]"]');
            const semesterContainer = document.querySelector('.max-h-80.overflow-y-auto'); // Contenedor de los semestres

            if (toggleAllButton) {
                toggleAllButton.addEventListener('click', function() {
                    let allChecked = true;
                    checkboxes.forEach(checkbox => {
                        if (!checkbox.checked) {
                            allChecked = false;
                        }
                    });

                    checkboxes.forEach(checkbox => {
                        checkbox.checked = !allChecked;
                        // Actualiza las clases de la etiqueta padre para el estilo visual
                        const label = checkbox.closest('label');
                        if (label) {
                            if (checkbox.checked) {
                                label.classList.add('bg-indigo-100', 'border-indigo-500', 'shadow-md');
                                label.classList.remove('bg-white', 'hover:bg-gray-100', 'hover:border-indigo-300');
                            } else {
                                label.classList.remove('bg-indigo-100', 'border-indigo-500', 'shadow-md');
                                label.classList.add('bg-white', 'hover:bg-gray-100', 'hover:border-indigo-300');
                            }
                        }
                    });

                    // Actualiza el texto del botón
                    toggleAllButton.textContent = allChecked ? 'Seleccionar Todos' : 'Deseleccionar Todos';
                });
            }

            // Añadir un listener a cada checkbox para actualizar el estilo al marcar/desmarcar
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const label = this.closest('label');
                    if (label) {
                        if (this.checked) {
                            label.classList.add('bg-indigo-100', 'border-indigo-500', 'shadow-md');
                            label.classList.remove('bg-white', 'hover:bg-gray-100', 'hover:border-indigo-300');
                        } else {
                            label.classList.remove('bg-indigo-100', 'border-indigo-500', 'shadow-md');
                            label.classList.add('bg-white', 'hover:bg-gray-100', 'hover:border-indigo-300');
                        }
                    }
                });
            });

            // Lógica para inicializar el estado del botón "Seleccionar/Deseleccionar Todos"
            // Basado en si todos los checkboxes están inicialmente marcados.
            if (toggleAllButton && checkboxes.length > 0) {
                let allCheckedInitially = true;
                checkboxes.forEach(checkbox => {
                    if (!checkbox.checked) {
                        allCheckedInitially = false;
                    }
                });
                toggleAllButton.textContent = allCheckedInitially ? 'Deseleccionar Todos' : 'Seleccionar Todos';
            }
        });
    </script>
@endsection

@section('footer')
    @include('components.footer') {{-- Incluye el componente del pie de página --}}
@endsection
