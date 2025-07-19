<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Check if user is banned
            if ($user->is_banned) {
                Auth::logout();
                return redirect()->route('banned');
            }
            
            // Track user location
            LocationService::trackUserLocation($user);
            
            // Redirect based on role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'manager':
                    return redirect()->route('manager.dashboard');
                default:
                    return redirect()->route('user.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
            'referred_by' => 'nullable|string|exists:users,referral_code',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'referred_by' => $request->referred_by,
            'referral_code' => Str::upper(Str::random(8)),
            'role' => 'user',
            'is_active' => false,
            'reward_balance' => 0.00,
        ]);

        Auth::login($user);
        
        // Track user location
        LocationService::trackUserLocation($user);

        return redirect()->route('user.dashboard');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            LocationService::logUserAction(Auth::user(), 'logout');
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function banned()
    {
        if (!Auth::check() || !Auth::user()->is_banned) {
            return redirect()->route('login');
        }
        
        return view('auth.banned');
    }
}
