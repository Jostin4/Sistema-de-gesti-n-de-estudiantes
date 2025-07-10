<nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Botón de menú móvil y logo -->
            <div class="flex items-center">
                <!-- Botón de menú móvil -->
                <button id="mobile-menu-button" class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors duration-200">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center ml-4 lg:ml-0">
                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-white text-sm"></i>
                    </div>
                    <span class="ml-2 text-xl font-bold text-gray-900">Sistema Académico</span>
                </div>
            </div>

            <!-- Información de la página actual -->
            <div class="hidden md:flex items-center">
                <h1 class="text-lg font-semibold text-gray-900">
                    @yield('page-title', 'Dashboard')
                </h1>
            </div>

            <!-- Usuario y acciones -->
            <div class="flex items-center space-x-4">
                <!-- Notificaciones -->
                <button class="p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200 relative">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400"></span>
                </button>

                <!-- Perfil del usuario -->
                <div class="relative group">
                    <button class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600 transition-colors duration-200">
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-600 text-sm"></i>
                        </div>
                        <span class="hidden md:block text-sm font-medium">{{ auth()->user()->name ?? 'Usuario' }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i>Mi Perfil
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog mr-2"></i>Configuración
                        </a>
                        <div class="border-t border-gray-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>