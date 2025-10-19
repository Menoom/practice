<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        switch ($user->current_role) {
            case 'admin':
                return $this->adminDashboard();
            case 'manager':
                return $this->managerDashboard();
            case 'user':
            default:
                return $this->userDashboard();
        }
    }

    private function userDashboard()
    {
        $user = Auth::user();
        $tasks = Task::where('assigned_to', $user->id)
                    ->with(['assignedBy'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        $stats = [
            'total_tasks' => $tasks->count(),
            'pending_tasks' => $tasks->where('status', 'pending')->count(),
            'in_progress_tasks' => $tasks->where('status', 'in_progress')->count(),
            'completed_tasks' => $tasks->where('status', 'completed')->count(),
        ];

        return view('dashboard.user', compact('user', 'tasks', 'stats'));
    }

    private function managerDashboard()
    {
        $user = Auth::user();
        $assignedTasks = Task::where('assigned_by', $user->id)
                            ->with(['assignedUser'])
                            ->orderBy('created_at', 'desc')
                            ->get();

        $myTasks = Task::where('assigned_to', $user->id)
                      ->with(['assignedBy'])
                      ->orderBy('created_at', 'desc')
                      ->get();

        $users = User::where('current_role', 'user')
                    ->orWhere('current_role', 'manager')
                    ->where('id', '!=', $user->id)
                    ->get();

        $stats = [
            'total_assigned' => $assignedTasks->count(),
            'my_tasks' => $myTasks->count(),
            'total_users' => $users->count(),
            'pending_assigned' => $assignedTasks->where('status', 'pending')->count(),
        ];

        return view('dashboard.manager', compact('user', 'assignedTasks', 'myTasks', 'users', 'stats'));
    }

    private function adminDashboard()
    {
        $user = Auth::user();
        $allTasks = Task::with(['assignedUser', 'assignedBy'])
                       ->orderBy('created_at', 'desc')
                       ->get();

        $allUsers = User::with(['employee'])->get();
        $employees = Employee::with(['user'])->get();

        $stats = [
            'total_users' => $allUsers->count(),
            'total_tasks' => $allTasks->count(),
            'total_employees' => $employees->count(),
            'admins' => $allUsers->where('current_role', 'admin')->count(),
            'managers' => $allUsers->where('current_role', 'manager')->count(),
            'users' => $allUsers->where('current_role', 'user')->count(),
        ];

        return view('dashboard.admin', compact('user', 'allTasks', 'allUsers', 'employees', 'stats'));
    }
}
