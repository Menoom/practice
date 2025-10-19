<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Only admin can create users
        if (!$user->hasRole('admin')) {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_verified' => true,
        ]);

        $newUser->roles()->attach($request->role_id);

        return back()->with('success', 'User created successfully!');
    }

    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Only admin can update users
        if (!$currentUser->hasRole('admin')) {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update($request->only(['name', 'email']));
        
        // Update role
        $user->roles()->sync([$request->role_id]);

        return back()->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $currentUser = Auth::user();
        
        // Only admin can delete users
        if (!$currentUser->hasRole('admin')) {
            return back()->with('error', 'Unauthorized action.');
        }

        // Prevent deleting yourself
        if ($user->id == $currentUser->id) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully!');
    }

    public function assignRole(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Only admin and manager can assign roles
        if (!$currentUser->hasRole('admin') && !$currentUser->hasRole('manager')) {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        // Manager can only assign 'user' role
        if ($currentUser->hasRole('manager')) {
            $role = Role::find($request->role_id);
            if ($role->name != 'user') {
                return back()->with('error', 'Managers can only assign user role.');
            }
        }

        $user->roles()->sync([$request->role_id]);

        return back()->with('success', 'Role assigned successfully!');
    }
}
