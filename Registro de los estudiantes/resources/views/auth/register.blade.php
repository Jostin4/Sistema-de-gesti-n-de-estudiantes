@extends('layouts.auth.layout')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-blue-50 to-blue-100 py-8 px-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <!-- Logo -->
        <div class="flex flex-col items-center mb-8">
            {{-- Si tienes un logo, reemplaza el texto por <img src="/ruta/logo.png" ...> --}}
            <span class="text-3xl font-bold text-blue-700 tracking-tight mb-2">Sistema de Registro</span>
            <span class="text-sm text-blue-400 font-medium">Crea tu cuenta</span>
        </div>
        @if($errors->any())
            <div class="mb-4 text-red-600 text-sm text-center">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-blue-700">Nombre completo</label>
                <input id="name" name="name" type="text" required autofocus autocomplete="name"
                    class="mt-1 block w-full rounded-md border border-blue-200 focus:border-blue-500 focus:ring focus:ring-blue-100 bg-blue-50 text-blue-900 placeholder-blue-300 shadow-sm transition" placeholder="Nombre y Apellido" value="{{ old('name') }}">
                @error('name')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-blue-700">Correo electrónico</label>
                <input id="email" name="email" type="email" required autocomplete="email"
                    class="mt-1 block w-full rounded-md border border-blue-200 focus:border-blue-500 focus:ring focus:ring-blue-100 bg-blue-50 text-blue-900 placeholder-blue-300 shadow-sm transition" placeholder="usuario@correo.com" value="{{ old('email') }}">
                @error('email')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-blue-700">Contraseña</label>
                <div class="relative">
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                        class="mt-1 block w-full rounded-md border border-blue-200 focus:border-blue-500 focus:ring focus:ring-blue-100 bg-blue-50 text-blue-900 placeholder-blue-300 shadow-sm transition pr-10" placeholder="********">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <input id="show-password" type="checkbox" class="form-checkbox text-blue-600" onclick="document.getElementById('password').type = this.checked ? 'text' : 'password'">
                        <label for="show-password" class="ml-2 text-xs text-blue-500 cursor-pointer select-none">Ver</label>
                    </div>
                </div>
                @error('password')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-blue-700">Confirmar contraseña</label>
                <div class="relative">
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                        class="mt-1 block w-full rounded-md border border-blue-200 focus:border-blue-500 focus:ring focus:ring-blue-100 bg-blue-50 text-blue-900 placeholder-blue-300 shadow-sm transition pr-10" placeholder="********">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <input id="show-password-confirm" type="checkbox" class="form-checkbox text-blue-600" onclick="document.getElementById('password_confirmation').type = this.checked ? 'text' : 'password'">
                        <label for="show-password-confirm" class="ml-2 text-xs text-blue-500 cursor-pointer select-none">Ver</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow transition">Registrarse</button>
        </form>
        <div class="mt-6 text-center">
            <span class="text-sm text-blue-500">¿Ya tienes cuenta?</span>
            <a href="{{ route('loginView') }}" class="text-blue-700 font-semibold hover:underline ml-1">Inicia sesión</a>
        </div>
    </div>
</div>
@endsection
