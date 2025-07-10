<div class="flex flex-col flex-grow bg-white border-r border-gray-200">
    <!-- Logo -->
    <div class="flex items-center h-16 flex-shrink-0 px-4 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                <i class="fas fa-graduation-cap text-indigo-600 text-sm"></i>
            </div>
            <span class="text-white font-bold text-lg">Sistema Académico</span>
        </div>
    </div>

    <!-- Navegación -->
    <div class="flex-1 flex flex-col overflow-y-auto">
        <nav class="flex-1 px-2 py-4 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="fas fa-home mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('dashboard') ? 'text-indigo-500' : '' }}"></i>
                Dashboard
            </a>

            @if(auth()->check() && auth()->user()->role === 'admin')
                <!-- Sección de Administración -->
                <div class="pt-4">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Administración
                    </h3>
                    
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('carreras.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('carreras.*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-university mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('carreras.*') ? 'text-indigo-500' : '' }}"></i>
                            Carreras
                        </a>
                        
                        <a href="{{ route('estudiantes.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('estudiantes.*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-user-graduate mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('estudiantes.*') ? 'text-indigo-500' : '' }}"></i>
                            Estudiantes
                        </a>
                        
                        <a href="{{ route('profesores.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('profesores.*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-chalkboard-teacher mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('profesores.*') ? 'text-indigo-500' : '' }}"></i>
                            Profesores
                        </a>
                    </div>
                </div>

                <!-- Sección Académica -->
                <div class="pt-4">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Académico
                    </h3>
                    
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('materias.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('materias.*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-book mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('materias.*') ? 'text-indigo-500' : '' }}"></i>
                            Materias
                        </a>
                        
                        <a href="{{ route('semestres.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('semestres.*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-calendar-alt mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('semestres.*') ? 'text-indigo-500' : '' }}"></i>
                            Semestres
                        </a>
                    </div>
                </div>

                <!-- Sección de Evaluaciones -->
                <div class="pt-4">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Evaluaciones
                    </h3>
                    
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('evaluaciones.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('evaluaciones.*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-tasks mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('evaluaciones.*') ? 'text-indigo-500' : '' }}"></i>
                            Gestionar Evaluaciones
                        </a>
                        
                        <a href="{{ route('notas.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('notas.*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-chart-bar mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('notas.*') ? 'text-indigo-500' : '' }}"></i>
                            Notas y Reportes
                        </a>
                    </div>
                </div>
            @endif

            @if(auth()->check() && auth()->user()->role === 'profesor')
                <!-- Sección de Profesor -->
                <div class="pt-4">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Mis Cursos
                    </h3>
                    
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('evaluaciones.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('evaluaciones.*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-tasks mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('evaluaciones.*') ? 'text-indigo-500' : '' }}"></i>
                            Mis Materias
                        </a>
                        
                        <a href="{{ route('evaluaciones.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('evaluaciones.*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-edit mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('evaluaciones.*') ? 'text-indigo-500' : '' }}"></i>
                            Cargar Notas
                        </a>
                    </div>
                </div>
            @endif

            @if(auth()->check() && auth()->user()->role === 'estudiante')
                <!-- Sección de Estudiante -->
                <div class="pt-4">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Mi Carrera
                    </h3>
                    
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('estudiante.dashboard') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('estudiante.*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-graduation-cap mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('estudiante.*') ? 'text-indigo-500' : '' }}"></i>
                            Mis Cursos
                        </a>
                        
                        <a href="{{ route('estudiante.notas') }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('estudiante.notas') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-chart-line mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('estudiante.notas') ? 'text-indigo-500' : '' }}"></i>
                            Mis Notas
                        </a>
                    </div>
                </div>
            @endif
        </nav>
    </div>

    <!-- Información del usuario -->
    <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-gray-600 text-sm"></i>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Usuario' }}</p>
                <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role ?? 'Usuario' }}</p>
            </div>
        </div>
    </div>
</div> 