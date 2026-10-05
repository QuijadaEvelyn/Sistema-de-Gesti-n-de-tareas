@extends('layouts.app')

@section('title', 'Gestión de Alumnos')

@section('content')
<h2>Gestión de Alumnos del Grupo</h2>

<div class="grid-2">
    <!-- Captura Individual -->
    <div class="card">
        <div class="card-title">Captura Individual</div>
        <form action="{{ route('teacher.students.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Alumno</button>
        </form>
    </div>

    <!-- Carga Masiva -->
    <div class="card">
        <div class="card-title">Carga Masiva (CSV / Texto)</div>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">
            Formato: <code>Nombre, Correo, Contraseña</code> (Una línea por alumno)
        </p>
        <form action="{{ route('teacher.students.bulk') }}" method="POST">
            @csrf
            <div class="form-group">
                <textarea name="bulk_data" class="form-control" rows="6" placeholder="Juan Perez, juan@mail.com, 123456&#10;Maria Lopez, maria@mail.com, 123456" required></textarea>
            </div>
            <button type="submit" class="btn btn-secondary">Cargar Masivamente</button>
        </form>
    </div>
</div>

<!-- Lista de Alumnos -->
<div class="card">
    <div class="card-title">Lista del Grupo</div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>
                            @if($student->is_active)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <form action="{{ route('teacher.students.toggle', $student) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn {{ $student->is_active ? 'btn-danger' : 'btn-success' }}">
                                    {{ $student->is_active ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection