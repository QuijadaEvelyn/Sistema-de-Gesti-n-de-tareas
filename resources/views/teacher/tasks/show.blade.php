@extends('layouts.app')

@section('title', 'Detalle de Tarea')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <h2>{{ $task->title }}</h2>
    @if(!$task->is_cancelled)
        <form action="{{ route('teacher.tasks.cancel', $task) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas cancelar esta tarea?')">Cancelar Tarea</button>
        </form>
    @else
        <span class="badge badge-danger">TAREA CANCELADA</span>
    @endif
</div>

<div class="card">
    <p><strong>Tipo:</strong> {{ str_replace('_', ' ', strtoupper($task->type)) }}</p>
    <p><strong>Fecha Límite:</strong> {{ $task->due_date->format('d/m/Y 23:59') }}</p>
    <p style="margin-top: 1rem;">{{ $task->description }}</p>
</div>

<h3>Evaluaciones y Entregas</h3>
@if(!$isOverdue)
    <div class="card" style="border-left: 5px solid var(--blue-bell);">
        <p><strong>Nota:</strong> Solo se podrá calificar a los alumnos una vez que haya expirado la fecha límite de entrega ({{ $task->due_date->format('d/m/Y 23:59') }}).</p>
    </div>
@endif

<form action="{{ route('teacher.tasks.grades.save', $task) }}" method="POST">
    @csrf
    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Estado Entrega</th>
                        <th>Evidencia PDF</th>
                        <th>Calificación (0 - 10)</th>
                        <th>Estado Nota</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        @php
                            $sub = $submissions->get($student->id);
                        @endphp
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>
                                @if($sub && $sub->file_path)
                                    <span class="badge badge-success">Entregado</span>
                                @else
                                    <span class="badge badge-warning">Pendiente / No entregó</span>
                                @endif
                            </td>
                            <td>
                                @if($sub && $sub->file_path)
                                    <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="btn btn-secondary" style="padding: 0.2rem 0.5rem; font-size: 0.8rem;">Ver PDF</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <input type="number" step="0.1" min="0" max="10" 
                                    name="grades[{{ $student->id }}]" 
                                    value="{{ $sub ? $sub->grade : '' }}" 
                                    class="form-control" 
                                    style="width: 100px;" 
                                    {{ !$isOverdue ? 'disabled' : '' }}>
                            </td>
                            <td>
                                @if($sub && $sub->status === 'publicado')
                                    <span class="badge badge-success">Publicado</span>
                                @elseif($sub && $sub->status === 'borrador')
                                    <span class="badge badge-warning">Borrador</span>
                                @else
                                    <span class="badge badge-info">Sin Evaluar</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($isOverdue && !$task->is_cancelled)
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                <button type="submit" name="action" value="draft" class="btn btn-secondary">Guardar Borrador</button>
                <button type="submit" name="action" value="publish" class="btn btn-primary">Publicar Calificaciones</button>
            </div>
        @endif
    </div>
</form>
@endsection