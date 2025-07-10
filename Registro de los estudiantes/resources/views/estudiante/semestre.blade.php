@extends('layouts.app.layout')
@section('content')
<div class="container">
    <h1>Materias de {{ $semestre->nombre }}</h1>
    <div class="mb-3">
        <input type="text" id="busquedaMateria" class="form-control" placeholder="Buscar materia...">
    </div>
    <ul class="list-group" id="listaMaterias">
        @foreach($materias as $materia)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $materia->nombre }}
                <a href="{{ route('estudiante.materia', [$semestre->id, $materia->id]) }}" class="btn btn-primary btn-sm">Ver Evaluaciones y Notas</a>
            </li>
        @endforeach
    </ul>
    <script>
        document.getElementById('busquedaMateria').addEventListener('input', function() {
            let filtro = this.value.toLowerCase();
            let items = document.querySelectorAll('#listaMaterias li');
            items.forEach(function(item) {
                let texto = item.textContent.toLowerCase();
                item.style.display = texto.includes(filtro) ? '' : 'none';
            });
        });
    </script>
    <a href="{{ route('estudiante.dashboard') }}" class="btn btn-secondary mt-3">Volver a mis semestres</a>
</div>
@endsection 