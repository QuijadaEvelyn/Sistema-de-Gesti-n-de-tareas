<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Submission;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = Auth::user();
        $tasks = Task::orderBy('due_date', 'asc')->get();
        $submissions = Submission::where('student_id', $student->id)->get()->keyBy('task_id');
        $notifications = Notification::where('user_id', $student->id)->orderBy('created_at', 'desc')->take(5)->get();

        return view('student.dashboard', compact('tasks', 'submissions', 'notifications'));
    }

    public function showTask(Task $task)
    {
        $submission = Submission::where('task_id', $task->id)
            ->where('student_id', Auth::id())
            ->first();

        $canSubmit = Carbon::now()->lessThanOrEqualTo($task->due_date) && !$task->is_cancelled;

        return view('student.tasks.show', compact('task', 'submission', 'canSubmit'));
    }

    public function uploadSubmission(Request $request, Task $task)
    {
        if (Carbon::now()->greaterThan($task->due_date)) {
            return redirect()->back()->with('error', 'La fecha límite de entrega ha expirado.');
        }

        if ($task->is_cancelled) {
            return redirect()->back()->with('error', 'La tarea está cancelada y no admite entregas.');
        }

        $request->validate([
            'file' => 'required|mimes:pdf|max:2048', // PDF max 2MB
        ]);

        $student = Auth::user();
        
        // Si ya había subido un archivo previo, borrarlo
        $existing = Submission::where('task_id', $task->id)->where('student_id', $student->id)->first();
        if ($existing && $existing->file_path) {
            Storage::disk('public')->delete($existing->file_path);
        }

        $path = $request->file('file')->store('evidencias', 'public');

        Submission::updateOrCreate(
            ['task_id' => $task->id, 'student_id' => $student->id],
            [
                'file_path' => $path,
                'original_filename' => $request->file('file')->getClientOriginalName(),
                'submitted_at' => Carbon::now(),
                'status' => 'pendiente',
            ]
        );

        return redirect()->back()->with('success', 'Evidencia subida correctamente.');
    }

    public function markNotificationsRead()
    {
        Notification::where('user_id', Auth::id())->update(['is_read' => true]);
        return redirect()->back();
    }
}