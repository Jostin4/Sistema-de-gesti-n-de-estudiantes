<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>@yield('title', 'Dashboard') - Sistema Académico</title>
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body class="h-full bg-gray-50 font-sans antialiased">
    <div class="min-h-full">
        <!-- Sidebar para desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
            <x-sidebar />
        </div>

        <!-- Sidebar móvil -->
        <div class="lg:hidden" id="mobile-sidebar">
            <x-sidebar-mobile />
        </div>

        <!-- Contenido principal -->
        <div class="lg:pl-64">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <x-navbar />
            </header>

            <!-- Main Content -->
            <main class="flex-1">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200">
                <x-footer />
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
