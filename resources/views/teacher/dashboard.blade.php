@extends('layouts.app')

@section('title', 'Panel Docente')

@section('content')
<h2>Panel de Control Docente</h2>
<p style="color: var(--text-muted); margin-bottom: 1.5rem;">Gestión general del grupo único y estado de tareas.</p>

<div class="grid-2">
    <div class="card">
        <div class="card-title">Alumnos Registrados</div>
        <p style="font-size: 2.5rem; font-weight: bold; color: var(--blue-bell);">{{ $studentsCount }}</p>
        <a href="{{ route('teacher.students.index') }}" class="btn btn-secondary" style="margin-top: 1rem;">Gestionar Alumnos</a>
    </div>

    <div class="card">
        <div class="card-title">Acción Rápida</div>
        <p style="margin-bottom: 1rem;">Crea nuevas tareas para el grupo con asignación de tiempo límite.</p>
        <a href="{{ route('teacher.tasks.create') }}" class="btn btn-primary">Crear Nueva Tarea</a>
    </div>
</div>

<div class="card">
    <div class="card-title">Tareas Asignadas</div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Fecha Límite</th>
                    <th>Entregas</th>
                    <th>Estado de Evaluaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasksData as $data)
                    <tr>
                        <td><strong>{{ $data['task']->title }}</strong></td>
                        <td>
                            <span class="badge badge-info">
                                {{ str_replace('_', ' ', strtoupper($data['task']->type)) }}
                            </span>
                        </td>
                        <td>{{ $data['task']->due_date->format('d/m/Y 23:59') }}</td>
                        <td>{{ $data['submissions'] }} alumnos entregaron</td>
                        <td>
                            @if($data['is_overdue'])
                                <span class="badge badge-warning">Listo para evaluar</span>
                            @else
                                <span class="badge badge-info">En proceso de entrega</span>
                            @endif

                            @if($data['task']->is_cancelled)
                                <span class="badge badge-danger">CANCELADA</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('teacher.tasks.show', $data['task']) }}" class="btn btn-secondary">Ver Detalle</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">No hay tareas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection