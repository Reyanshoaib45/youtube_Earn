<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Models\Package;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalManagers = User::where('role', 'manager')->count();
        $totalPackages = Package::count();
         $totalWithdrawals = WithdrawalRequest::sum('amount');
        $totalVideos = Video::count();
        $pendingWithdrawals = WithdrawalRequest::where('status', 'pending')->count();
        
        return view('admin.dashboard', compact(
            'totalUsers', 'totalManagers', 'totalPackages', 
            'totalWithdrawals',
            'totalVideos', 'pendingWithdrawals'
        ));
    }

    public function managers()
    {
        $managers = User::where('role', 'manager')->latest()->get();
        return view('admin.managers', compact('managers'));
    }

    public function createManager(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:12',
        ]);

        User::create([
            'name' => $request->name,
            'phone'=> $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'manager',
            'referral_code' => User::generateUniqueReferralCode(),
        ]);

        return back()->with('success', 'Manager created successfully!');
    }

    public function updateManager(Request $request, User $manager)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $manager->id,
        ]);

        $manager->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Manager updated successfully!');
    }

    public function banManager(User $manager)
    {
        $manager->update(['is_banned' => true]);
        return back()->with('success', 'Manager banned successfully!');
    }

    public function unbanManager(User $manager)
    {
        $manager->update(['is_banned' => false, 'ban_reason' => null]);
        return back()->with('success', 'Manager unbanned successfully!');
    }

    public function withdrawals()
    {
        $withdrawals = WithdrawalRequest::with('user')->latest()->get();
        return view('admin.withdrawals', compact('withdrawals'));
    }
}