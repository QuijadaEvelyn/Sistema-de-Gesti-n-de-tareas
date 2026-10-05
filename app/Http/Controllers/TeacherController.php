<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Submission;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $studentsCount = User::where('role', 'alumno')->count();
        $activeTasks = Task::where('is_cancelled', false)->get();
        
        $tasksData = $activeTasks->map(function ($task) {
            $totalSubmissions = Submission::where('task_id', $task->id)->count();
            return [
                'task' => $task,
                'submissions' => $totalSubmissions,
                'is_overdue' => Carbon::now()->greaterThan($task->due_date),
            ];
        });

        return view('teacher.dashboard', compact('studentsCount', 'tasksData'));
    }

    public function indexStudents()
    {
        $students = User::where('role', 'alumno')->orderBy('name')->get();
        return view('teacher.students.index', compact('students'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'alumno',
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Alumno registrado correctamente.');
    }

    public function bulkStoreStudents(Request $request)
    {
        $request->validate([
            'bulk_data' => 'required|string',
        ]);

        $lines = explode("\n", str_replace("\r", "", $request->bulk_data));
        $created = 0;

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;
            
            $parts = explode(',', $line);
            if (count($parts) >= 3) {
                $name = trim($parts[0]);
                $email = trim($parts[1]);
                $password = trim($parts[2]);

                if (!User::where('email', $email)->exists()) {
                    User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'role' => 'alumno',
                        'is_active' => true,
                    ]);
                    $created++;
                }
            }
        }

        return redirect()->back()->with('success', "Se cargaron $created alumnos masivamente.");
    }

    public function updateStudent(Request $request, User $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
        ]);

        $student->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $student->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->back()->with('success', 'Datos del alumno actualizados.');
    }

    public function toggleStudentStatus(User $student)
    {
        $student->update(['is_active' => !$student->is_active]);
        $status = $student->is_active ? 'activado' : 'desactivado';
        return redirect()->back()->with('success', "Alumno $status correctamente.");
    }

    public function createTask()
    {
        return view('teacher.tasks.create');
    }

    public function storeTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:tarea_casa,actividad_clase,exposicion',
            'due_date' => 'required|date_format:Y-m-d',
        ]);

        $dueDate = Carbon::createFromFormat('Y-m-d', $request->due_date)->setTime(23, 59, 59);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'due_date' => $dueDate,
            'is_cancelled' => false,
        ]);

        // Notificar a todos los alumnos activos
        $students = User::where('role', 'alumno')->where('is_active', true)->get();
        foreach ($students as $student) {
            Notification::create([
                'user_id' => $student->id,
                'message' => "Nueva tarea asignada: {$task->title}",
                'type' => 'nueva_tarea',
            ]);
        }

        return redirect()->route('teacher.dashboard')->with('success', 'Tarea creada y asignada con éxito.');
    }

    public function cancelTask(Task $task)
    {
        $task->update(['is_cancelled' => true]);

        $students = User::where('role', 'alumno')->get();
        foreach ($students as $student) {
            Notification::create([
                'user_id' => $student->id,
                'message' => "La tarea '{$task->title}' ha sido cancelada.",
                'type' => 'tarea_cancelada',
            ]);
        }

        return redirect()->back()->with('success', 'La tarea ha sido cancelada.');
    }

    public function showTask(Task $task)
    {
        $students = User::where('role', 'alumno')->where('is_active', true)->get();
        $submissions = Submission::where('task_id', $task->id)->get()->keyBy('student_id');
        $isOverdue = Carbon::now()->greaterThan($task->due_date);

        return view('teacher.tasks.show', compact('task', 'students', 'submissions', 'isOverdue'));
    }

    public function saveGrades(Request $request, Task $task)
    {
        if (Carbon::now()->lessThanOrEqualTo($task->due_date)) {
            return redirect()->back()->with('error', 'No se puede evaluar la tarea antes de la fecha límite.');
        }

        $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'nullable|numeric|min:0|max:10',
            'action' => 'required|in:draft,publish',
        ]);

        $status = $request->action === 'publish' ? 'publicado' : 'borrador';

        foreach ($request->grades as $studentId => $grade) {
            if ($grade !== null) {
                Submission::updateOrCreate(
                    ['task_id' => $task->id, 'student_id' => $studentId],
                    [
                        'grade' => $grade,
                        'status' => $status,
                        'file_path' => Submission::where('task_id', $task->id)->where('student_id', $studentId)->value('file_path') ?? '',
                        'original_filename' => Submission::where('task_id', $task->id)->where('student_id', $studentId)->value('original_filename') ?? '',
                    ]
                );
            }
        }

        $msg = $status === 'publicado' ? 'Calificaciones publicadas correctamente.' : 'Borrador guardado correctamente.';
        return redirect()->back()->with('success', $msg);
    }
}