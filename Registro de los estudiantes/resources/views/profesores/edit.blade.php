@extends('layouts.app.layout')
@section('content')
<div class="container">
    <h1>Editar Profesor</h1>
    <form action="{{ route('profesores.update', $profesor->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nombre de usuario</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $profesor->user->name) }}" required>
            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $profesor->user->email) }}" required>
            @error('email')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $profesor->nombre) }}" required>
            @error('nombre')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control" value="{{ old('apellido', $profesor->apellido) }}" required>
            @error('apellido')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">Actualizar Profesor</button>
        <a href="{{ route('profesores.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection 