<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $tasks = Task::with(['assignedUser', 'assignedBy'])->get();
        } elseif ($user->isManager()) {
            $tasks = Task::where('assigned_by', $user->id)
                        ->orWhere('assigned_to', $user->id)
                        ->with(['assignedUser', 'assignedBy'])
                        ->get();
        } else {
            $tasks = Task::where('assigned_to', $user->id)
                        ->with(['assignedBy'])
                        ->get();
        }

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isManager() && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::where('current_role', 'user')
                    ->orWhere('current_role', 'manager')
                    ->where('id', '!=', $user->id)
                    ->get();

        return view('tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isManager() && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'nullable|date|after:today',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
            'assigned_by' => $user->id,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function show(Task $task)
    {
        $user = Auth::user();
        
        // Check if user can view this task
        if (!$user->isAdmin() && $task->assigned_to != $user->id && $task->assigned_by != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $user = Auth::user();
        
        if (!$user->isManager() && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Managers can only edit tasks they created
        if ($user->isManager() && $task->assigned_by != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::where('current_role', 'user')
                    ->orWhere('current_role', 'manager')
                    ->where('id', '!=', $user->id)
                    ->get();

        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $user = Auth::user();
        
        if (!$user->isManager() && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Managers can only edit tasks they created
        if ($user->isManager() && $task->assigned_by != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $user = Auth::user();
        
        // Only admin can delete tasks
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Users can only update status of their assigned tasks
        if ($task->assigned_to != $user->id && !$user->isManager() && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $updateData = ['status' => $request->status];
        
        if ($request->status === 'completed') {
            $updateData['completed_at'] = now();
        }

        $task->update($updateData);

        return back()->with('success', 'Task status updated successfully!');
    }
}
