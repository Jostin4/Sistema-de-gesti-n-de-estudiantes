<div class="bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Información del sistema -->
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-white text-sm"></i>
                    </div>
                    <span class="text-lg font-bold text-gray-900">Sistema Académico</span>
                </div>
                <p class="text-gray-600 text-sm">
                    Plataforma integral para la gestión académica universitaria.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <i class="fab fa-twitter text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <i class="fab fa-facebook text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <i class="fab fa-linkedin text-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Enlaces rápidos -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase">Enlaces Rápidos</h3>
                <div class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="block text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">
                        Dashboard
                    </a>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('carreras.index') }}" class="block text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">
                            Carreras
                        </a>
                        <a href="{{ route('estudiantes.index') }}" class="block text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">
                            Estudiantes
                        </a>
                        <a href="{{ route('materias.index') }}" class="block text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">
                            Materias
                        </a>
                    @endif
                </div>
            </div>

            <!-- Información de contacto -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase">Contacto</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-envelope text-gray-400"></i>
                        <span>soporte@sistemaacademico.com</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-phone text-gray-400"></i>
                        <span>+1 (555) 123-4567</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                        <span>Universidad, Ciudad, País</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Línea divisoria -->
        <div class="mt-8 pt-8 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <p class="text-sm text-gray-600">
                    &copy; {{ date('Y') }} Sistema Académico. Todos los derechos reservados.
                </p>
                <div class="flex space-x-6 text-sm">
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors duration-200">Política de Privacidad</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors duration-200">Términos de Servicio</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors duration-200">Ayuda</a>
                </div>
            </div>
        </div>
    </div>
</div>
