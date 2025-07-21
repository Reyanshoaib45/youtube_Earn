<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Video;
use App\Models\UserVideo;
use App\Models\Purchase;
use App\Models\PurchaseRequest;
use App\Models\WithdrawalRequest;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $currentPackage = $user->currentPackage();
        $todayWatched = $user->todayWatchedVideos();
        $totalEarnings = $user->total_earnings ?? 0;
        $referralEarnings = $user->referrals()->sum('reward') ?? 0;
        $referralCount = $user->referrals()->count() ?? 0;
        
        return view('user.dashboard', compact(
            'user', 'currentPackage', 'todayWatched', 
            'totalEarnings', 'referralEarnings', 'referralCount'
        ));
    }

    public function referrals()
    {
        $user = Auth::user();
        
        // Basic referral stats
        $totalReferrals = $user->referrals()->count();
        $totalReferralEarnings = $user->referrals()->sum('reward');
        $thisMonthReferrals = $user->referrals()
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        // Referral level calculation
        $referralLevel = $this->calculateReferralLevel($totalReferrals);
        
        // Referral tree (direct referrals with their sub-referrals)
        $referralTree = $user->referrals()
            ->with(['referred' => function($query) {
                $query->withCount('referrals');
            }])
            ->latest()
            ->get();
        
        // Recent referrals for history
        $recentReferrals = $user->referrals()
            ->with('referred')
            ->latest()
            ->limit(5)
            ->get();
        
        // Top referrers leaderboard
        $topReferrers = User::withCount('referrals')
            ->where('role', 'user')
            ->orderBy('referrals_count', 'desc')
            ->limit(5)
            ->get();
        
        // Referral link
        $referralLink = url('/register?referral_code=' . $user->referral_code);
        
        return view('user.referrals', compact(
            'user', 'totalReferrals', 'totalReferralEarnings', 'thisMonthReferrals',
            'referralLevel', 'referralTree', 'recentReferrals', 'topReferrers', 'referralLink'
        ));
    }

    private function calculateReferralLevel($totalReferrals)
    {
        if ($totalReferrals >= 50) return 5;
        if ($totalReferrals >= 25) return 4;
        if ($totalReferrals >= 10) return 3;
        if ($totalReferrals >= 5) return 2;
        return 1;
    }

    public function videos()
    {
        $user = Auth::user();
        $currentPackage = $user->currentPackage();
        
        if (!$currentPackage) {
            return redirect()->route('user.packages')->with('error', 'Please buy a package first');
        }

        $todayWatched = $user->todayWatchedVideos();
        $canWatch = $todayWatched < $currentPackage->daily_video_limit;
        
        $videos = Video::where('is_active', true)->get();
        
        return view('user.videos', compact('videos', 'canWatch', 'todayWatched', 'currentPackage'));
    }

    public function watchVideo(Request $request)
    {
        $user = Auth::user();
        $video = Video::findOrFail($request->video_id);
        $currentPackage = $user->currentPackage();

        // Validation checks
        if (!$currentPackage) {
            return response()->json(['error' => 'No active package'], 400);
        }

        $todayWatched = $user->todayWatchedVideos();
        if ($todayWatched >= $currentPackage->daily_video_limit) {
            return response()->json(['error' => 'Daily limit reached'], 400);
        }

        // Anti-cheat: Check if already watched today
        $alreadyWatched = UserVideo::where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($alreadyWatched) {
            return response()->json(['error' => 'Already watched today'], 400);
        }

        // Anti-cheat: Validate watch duration (must be at least 80% of video duration)
        $watchDuration = $request->input('watch_duration', 0);
        $requiredDuration = $video->duration * 0.8; // 80% minimum
        
        if ($watchDuration < $requiredDuration) {
            return response()->json(['error' => 'Video not watched completely'], 400);
        }

        // Record the watch
        UserVideo::create([
            'user_id' => $user->id,
            'video_id' => $video->id,
            'reward_earned' => $video->reward,
            'watch_duration' => $watchDuration,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Update user earnings
        $user->increment('total_earnings', $video->reward);

        return response()->json([
            'success' => true, 
            'reward' => $video->reward,
            'new_balance' => $user->fresh()->total_earnings
        ]);
    }

    public function packages()
    {
        $packages = Package::where('is_active', true)->orderBy('price')->get();
        $userPackage = Auth::user()->currentPackage();
        $pendingPurchases = Auth::user()->purchaseRequests()->where('status', 'pending')->get();
        
        return view('user.packages', compact('packages', 'userPackage', 'pendingPurchases'));
    }

    public function buyPackage(Request $request)
    {
        $user = Auth::user();
        $package = Package::findOrFail($request->package_id);

        // Check if user already has an active package
        if ($user->currentPackage()) {
            return back()->with('error', 'You already have an active package');
        }

        // Check if user has pending purchase request
        $pendingRequest = $user->purchaseRequests()
            ->where('status', 'pending')
            ->exists();

        if ($pendingRequest) {
            return back()->with('error', 'You already have a pending purchase request');
        }

        return view('user.purchase-package', compact('package'));
    }

    public function submitPurchaseRequest(Request $request)
    {
        $user = Auth::user();
        
        $package = Package::findOrFail($request->package_id);

        $request->validate([
            'sender_number' => 'required|string|max:13',
            'transaction_id' => 'nullable|string|max:255',
            'payment_proof' => 'nullable|string|max:1000',
        ]);

        // Check if user already has an active package
        if ($user->currentPackage()) {
            return back()->with('error', 'You already have an active package');
        }

        // Check if user has pending purchase request
        $pendingRequest = $user->purchaseRequests()
            ->where('status', 'pending')
            ->exists();

        if ($pendingRequest) {
            return back()->with('error', 'You already have a pending purchase request');
        }
         
        PurchaseRequest::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'amount' => $package->price,
            'payment_method' => 'jazzcash',
            'sender_number' => $request->sender_number,
            'transaction_id' => $request->transaction_id,
            'payment_proof' => $request->payment_proof,
            'status' => 'pending',
        ]);
        
        return redirect()->route('user.dashboard')->with('success', 'Purchase request submitted successfully! Please wait for manager approval.');
    }

    public function withdraw()
    {
        $user = Auth::user();
        $referralCount = $user->referrals()->count();
        $canWithdraw = $referralCount >= 3; // Minimum 3 referrals required
        $pendingWithdrawals = $user->withdrawalRequests()->where('status', 'pending')->get();
        
        return view('user.withdraw', compact('user', 'canWithdraw', 'referralCount', 'pendingWithdrawals'));
    }

    public function requestWithdraw(Request $request)
    {
        $user = Auth::user();
        
        // Check referral requirement
        if (!$user->canWithdraw()) {
            return back()->with('error', 'You need at least 3 referrals to withdraw');
        }

        $request->validate([
            'amount' => 'required|numeric|min:100|max:' . $user->total_earnings,
            'payment_method' => 'required|string|in:bank_transfer,jazzcash,easypaisa',
            'account_details' => 'required|string|max:255',
        ]);

        // Check for pending withdrawals
        $pendingWithdrawal = $user->withdrawalRequests()
            ->where('status', 'pending')
            ->exists();
        
        if ($pendingWithdrawal) {
            return back()->with('error', 'You already have a pending withdrawal request');
        }

        WithdrawalRequest::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'account_details' => $request->account_details,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Withdrawal request submitted successfully!');
    }
}