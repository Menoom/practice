<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

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
            'phone' => 'nullable|string|max:20',
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
            'is_verified' => false,
        ]);

        // Send OTP via email
        $this->sendOtpEmail($user, $otp);

        return redirect()->route('verify.otp')->with([
            'message' => 'Registration successful! Please check your email for OTP verification.',
            'user_id' => $user->id
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        if (!$user->is_verified) {
            // Generate new OTP for unverified users
            $otp = rand(100000, 999999);
            $user->update([
                'otp' => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(10),
            ]);

            $this->sendOtpEmail($user, $otp);

            return redirect()->route('verify.otp')->with([
                'message' => 'Please verify your email with the OTP sent to your email.',
                'user_id' => $user->id
            ]);
        }

        Auth::login($user);

        // Check if user has selected a role
        if (!$user->current_role) {
            return redirect()->route('select.role');
        }

        return redirect()->route('dashboard');
    }

    public function showOtpVerification()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->user_id);

        if (!$user || $user->otp != $request->otp) {
            return back()->withErrors([
                'otp' => 'Invalid OTP provided.',
            ]);
        }

        if (Carbon::now()->isAfter($user->otp_expires_at)) {
            return back()->withErrors([
                'otp' => 'OTP has expired. Please request a new one.',
            ]);
        }

        // Mark user as verified
        $user->update([
            'is_verified' => true,
            'email_verified_at' => Carbon::now(),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        Auth::login($user);

        return redirect()->route('select.role')->with('message', 'Email verified successfully!');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->user_id);
        $otp = rand(100000, 999999);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $this->sendOtpEmail($user, $otp);

        return back()->with('message', 'New OTP sent to your email!');
    }

    public function showRoleSelection()
    {
        $roles = ['user', 'manager', 'admin'];
        return view('auth.select-role', compact('roles'));
    }

    public function selectRole(Request $request)
    {
        $request->validate([
            'role' => 'required|in:user,manager,admin',
        ]);

        $user = Auth::user();
        $user->update(['current_role' => $request->role]);

        // Assign the role in the user_roles table
        $role = Role::where('name', $request->role)->first();
        if ($role) {
            $user->roles()->syncWithoutDetaching([$role->id => ['assigned_at' => Carbon::now()]]);
        }

        return redirect()->route('dashboard')->with('message', 'Role selected successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function sendOtpEmail($user, $otp)
    {
        try {
            // Send OTP email using the OtpMail class
            Mail::to($user->email)->send(new OtpMail($user, $otp));
            
            // Also log for debugging purposes
            \Log::info("OTP sent to {$user->email}: {$otp}");
        } catch (\Exception $e) {
            // If email sending fails, log the error and the OTP for debugging
            \Log::error("Failed to send OTP email to {$user->email}: " . $e->getMessage());
            \Log::info("OTP for {$user->email} (email failed): {$otp}");
        }
    }
}
