<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'referral_code' => 'nullable|string|exists:users,referral_code',
        ]);

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'referral_code' => User::generateUniqueReferralCode(),
            'total_earnings' => 0,
        ]);

        // Enhanced Referral System with Bonuses
        if ($request->referral_code) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
            if ($referrer && $referrer->id !== $user->id) {
                // Create referral record
                $referral = Referral::create([
                    'referrer_id' => $referrer->id,
                    'referred_id' => $user->id,
                    'reward' => 50.00, // Rs. 50 referral reward
                ]);
            
                // Add reward to referrer's balance
                $referrer->increment('total_earnings', 50.00);

                // Check for milestone bonuses
                $totalReferrals = $referrer->referrals()->count();
                
                // 5 referrals bonus
                if ($totalReferrals == 5) {
                    $referrer->increment('total_earnings', 100.00);
                    // You could add a notification here
                }
                
                // 10 referrals bonus
                if ($totalReferrals == 10) {
                    $referrer->increment('total_earnings', 250.00);
                    // You could add a notification here
                }

                // Log referral activity
                \Log::info("New referral: User {$user->id} referred by User {$referrer->id}. Total referrals: {$totalReferrals}");
            }
        }

        Auth::login($user);
        
        return redirect()->route('user.dashboard')->with('success', 
            'Registration successful! Welcome to Watch & Earn platform. Start referring friends to unlock withdrawal!');
    }
}
