@extends('layouts.app.layout')
@section('content')
<div class="container">
    <h1>Mis Semestres</h1>
    @if($semestres->isEmpty())
        <div class="alert alert-info">No tienes semestres inscritos.</div>
    @else
        <ul class="list-group">
            @foreach($semestres as $semestre)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $semestre->nombre }}
                    <a href="{{ route('estudiante.semestre', $semestre->id) }}" class="btn btn-primary btn-sm">Ver Materias</a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection 