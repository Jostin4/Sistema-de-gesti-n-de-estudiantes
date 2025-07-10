{{-- Este archivo (ej: resources/views/auth/login.blade.php) extiende el layout base de autenticación --}}
@extends('layouts.auth.layout') {{-- Asegúrate de que 'layouts.auth' apunte al archivo del layout base --}}

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-blue-50 to-blue-100 py-8 px-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <!-- Logo -->
        <div class="flex flex-col items-center mb-8">
            {{-- Si tienes un logo, reemplaza el texto por <img src="/ruta/logo.png" ...> --}}
            <span class="text-3xl font-bold text-blue-700 tracking-tight mb-2">Sistema de Registro</span>
            <span class="text-sm text-blue-400 font-medium">Acceso al sistema</span>
        </div>
        @if(session('error'))
            <div class="mb-4 text-red-600 text-sm text-center">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-blue-700">Correo electrónico</label>
                <input id="email" name="email" type="email" required autofocus autocomplete="email"
                    class="mt-1 p-2 block w-full rounded-md border border-blue-200 focus:border-blue-500 focus:ring focus:ring-blue-100 bg-blue-50 text-blue-900 placeholder-blue-300 shadow-sm transition" placeholder="usuario@correo.com" value="{{ old('email') }}">
                @error('email')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-blue-700">Contraseña</label>
                <div class="relative">
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                        class="mt-1 block p-2 w-full rounded-md border border-blue-200 focus:border-blue-500 focus:ring focus:ring-blue-100 bg-blue-50 text-blue-900 placeholder-blue-300 shadow-sm transition pr-10" placeholder="********">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <input id="show-password" type="checkbox" class="form-checkbox text-blue-600" onclick="document.getElementById('password').type = this.checked ? 'text' : 'password'">
                        <label for="show-password" class="ml-2 text-xs text-blue-500 cursor-pointer select-none">Ver</label>
                    </div>
                </div>
                @error('password')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <a href="#" class="text-xs text-blue-500 hover:underline">¿Olvidaste tu contraseña?</a>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow transition">Iniciar sesión</button>
        </form>
        <div class="mt-6 text-center">
            <span class="text-sm text-blue-500">¿No tienes cuenta?</span>
            <a href="{{ route('registerView') }}" class="text-blue-700 font-semibold hover:underline ml-1">Regístrate</a>
        </div>
    </div>
</div>
@endsection
