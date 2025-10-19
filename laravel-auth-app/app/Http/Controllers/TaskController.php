<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'assigned_by' => Auth::id(),
        ]);

        return back()->with('success', 'Task created successfully!');
    }

    public function update(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Check permissions
        if (!$user->hasRole('admin') && !$user->hasRole('manager')) {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $task->update($request->only(['title', 'description', 'assigned_to']));

        return back()->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $user = Auth::user();
        
        // Only admin can delete
        if (!$user->hasRole('admin')) {
            return back()->with('error', 'Unauthorized action.');
        }

        $task->delete();

        return back()->with('success', 'Task deleted successfully!');
    }

    public function toggleComplete(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Only the assigned user can mark task as complete
        if ($task->assigned_to != $user->id) {
            return back()->with('error', 'You can only complete tasks assigned to you.');
        }

        $task->update([
            'is_completed' => !$task->is_completed,
            'completed_at' => !$task->is_completed ? Carbon::now() : null,
        ]);

        return back()->with('success', 'Task status updated successfully!');
    }
}
