<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Middleware\RoleTeacherMiddleware;
use App\Http\Middleware\RoleStudentMiddleware;

// Rutas de Autenticación Libre
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas del Docente (Protegidas)
Route::middleware(['auth', RoleTeacherMiddleware::class])->prefix('docente')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    
    // Gestión de Alumnos
    Route::get('/alumnos', [TeacherController::class, 'indexStudents'])->name('teacher.students.index');
    Route::post('/alumnos', [TeacherController::class, 'storeStudent'])->name('teacher.students.store');
    Route::post('/alumnos/masivo', [TeacherController::class, 'bulkStoreStudents'])->name('teacher.students.bulk');
    Route::post('/alumnos/{student}/estado', [TeacherController::class, 'toggleStudentStatus'])->name('teacher.students.toggle');
    Route::put('/alumnos/{student}', [TeacherController::class, 'updateStudent'])->name('teacher.students.update');

    // Tareas y Evaluaciones
    Route::get('/tareas/crear', [TeacherController::class, 'createTask'])->name('teacher.tasks.create');
    Route::post('/tareas', [TeacherController::class, 'storeTask'])->name('teacher.tasks.store');
    Route::get('/tareas/{task}', [TeacherController::class, 'showTask'])->name('teacher.tasks.show');
    Route::post('/tareas/{task}/cancelar', [TeacherController::class, 'cancelTask'])->name('teacher.tasks.cancel');
    Route::post('/tareas/{task}/evaluar', [TeacherController::class, 'saveGrades'])->name('teacher.tasks.grades.save');
});

// Rutas del Alumno (Protegidas)
Route::middleware(['auth', RoleStudentMiddleware::class])->prefix('alumno')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/tareas/{task}', [StudentController::class, 'showTask'])->name('student.tasks.show');
    Route::post('/tareas/{task}/subir', [StudentController::class, 'uploadSubmission'])->name('student.tasks.upload');
});