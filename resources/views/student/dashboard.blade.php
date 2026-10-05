@extends('layouts.app')

@section('title', 'Panel de Alumno')

@section('content')
<h2>Historial de Tareas y Actividades</h2>

<!-- Notificaciones -->
@if($notifications->count() > 0)
    <div class="card">
        <div class="card-title">Notificaciones</div>
        <ul style="list-style: none;">
            @foreach($notifications as $notif)
                <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                    {{ $notif->message }} <small style="color: var(--text-muted);">({{ $notif->created_at->diffForHumans() }})</small>
                </li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tarea</th>
                    <th>Tipo</th>
                    <th>Fecha Límite</th>
                    <th>Estado</th>
                    <th>Calificación</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    @php
                        $sub = $submissions->get($task->id);
                        $isOverdue = \Carbon\Carbon::now()->greaterThan($task->due_date);
                    @endphp
                    <tr>
                        <td><strong>{{ $task->title }}</strong></td>
                        <td><span class="badge badge-info">{{ str_replace('_', ' ', strtoupper($task->type)) }}</span></td>
                        <td>{{ $task->due_date->format('d/m/Y 23:59') }}</td>
                        <td>
                            @if($task->is_cancelled)
                                <span class="badge badge-danger">CANCELADA</span>
                            @elseif($sub)
                                <span class="badge badge-success">Enviado</span>
                            @elseif($isOverdue)
                                <span class="badge badge-danger">No entregó</span>
                            @else
                                <span class="badge badge-warning">Pendiente</span>
                            @endif
                        </td>
                        <td>
                            @if($sub && $sub->status === 'publicado')
                                <strong>{{ $sub->grade }} / 10</strong>
                            @else
                                <span style="color: var(--text-muted);">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('student.tasks.show', $task) }}" class="btn btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.85rem;">Detalles</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">No hay tareas asignadas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection