<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);
        
        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
            'is_verified' => false,
        ]);

        // Send OTP via email
        try {
            Mail::raw("Your OTP for registration is: $otp. This OTP will expire in 10 minutes.", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Email Verification OTP');
            });
        } catch (\Exception $e) {
            // Log error but continue (for development)
            \Log::error('Email sending failed: ' . $e->getMessage());
        }

        return redirect()->route('verify.otp.form', ['email' => $user->email])
                         ->with('success', 'Registration successful! Please check your email for OTP.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.'])->withInput();
        }

        if (!$user->is_verified) {
            return back()->withErrors(['email' => 'Please verify your email first.'])->withInput();
        }

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Check if user has any role assigned
            if ($user->roles()->count() > 0) {
                // Redirect to dashboard based on first role
                $role = $user->roles()->first()->name;
                return redirect()->route('dashboard.' . $role);
            } else {
                // No role assigned, show role selection
                return redirect()->route('select.role');
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    public function showVerifyOtp(Request $request)
    {
        return view('auth.verify-otp', ['email' => $request->email]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        if ($user->otp != $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired.']);
        }

        // Mark user as verified
        $user->update([
            'is_verified' => true,
            'email_verified_at' => Carbon::now(),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        return redirect()->route('login')->with('success', 'Email verified successfully! Please login.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        if ($user->is_verified) {
            return redirect()->route('login')->with('info', 'Email already verified. Please login.');
        }

        // Generate new OTP
        $otp = rand(100000, 999999);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send OTP via email
        try {
            Mail::raw("Your new OTP is: $otp. This OTP will expire in 10 minutes.", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Email Verification OTP - Resend');
            });
        } catch (\Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());
        }

        return back()->with('success', 'OTP resent successfully! Please check your email.');
    }

    public function showSelectRole()
    {
        $roles = Role::all();
        return view('auth.select-role', compact('roles'));
    }

    public function selectRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = Auth::user();
        $role = Role::find($request->role_id);

        // Attach role to user if not already attached
        if (!$user->roles()->where('role_id', $role->id)->exists()) {
            $user->roles()->attach($role->id);
        }

        return redirect()->route('dashboard.' . $role->name);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }
}
