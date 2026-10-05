<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Docente - @yield('title', 'Gestión')</title>
    
    <!-- Fuente Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">

    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <svg class="icon" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4l7 3.82 7-3.82v-4L12 17l-7-3.82z"/></svg>
                <span>Gestión Escolar</span>
            </div>
            <ul class="sidebar-menu">
                @if(Auth::user()->role === 'docente')
                    <li>
                        <a href="{{ route('teacher.dashboard') }}" class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z"/></svg>
                            Panel Principal
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('teacher.students.index') }}" class="{{ request()->routeIs('teacher.students.*') ? 'active' : '' }}">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                            Alumnos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('teacher.tasks.create') }}" class="{{ request()->routeIs('teacher.tasks.create') ? 'active' : '' }}">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                            Nueva Tarea
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z"/></svg>
                            Mis Tareas
                        </a>
                    </li>
                @endif
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div>
                    <strong>Usuario:</strong> {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})
                </div>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <button id="theme-toggle" class="theme-toggle" title="Cambiar tema">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-2.98 0-5.4-2.42-5.4-5.4 0-1.81.89-3.42 2.26-4.4C12.92 3.04 12.46 3 12 3z"/></svg>
                    </button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M10.09 15.59L11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59zM19 3H5c-1.11 0-2 .9-2 2v4h2V5h14v14H5v-4H3v4c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                            Salir
                        </button>
                    </form>
                </div>
            </header>

            <!-- Body -->
            <main class="content-body">
                @if(session('success'))
                    <div class="card" style="border-left: 5px solid #2ed573; background-color: rgba(46,213,115,0.1);">
                        <p style="color: #2ed573; font-weight: bold;">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="card" style="border-left: 5px solid #d9534f; background-color: rgba(217,83,79,0.1);">
                        <p style="color: #d9534f; font-weight: bold;">{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>