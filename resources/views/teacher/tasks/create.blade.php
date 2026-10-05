@extends('layouts.app')

@section('title', 'Crear Tarea')

@section('content')
<h2>Crear Nueva Tarea</h2>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('teacher.tasks.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Título de la Tarea</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Tipo de Tarea</label>
            <select name="type" class="form-control" required>
                <option value="tarea_casa">Tarea en Casa</option>
                <option value="actividad_clase">Actividad en Clase</option>
                <option value="exposicion">Exposición</option>
            </select>
        </div>

        <div class="form-group">
            <label>Fecha de Entrega (Se limitará automáticamente a las 23:59 del día)</label>
            <input type="date" name="due_date" class="form-control" min="{{ date('Y-m-d') }}" required>
        </div>

        <div class="form-group">
            <label>Descripción / Instrucciones</label>
            <textarea name="description" class="form-control" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Crear Tarea</button>
    </form>
</div>
@endsection