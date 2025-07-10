@extends('layouts.app.layout')
@section('content')
<div class="container">
    <h1>Evaluaciones y Notas - {{ $materia->nombre }} ({{ $semestre->nombre }})</h1>
    <div class="mb-3 d-flex align-items-center">
        <label for="estadoNota" class="me-2">Filtrar por estado de nota:</label>
        <select id="estadoNota" class="form-select w-auto me-3">
            <option value="todos">Todos</option>
            <option value="pendiente">Pendiente</option>
            <option value="aprobada">Aprobada</option>
            <option value="reprobada">Reprobada</option>
        </select>
        <label for="minAprobacion" class="me-2">Mínimo para aprobar:</label>
        <input type="number" id="minAprobacion" class="form-control w-auto" value="60" min="0" max="100">
    </div>
    <table class="table table-bordered" id="tablaEvaluaciones">
        <thead>
            <tr>
                <th>Evaluación</th>
                <th>Porcentaje</th>
                <th>Nota</th>
            </tr>
        </thead>
        <tbody>
            @foreach($evaluaciones as $evaluacion)
                <tr data-estado="
                    @if(!isset($notas[$evaluacion->id]))pendiente
                    @elseif($notas[$evaluacion->id]->nota >= 60)aprobada
                    @else reprobada
                    @endif
                " data-nota="{{ isset($notas[$evaluacion->id]) ? $notas[$evaluacion->id]->nota : '' }}">
                    <td>{{ $evaluacion->nombre }}</td>
                    <td>{{ $evaluacion->porcentaje }}%</td>
                    <td>
                        @if(isset($notas[$evaluacion->id]))
                            {{ $notas[$evaluacion->id]->nota }}
                        @else
                            <span class="text-warning">Pendiente</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        function filtrarEvaluaciones() {
            let estado = document.getElementById('estadoNota').value;
            let minAprob = parseFloat(document.getElementById('minAprobacion').value);
            let filas = document.querySelectorAll('#tablaEvaluaciones tbody tr');
            filas.forEach(function(fila) {
                let estadoFila = fila.getAttribute('data-estado').trim();
                let nota = parseFloat(fila.getAttribute('data-nota'));
                let mostrar = true;
                if (estado !== 'todos') {
                    if (estado === 'pendiente' && estadoFila !== 'pendiente') mostrar = false;
                    if (estado === 'aprobada' && (isNaN(nota) || nota < minAprob)) mostrar = false;
                    if (estado === 'reprobada' && (!isNaN(nota) && nota >= minAprob || estadoFila === 'pendiente')) mostrar = false;
                }
                fila.style.display = mostrar ? '' : 'none';
            });
        }
        document.getElementById('estadoNota').addEventListener('change', filtrarEvaluaciones);
        document.getElementById('minAprobacion').addEventListener('input', filtrarEvaluaciones);
    </script>
    <a href="{{ route('estudiante.semestre', $semestre->id) }}" class="btn btn-secondary">Volver a materias</a>
</div>
@endsection 