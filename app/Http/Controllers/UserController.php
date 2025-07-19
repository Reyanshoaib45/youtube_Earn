<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\UserPackage;
use App\Models\Video;
use App\Models\WatchedVideo;
use App\Models\Withdrawal;
use App\Models\Referral;
use App\Models\Notification;
use App\Models\Earning;
use App\Models\Category;
use App\Models\Setting;
use App\Models\User;

// Make sure User model is imported
use App\Services\LocationService;

// Import LocationService
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $activePackage = $user->activePackage;

        $totalEarnings = $user->reward_balance;
        $totalRewards = $user->earnings()->sum('amount');
        $totalWithdrawals = $user->withdrawals()->where('status', 'approved')->sum('amount');
        $pendingWithdrawals = $user->withdrawals()->where('status', 'pending')->sum('amount');
        $totalReferrals = $user->referrals()->count();
        $totalWatched = $user->watchedVideos()->count(); // OR use ->where('is_completed', true) if needed
        $videosWatched = $user->watchedVideos()->where('is_completed', true)->count();
        $recentEarnings = $user->earnings()->latest()->take(5)->get();
        $notifications = $user->notifications()->where('is_read', false)->latest()->take(3)->get();

        return view('user.dashboard', compact(
            'user', 'activePackage', 'totalEarnings', 'totalRewards',
            'totalWithdrawals', 'pendingWithdrawals', 'totalReferrals',
            'totalWatched', 'videosWatched', 'recentEarnings', 'notifications'
        ));
    }


    public function packages()
    {
        $packages = Package::where('is_active', true)->get();
        $user = Auth::user();
        $userPackage = $user->userPackage()->first(); // ✅ get the actual model instance

        return view('user.packages', compact('packages', 'userPackage'));
    }


    public function purchasePackage(Request $request, Package $package)
    {
        $user = Auth::user();

        // Check if user already has an active package
        if ($user->activePackage()) {
            return back()->with('error', 'You already have an active package.');
        }

        // Create user package record
        $userPackage = UserPackage::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'amount_paid' => $package->price,
            'payment_status' => 'pending',
            'is_active' => false,
        ]);

        LocationService::logUserAction($user, 'package_purchase_initiated', "Package: {$package->name}");

        return redirect()->route('user.payment', $userPackage);
    }

    public function showPayment(UserPackage $userPackage)
    {
        if ($userPackage->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.payment', compact('userPackage'));
    }

    public function submitPayment(Request $request, UserPackage $userPackage)
    {
        if ($userPackage->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_method' => 'required|in:jazzcash,easypaisa',
            'transaction_id' => 'required|string|max:255',
            'payment_screenshot' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'sender_name' => 'required|string|max:255',
            'sender_number' => 'required|string|max:20',
        ]);

        $screenshotPath = $request->file('payment_screenshot')->store('payment_screenshots', 'public');

        $userPackage->update([
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'payment_screenshot' => $screenshotPath,
            'sender_name' => $request->sender_name,
            'sender_number' => $request->sender_number,
            'payment_notes' => $request->payment_notes,
            'submitted_at' => now(),
        ]);

        LocationService::logUserAction(Auth::user(), 'payment_submitted', "Package: {$userPackage->package->name}");

        return redirect()->route('user.packages')->with('success', 'Payment submitted successfully! Please wait for approval.');
    }

    public function videos(Request $request)
    {
        $user = Auth::user();

        if (!$user->activePackage()) {
            return redirect()->route('user.packages')->with('error', 'Please purchase a package to watch videos.');
        }

        $query = Video::where('is_active', true)->where('status', 'published');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        $videos = $query->latest()->paginate(12);
        $categories = Category::all();

        return view('user.videos', compact('videos', 'categories'));
    }

    public function watchVideo(Video $video)
    {
        $user = Auth::user();

        if (!$user->activePackage()) {
            return redirect()->route('user.packages')->with('error', 'Please purchase a package to watch videos.');
        }

        // Check if user has already watched this video today
        $alreadyWatched = WatchedVideo::where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->whereDate('created_at', today())
            ->exists();

        if ($alreadyWatched) {
            return back()->with('error', 'You have already watched this video today.');
        }

        // Create watch record
        $watchedVideo = WatchedVideo::create([
            'user_id' => $user->id,
            'video_id' => $video->id,
            'started_at' => now(),
            'watched_seconds' => 0,
            'is_completed' => false,
            'reward_granted' => false,
        ]);

        LocationService::logUserAction($user, 'video_watch_started', "Video: {$video->title}");

        return view('user.watch-video', compact('video', 'watchedVideo'));
    }

    public function updateWatchProgress(Request $request, Video $video)
    {
        $request->validate([
            'watched_seconds' => 'required|integer|min:0',
        ]);

        $user = Auth::user();
        $watchedVideo = WatchedVideo::where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->whereDate('created_at', today())
            ->first();

        if (!$watchedVideo) {
            return response()->json(['error' => 'Watch record not found'], 404);
        }

        $watchedVideo->update([
            'watched_seconds' => $request->watched_seconds,
        ]);

        // Check if video is completed (watched minimum required time)
        $requiredSeconds = $video->min_watch_minutes * 60;
        if ($request->watched_seconds >= $requiredSeconds && !$watchedVideo->is_completed) {
            $watchedVideo->update([
                'is_completed' => true,
                'completed_at' => now(),
                'reward_granted' => true,
                'reward_amount' => $video->reward_amount,
            ]);

            // Add reward to user balance
            $user->increment('reward_balance', $video->reward_amount);

            // Create earning record
            Earning::create([
                'user_id' => $user->id,
                'amount' => $video->reward_amount,
                'type' => 'video_reward',
                'description' => "Reward for watching: {$video->title}",
                'reference_type' => 'video',
                'reference_id' => $video->id,
            ]);

            // Update video stats
            $video->increment('completion_count');

            LocationService::logUserAction($user, 'video_completed', "Video: {$video->title}, Reward: {$video->reward_amount}");

            return response()->json([
                'completed' => true,
                'reward' => $video->reward_amount,
                'message' => 'Congratulations! You earned $' . number_format($video->reward_amount, 2)
            ]);
        }

        return response()->json(['completed' => false]);
    }

    public function rewards()
    {
        $user = Auth::user();
        $activePackage = $user->activePackage();
        $totalEarnings = $user->reward_balance;
        $totalWithdrawals = $user->withdrawals()->where('status', 'approved')->sum('amount');
        $pendingWithdrawals = $user->withdrawals()->where('status', 'pending')->sum('amount');
        $totalReferrals = $user->referrals()->count();
        $videosWatched = $user->watchedVideos()->where('is_completed', true)->count();
        $recentEarnings = $user->earnings()->latest()->take(5)->get();
        $notifications = $user->notifications()->where('is_read', false)->latest()->take(3)->get();
        $watchedVideos = $user->watchedVideos()->latest()->take(10)->get();

        // Page specific variables
        $earnings = $user->earnings()->latest()->paginate(20);
        $monthlyEarnings = $user->earnings()->whereMonth('created_at', now()->month)->sum('amount');
        $todayEarnings = $user->earnings()->whereDate('created_at', today())->sum('amount');

        return view('user.rewards', compact(
            'user', 'activePackage', 'totalEarnings', 'totalWithdrawals',
            'pendingWithdrawals', 'totalReferrals', 'videosWatched',
            'recentEarnings', 'notifications', 'watchedVideos',
            'earnings', 'monthlyEarnings', 'todayEarnings'
        ));
    }




//    public function withdrawals()
//    {
//        $user = Auth::user()->load('activePackage.package');
//
//        $activePackage = $user->activePackage;
//        $packageName = $activePackage?->package?->name ?? 'N/A';
//
//
//        $activePackage = $user->activePackage();
//        $withdrawals = $user->withdrawals()->latest()->paginate(10);
//
//        // Logic to determine if the user can withdraw
//        $canWithdraw = false;
//        if ($activePackage && $activePackage->package) {
//            $minWithdrawal = $activePackage->package->min_withdrawal ?? 0;
//            $hasSufficientBalance = $user->reward_balance >= $minWithdrawal;
//
//            $lastWithdrawal = $user->withdrawals()->latest()->first();
//            $cooldownPassed = !$lastWithdrawal || $lastWithdrawal->requested_at->lt(now()->subHours(24));
//
//            $canWithdraw = $hasSufficientBalance && $cooldownPassed;
//        }
//
//        $totalWithdrawn = $user->withdrawals()->where('status', 'approved')->sum('amount');
//        $pendingAmount = $user->withdrawals()->where('status', 'pending')->sum('amount');
//
//        return view('user.withdrawals', compact(
//            'user',
//            'activePackage',
//            'withdrawals',
//            'canWithdraw',
//            'totalWithdrawn',
//            'pendingAmount'
//        ));
//    }
    public function withdrawals()
    {
        $user = Auth::user();
        $activePackage = $user->activePackage()->with('package')->first();

        $totalEarnings = $user->reward_balance;
        $earnings = $user->earnings()->latest()->paginate(20);

        $totalRewards = $user->reward_balance; // <== This is the variable you're missing
        $monthlyEarnings = $user->earnings()->whereMonth('created_at', now()->month)->sum('amount');
        $todayEarnings = $user->earnings()->whereDate('created_at', today())->sum('amount');
        $totalWatched = $user->watchedVideos()->where('is_completed', true)->count();
        $totalWithdrawals = $user->withdrawals()->where('status', 'approved')->sum('amount');
        $pendingWithdrawals = $user->withdrawals()->where('status', 'pending')->sum('amount');
        $totalReferrals = $user->referrals()->count();
        $videosWatched = $user->watchedVideos()->where('is_completed', true)->count();
        $recentEarnings = $user->earnings()->latest()->take(5)->get();
        $notifications = $user->notifications()->where('is_read', false)->latest()->take(3)->get();
        $watchedVideos = $user->watchedVideos()->latest()->take(10)->get();

        return view('user.dashboard', compact(
            'user', 'activePackage', 'totalEarnings', 'totalWithdrawals',
            'pendingWithdrawals', 'totalReferrals', 'videosWatched',
            'earnings', 'totalRewards', 'monthlyEarnings', 'todayEarnings',
            'recentEarnings', 'notifications', 'watchedVideos', 'totalWatched'
        ));
    }


    public function requestWithdrawal(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'amount' => 'required|numeric|min:10|max:' . $user->reward_balance,
            'method' => 'required|in:jazzcash,easypaisa,bank_transfer,paypal',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required_if:method,bank_transfer|string|max:255',
            'branch_code' => 'nullable|string|max:20',
        ]);

        // Calculate fee (2% of withdrawal amount)
        $feePercentage = 2;
        $feeAmount = ($request->amount * $feePercentage) / 100;
        $finalAmount = $request->amount - $feeAmount;

        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'fee_amount' => $feeAmount,
            'final_amount' => $finalAmount,
            'method' => $request->method,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'branch_code' => $request->branch_code,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        // Deduct amount from user balance (will be returned if rejected)
        $user->decrement('reward_balance', $request->amount);

        LocationService::logUserAction($user, 'withdrawal_requested', "Amount: {$request->amount}, Method: {$request->method}");

        return back()->with('success', 'Withdrawal request submitted successfully!');
    }

    public function referrals()
    {
        $user = Auth::user();

        // Paginated list of referrals
        $referrals = $user->referrals()
            ->with(['referred.activePackage.package']) // eager load relationships
            ->latest()
            ->paginate(10);

        // Total referrals
        $referralCount = $user->referrals()->count();

        // Referrals made this month
        $monthlyReferrals = $user->referrals()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Total earnings from referral bonuses
        $referralEarnings = $user->earnings()
            ->where('type', 'referral_bonus')
            ->sum('amount');

        // Referral bonus amount (this could be from config or DB)
        $referralBonus = 5.00; // Example static value

        // Generate referral link
        $referralLink = route('register', ['ref' => $user->referral_code]);

        // Top referrers this month (customize as needed)
        $topReferrers = User::withCount(['referrals as monthly_referrals' => function ($query) {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }])
            ->addSelect([
                'monthly_earnings' => DB::table('earnings')
                    ->selectRaw('SUM(amount)')
                    ->whereColumn('earnings.user_id', 'users.id')
                    ->where('type', 'referral_bonus')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
            ])
            ->orderByDesc('monthly_referrals')
            ->limit(10)
            ->get();

        // User rank (optional)
        $userRank = User::withCount(['referrals as monthly_referrals' => function ($query) {
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            }])
                ->orderByDesc('monthly_referrals')
                ->get()
                ->search(fn($u) => $u->id === $user->id) + 1;

        return view('user.referrals', compact(
            'referrals',
            'referralCount',
            'monthlyReferrals',
            'referralEarnings',
            'referralBonus',
            'referralLink',
            'topReferrers',
            'userRank'
        ));
    }

    public function profile()
    {
        return view('user.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        $data = $request->only(['name', 'phone_number']);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        LocationService::logUserAction($user, 'profile_updated');

        return back()->with('success', 'Profile updated successfully!');
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->paginate(20);

        // Mark all as read
        $user->notifications()->where('is_read', false)->update(['is_read' => true, 'read_at' => now()]);

        return view('user.notifications', compact('notifications'));
    }

    public function locationHistory()
    {
        $user = Auth::user();
        $locationLogs = $user->locationLogs()->latest()->paginate(20);
        $ipTrackings = $user->ipTrackings()->latest()->paginate(10);

        return view('user.location-history', compact('locationLogs', 'ipTrackings'));
    }
}
