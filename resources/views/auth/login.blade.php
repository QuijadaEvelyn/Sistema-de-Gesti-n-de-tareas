<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Sistema Docente</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-left">
            <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">SISTEMA DOCENTE</h1>
            <p style="font-size: 1.1rem; text-align: center; max-width: 300px;">Plataforma de gestión de tareas y control de evidencias de alumnos.</p>
        </div>
        <div class="login-right">
            <div class="login-card">
                <h2 style="text-align: center; margin-bottom: 1.5rem; font-family: 'Roboto Slab', serif;">INICIO DE SESIÓN</h2>
                
                @if($errors->any())
                    <div style="color: #d9534f; margin-bottom: 1rem; font-size: 0.9rem;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" name="email" id="email" class="form-control" required autofocus placeholder="usuario@correo.com">
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">
                        Ingresar
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>