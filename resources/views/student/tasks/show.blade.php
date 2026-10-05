@extends('layouts.app')

@section('title', 'Detalle de la Tarea')

@section('content')
<h2>{{ $task->title }}</h2>

<div class="card">
    <p><strong>Tipo:</strong> {{ str_replace('_', ' ', strtoupper($task->type)) }}</p>
    <p><strong>Fecha Límite:</strong> {{ $task->due_date->format('d/m/Y 23:59') }}</p>
    <p style="margin-top: 1rem;">{{ $task->description }}</p>
</div>

<div class="card">
    <div class="card-title">Mi Evidencia</div>

    @if($submission && $submission->file_path)
        <p style="margin-bottom: 1rem;">
            <strong>Archivo entregado:</strong> {{ $submission->original_filename }}
            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-secondary" style="margin-left: 1rem; padding: 0.2rem 0.5rem;">Ver Evidencia PDF</a>
        </p>
    @else
        <p style="color: var(--text-muted); margin-bottom: 1rem;">Aún no has subido evidencia para esta tarea.</p>
    @endif

    @if($submission && $submission->status === 'publicado')
        <div style="padding: 1rem; background-color: rgba(46,213,115,0.1); border-radius: 8px; font-size: 1.2rem;">
            <strong>Calificación Obtenida:</strong> {{ $submission->grade }} / 10
        </div>
    @endif

    @if($canSubmit)
        <hr style="margin: 1.5rem 0; border: none; border-top: 1px solid var(--border-color);">
        <h4>{{ $submission ? 'Reemplazar Archivo' : 'Subir Archivo de Evidencia' }}</h4>
        <form action="{{ route('student.tasks.upload', $task) }}" method="POST" enctype="multipart/form-data" style="margin-top: 1rem;">
            @csrf
            <div class="form-group">
                <label>Seleccionar Archivo (Solo PDF, Máximo 2MB)</label>
                <input type="file" name="file" accept="application/pdf" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Subir Evidencia</button>
        </form>
    @else
        @if(!$task->is_cancelled)
            <p style="color: var(--text-muted); margin-top: 1rem;"><em>El plazo de entrega ha expirado.</em></p>
        @endif
    @endif
</div>
@endsection