<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isManager() && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::with(['employee', 'roles'])->get();
        
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isManager() && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::all();
        
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isManager() && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'current_role' => 'required|in:user,manager,admin',
            'employee_id' => 'nullable|string|unique:employees',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
        ]);

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'current_role' => $request->current_role,
            'is_verified' => true, // Admin/Manager created users are auto-verified
        ]);

        // Assign role
        $role = Role::where('name', $request->current_role)->first();
        if ($role) {
            $newUser->roles()->attach($role->id, [
                'assigned_at' => now(),
                'assigned_by' => $user->id,
            ]);
        }

        // Create employee record if data provided
        if ($request->employee_id) {
            Employee::create([
                'user_id' => $newUser->id,
                'employee_id' => $request->employee_id,
                'department' => $request->department,
                'position' => $request->position,
                'hire_date' => $request->hire_date,
                'salary' => $request->salary,
                'status' => 'active',
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        $authUser = Auth::user();
        
        if (!$authUser->isManager() && !$authUser->isAdmin() && $authUser->id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $user->load(['employee', 'roles', 'assignedTasks', 'createdTasks']);
        
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $authUser = Auth::user();
        
        if (!$authUser->isManager() && !$authUser->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::all();
        
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $authUser = Auth::user();
        
        if (!$authUser->isManager() && !$authUser->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'current_role' => 'required|in:user,manager,admin',
            'employee_id' => 'nullable|string|unique:employees,employee_id,' . ($user->employee->id ?? 'NULL'),
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'current_role' => $request->current_role,
        ]);

        // Update role
        $role = Role::where('name', $request->current_role)->first();
        if ($role) {
            $user->roles()->sync([$role->id => [
                'assigned_at' => now(),
                'assigned_by' => $authUser->id,
            ]]);
        }

        // Update or create employee record
        if ($request->employee_id) {
            $user->employee()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_id' => $request->employee_id,
                    'department' => $request->department,
                    'position' => $request->position,
                    'hire_date' => $request->hire_date,
                    'salary' => $request->salary,
                ]
            );
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $authUser = Auth::user();
        
        // Only admin can delete users
        if (!$authUser->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent admin from deleting themselves
        if ($authUser->id == $user->id) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }

    public function profile()
    {
        $user = Auth::user();
        $user->load(['employee', 'roles']);
        
        return view('users.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Verify current password if changing password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return back()->with('success', 'Profile updated successfully!');
    }
}
