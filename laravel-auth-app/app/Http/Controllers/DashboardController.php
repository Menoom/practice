<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        $user = Auth::user();
        $tasks = Task::where('assigned_to', $user->id)->get();
        
        return view('dashboards.user', compact('user', 'tasks'));
    }

    public function managerDashboard()
    {
        $user = Auth::user();
        $allUsers = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->get();
        $tasks = Task::where('assigned_by', $user->id)->with(['assignedUser'])->get();
        
        return view('dashboards.manager', compact('user', 'allUsers', 'tasks'));
    }

    public function adminDashboard()
    {
        $user = Auth::user();
        $allUsers = User::with('roles')->get();
        $allTasks = Task::with(['assignedUser', 'assignedByUser'])->get();
        
        return view('dashboards.admin', compact('user', 'allUsers', 'allTasks'));
    }
}
