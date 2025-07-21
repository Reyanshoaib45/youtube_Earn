<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Package;
use App\Models\WithdrawalRequest;
use App\Models\PurchaseRequest;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $totalVideos = Video::count();
        $totalPackages = Package::count();
        $pendingWithdrawals = WithdrawalRequest::where('status', 'pending')->count();
        $pendingPurchases = PurchaseRequest::where('status', 'pending')->count();
        $totalUsers = User::where('role', 'user')->count();
        
        return view('manager.dashboard', compact(
            'totalVideos', 'totalPackages', 'pendingWithdrawals', 
            'pendingPurchases', 'totalUsers'
        ));
    }

    public function videos()
    {
        $videos = Video::latest()->get();
        return view('manager.videos', compact('videos'));
    }

    public function storeVideo(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|url|regex:/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+/',
            'reward' => 'required|numeric|min:1|max:1000',
            'duration' => 'required|integer|min:30|max:3600',
        ]);

        Video::create([
            'title' => $request->title,
            'youtube_url' => $request->youtube_url,
            'reward' => $request->reward,
            'duration' => $request->duration,
            'is_active' => true,
        ]);

        return back()->with('success', 'Video added successfully!');
    }

    public function updateVideo(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'reward' => 'required|numeric|min:1|max:1000',
        ]);

        $video->update([
            'title' => $request->title,
            'reward' => $request->reward,
        ]);
        
        return back()->with('success', 'Video updated successfully!');
    }

    public function deleteVideo(Video $video)
    {
        $video->delete();
        return back()->with('success', 'Video deleted successfully!');
    }

    public function packages()
    {
        $packages = Package::latest()->get();
        return view('manager.packages', compact('packages'));
    }

    public function storePackage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:100|max:50000',
            'duration_days' => 'required|integer|min:1|max:365',
            'daily_video_limit' => 'required|integer|min:1|max:100',
            'total_reward' => 'required|numeric|min:1|max:100000',
        ]);

        if ($request->total_reward <= $request->price) {
            return back()->withErrors(['total_reward' => 'Total reward must be greater than package price']);
        }

        Package::create($request->all() + ['is_active' => true]);
        return back()->with('success', 'Package created successfully!');
    }

    public function updatePackage(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:100|max:50000',
            'duration_days' => 'required|integer|min:1|max:365',
            'daily_video_limit' => 'required|integer|min:1|max:100',
            'total_reward' => 'required|numeric|min:1|max:100000',
        ]);

        if ($request->total_reward <= $request->price) {
            return back()->withErrors(['total_reward' => 'Total reward must be greater than package price']);
        }

        $package->update($request->all());
        return back()->with('success', 'Package updated successfully!');
    }

    public function deletePackage(Package $package)
    {
        $activePurchases = $package->purchases()->where('expires_at', '>', now())->count();
        
        if ($activePurchases > 0) {
            return back()->with('error', 'Cannot delete package with active purchases');
        }

        $package->delete();
        return back()->with('success', 'Package deleted successfully!');
    }

    public function purchaseRequests()
    {
        $purchaseRequests = PurchaseRequest::with(['user', 'package'])->latest()->get();
        return view('manager.purchase-requests', compact('purchaseRequests'));
    }

    public function approvePurchaseRequest(PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'Purchase request already processed');
        }

        // Check if user already has an active package
        if ($purchaseRequest->user->currentPackage()) {
            return back()->with('error', 'User already has an active package');
        }

        // Create the actual purchase
        Purchase::create([
            'user_id' => $purchaseRequest->user_id,
            'package_id' => $purchaseRequest->package_id,
            'amount' => $purchaseRequest->amount,
            'expires_at' => Carbon::now()->addDays($purchaseRequest->package->duration_days),
        ]);

        // Update purchase request status
        $purchaseRequest->update([
            'status' => 'approved',
            'approved_at' => now()
        ]);

        return back()->with('success', 'Purchase request approved successfully! User package is now active.');
    }

    public function rejectPurchaseRequest(Request $request, PurchaseRequest $purchaseRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $purchaseRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        return back()->with('success', 'Purchase request rejected successfully!');
    }

    public function withdrawals()
    {
        $withdrawals = WithdrawalRequest::with('user')->latest()->get();
        return view('manager.withdrawals', compact('withdrawals'));
    }

    public function approveWithdrawal(WithdrawalRequest $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal already processed');
        }

        if ($withdrawal->user->total_earnings < $withdrawal->amount) {
            return back()->with('error', 'User has insufficient balance');
        }

        if (!$withdrawal->user->canWithdraw()) {
            return back()->with('error', 'User does not meet referral requirements');
        }

        $withdrawal->update([
            'status' => 'approved',
            'approved_at' => now()
        ]);
        
        $withdrawal->user->decrement('total_earnings', $withdrawal->amount);

        return back()->with('success', 'Withdrawal approved successfully!');
    }

    public function banUser(Request $request, User $user)
    {
        $request->validate([
            'ban_reason' => 'required|string|max:255',
        ]);

        $user->update([
            'is_banned' => true,
            'ban_reason' => $request->ban_reason,
        ]);

        $user->withdrawalRequests()
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'rejection_reason' => 'User banned: ' . $request->ban_reason
            ]);

        return back()->with('success', 'User banned successfully!');
    }
}