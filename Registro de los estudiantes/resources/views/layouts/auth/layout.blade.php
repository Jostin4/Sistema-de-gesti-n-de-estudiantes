<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Registro')</title>
    @vite('resources/css/app.css')
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>
    @yield('styles')
</head>
<body>
    {{-- El contenido se renderiza directamente desde las vistas hijas --}}
    @yield('content')

    @yield('scripts')
</body>
</html>
