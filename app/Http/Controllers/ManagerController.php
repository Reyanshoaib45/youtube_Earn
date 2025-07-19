<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPackage;
use App\Models\Video;
use App\Models\Category;
use App\Models\Package;
use App\Models\Withdrawal;
use App\Models\Notification;
use App\Models\ManagerLog;
use App\Models\Earning;
use App\Models\WatchedVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->count();
        $activeUsers = User::where('role', 'user')->where('is_active', true)->where('is_banned', false)->count();
        $totalVideos = Video::count();
        $totalPackages = Package::count();
        $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();
        $todayEarnings = WatchedVideo::whereDate('completed_at', today())->where('reward_granted', true)->sum('reward_amount');

        // Monthly stats
        $monthlyStats = [
            'new_users' => User::where('role', 'user')->whereMonth('created_at', now()->month)->count(),
            'videos_watched' => WatchedVideo::whereMonth('completed_at', now()->month)->where('is_completed', true)->count(),
            'total_earnings' => WatchedVideo::whereMonth('completed_at', now()->month)->where('reward_granted', true)->sum('reward_amount'),
            'withdrawals_processed' => Withdrawal::whereMonth('processed_at', now()->month)->where('status', 'approved')->count(),
        ];

        $recentUsers = User::where('role', 'user')->latest()->take(5)->get();
        $recentWithdrawals = Withdrawal::with('user')->where('status', 'pending')->latest()->take(5)->get();
        $notifications = Auth::user()->notifications()->latest()->take(5)->get();

        return view('manager.dashboard', compact(
            'totalUsers', 'activeUsers', 'totalVideos', 'totalPackages', 'pendingWithdrawals',
            'todayEarnings', 'monthlyStats', 'recentUsers', 'recentWithdrawals', 'notifications'
        ));
    }

    public function payments(Request $request)
    {
        $query = UserPackage::with(['user', 'package']);

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }
        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate(10);

        return view('manager.payments', compact('payments'));
    }

    public function getPaymentDetails($id)
    {
        $payment = UserPackage::with(['user', 'package'])->findOrFail($id);
        return response()->json($payment);
    }

    public function processPayment(Request $request, $id, $action)
    {
        $payment = UserPackage::findOrFail($id);
        $notes = $request->input('notes');

        DB::transaction(function () use ($payment, $action, $notes) {
            if ($action === 'approved') {
                $payment->payment_status = 'approved';
                $payment->approved_at = now();
                $payment->payment_notes = $notes;
                $payment->is_active = true;
                $payment->save();

                // Activate user and package
                $user = $payment->user;
                $user->is_active = true;
                $user->save();

                // Deactivate other packages for this user
                UserPackage::where('user_id', $user->id)
                    ->where('id', '!=', $payment->id)
                    ->update(['is_active' => false]);

                // Create notification
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'payment_approved',
                    'title' => 'Payment Approved!',
                    'message' => "Your payment for package '{$payment->package->name}' has been approved. Your package is now active!",
                    'priority' => 'high',
                    'action_url' => route('user.dashboard'),
                    'action_text' => 'View Dashboard',
                ]);

                // Handle referral bonus if applicable
                if ($user->referred_by && $payment->package->referral_bonus_percentage > 0) {
                    $referrer = User::where('referral_code', $user->referred_by)->first();
                    if ($referrer) {
                        $bonusAmount = ($payment->amount_paid * $payment->package->referral_bonus_percentage) / 100;

                        $referrer->increment('reward_balance', $bonusAmount);

                        Earning::create([
                            'user_id' => $referrer->id,
                            'amount' => $bonusAmount,
                            'type' => 'referral_bonus',
                            'description' => "Referral bonus from {$user->name}'s package purchase",
                            'reference_type' => 'user_package',
                            'reference_id' => $payment->id,
                        ]);

                        Notification::create([
                            'user_id' => $referrer->id,
                            'type' => 'referral_bonus',
                            'title' => 'Referral Bonus Earned!',
                            'message' => "You received a referral bonus of $" . number_format($bonusAmount, 2) . " from " . $user->name . "'s package purchase!",
                            'priority' => 'medium',
                        ]);
                    }
                }

            } elseif ($action === 'rejected') {
                $payment->payment_status = 'rejected';
                $payment->payment_notes = $notes;
                $payment->save();

                // Create notification
                Notification::create([
                    'user_id' => $payment->user_id,
                    'type' => 'payment_rejected',
                    'title' => 'Payment Rejected',
                    'message' => "Your payment for package '{$payment->package->name}' was rejected. Reason: {$notes}",
                    'priority' => 'high',
                ]);
            }

            // Log manager action
            ManagerLog::create([
                'manager_id' => Auth::id(),
                'action' => "Processed payment #{$payment->id} as {$action}",
                'reference_type' => 'user_package',
                'reference_id' => $payment->id,
                'details' => $notes,
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Payment processed successfully.']);
    }

    public function videos(Request $request)
    {
        $query = Video::with('category');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $videos = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('manager.videos', compact('videos', 'categories'));
    }

    public function storeVideo(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|url|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
            'reward_amount' => 'required|numeric|min:0',
            'min_watch_minutes' => 'required|integer|min:1',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'status' => 'required|in:draft,published,archived',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $video = Video::create(array_merge($validated, [
            'created_by' => Auth::id(),
            'is_active' => $request->boolean('is_active'),
            'view_count' => 0,
            'completion_count' => 0,
        ]));

        ManagerLog::create([
            'manager_id' => Auth::id(),
            'action' => "Created new video: {$video->title}",
            'reference_type' => 'video',
            'reference_id' => $video->id,
        ]);

        return back()->with('success', 'Video added successfully!');
    }

    public function updateVideo(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|url|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
            'reward_amount' => 'required|numeric|min:0',
            'min_watch_minutes' => 'required|integer|min:1',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'status' => 'required|in:draft,published,archived',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $video->update(array_merge($validated, [
            'is_active' => $request->boolean('is_active'),
        ]));

        ManagerLog::create([
            'manager_id' => Auth::id(),
            'action' => "Updated video: {$video->title}",
            'reference_type' => 'video',
            'reference_id' => $video->id,
        ]);

        return back()->with('success', 'Video updated successfully!');
    }

    public function destroyVideo(Video $video)
    {
        if ($video->thumbnail) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        $title = $video->title;
        $video->delete();

        ManagerLog::create([
            'manager_id' => Auth::id(),
            'action' => "Deleted video: {$title}",
            'reference_type' => 'video',
            'reference_id' => $video->id,
        ]);

        return back()->with('success', 'Video deleted successfully!');
    }

    public function videoAnalytics(Video $video)
    {
        $totalViews = $video->view_count;
        $totalCompletions = $video->completion_count;
        $totalRewardsPaid = $video->watchedVideos()->where('reward_granted', true)->sum('reward_amount');
        $averageWatchTime = $video->watchedVideos()->avg('watched_seconds');
        $watchedVideos = $video->watchedVideos()->with('user')->latest()->paginate(10);

        return view('manager.video-analytics', compact('video', 'totalViews', 'totalCompletions', 'totalRewardsPaid', 'averageWatchTime', 'watchedVideos'));
    }

    public function users(Request $request)
    {
        $query = User::where('role', 'user');

        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true)->where('is_banned', false);
            } elseif ($request->status == 'banned') {
                $query->where('is_banned', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false)->where('is_banned', false);
            }
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(10);
        $activeUsersCount = User::where('role', 'user')->where('is_active', true)->where('is_banned', false)->count();
        $bannedUsersCount = User::where('role', 'user')->where('is_banned', true)->count();
        $inactiveUsersCount = User::where('role', 'user')->where('is_active', false)->where('is_banned', false)->count();

        return view('manager.users', compact('users', 'activeUsersCount', 'bannedUsersCount', 'inactiveUsersCount'));
    }

    public function userDetails(User $user)
    {
        $user->load(['userPackages.package', 'watchedVideos.video', 'withdrawals', 'earnings', 'referrals.referred', 'locationLogs']);
        return view('manager.user-details', compact('user'));
    }

    public function banUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'ban_reason' => 'required|string|max:500',
        ]);

        $user->update([
            'is_banned' => true,
            'ban_reason' => $validated['ban_reason'],
            'banned_at' => now(),
            'banned_by' => Auth::id(),
        ]);

        // Create notification
        Notification::create([
            'user_id' => $user->id,
            'type' => 'account_banned',
            'title' => 'Account Banned',
            'message' => "Your account has been banned. Reason: {$validated['ban_reason']}",
            'priority' => 'critical',
        ]);

        ManagerLog::create([
            'manager_id' => Auth::id(),
            'action' => "Banned user: {$user->name}",
            'reference_type' => 'user',
            'reference_id' => $user->id,
            'details' => $validated['ban_reason'],
        ]);

        return back()->with('success', 'User banned successfully!');
    }

    public function unbanUser(User $user)
    {
        $user->update([
            'is_banned' => false,
            'ban_reason' => null,
            'banned_at' => null,
            'banned_by' => null,
        ]);

        // Create notification
        Notification::create([
            'user_id' => $user->id,
            'type' => 'account_unbanned',
            'title' => 'Account Unbanned',
            'message' => 'Your account has been unbanned. You can now access all features.',
            'priority' => 'high',
        ]);

        ManagerLog::create([
            'manager_id' => Auth::id(),
            'action' => "Unbanned user: {$user->name}",
            'reference_type' => 'user',
            'reference_id' => $user->id,
        ]);

        return back()->with('success', 'User unbanned successfully!');
    }

    public function packages()
    {
        $packages = Package::latest()->paginate(10);
        return view('manager.packages', compact('packages'));
    }

    public function categories()
    {
        $categories = Category::latest()->paginate(10);
        return view('manager.categories', compact('categories'));
    }

    public function withdrawals(Request $request)
    {
        $query = Withdrawal::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('user', function($userQ) use ($request) {
                    $userQ->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('email', 'like', '%' . $request->search . '%');
                })->orWhere('account_name', 'like', '%' . $request->search . '%')
                  ->orWhere('account_number', 'like', '%' . $request->search . '%');
            });
        }

        $withdrawals = $query->latest()->paginate(10);
        $pendingCount = Withdrawal::where('status', 'pending')->count();
        $approvedCount = Withdrawal::where('status', 'approved')->count();
        $rejectedCount = Withdrawal::where('status', 'rejected')->count();

        return view('manager.withdrawals', compact('withdrawals', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    public function processWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'manager_notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($withdrawal, $validated) {
            $withdrawal->update([
                'status' => $validated['status'],
                'manager_notes' => $validated['manager_notes'],
                'processed_at' => now(),
                'processed_by' => Auth::id(),
            ]);

            if ($validated['status'] === 'approved') {
                // Create notification
                Notification::create([
                    'user_id' => $withdrawal->user_id,
                    'type' => 'withdrawal_approved',
                    'title' => 'Withdrawal Approved!',
                    'message' => "Your withdrawal request of $" . number_format($withdrawal->amount, 2) . " has been approved and processed.",
                    'priority' => 'high',
                ]);
            } else {
                // Return funds to user balance
                $withdrawal->user->increment('reward_balance', $withdrawal->amount);

                // Create notification
                Notification::create([
                    'user_id' => $withdrawal->user_id,
                    'type' => 'withdrawal_rejected',
                    'title' => 'Withdrawal Rejected',
                    'message' => "Your withdrawal request of $" . number_format($withdrawal->amount, 2) . " was rejected. Reason: {$validated['manager_notes']}. Funds returned to your balance.",
                    'priority' => 'high',
                ]);
            }

            ManagerLog::create([
                'manager_id' => Auth::id(),
                'action' => "Processed withdrawal for user {$withdrawal->user->name}. Status: {$validated['status']}",
                'reference_type' => 'withdrawal',
                'reference_id' => $withdrawal->id,
                'details' => $validated['manager_notes'],
            ]);
        });

        return back()->with('success', 'Withdrawal processed successfully!');
    }

    public function analytics()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalVideos = Video::count();
        $totalEarnings = Earning::sum('amount');
        $totalWithdrawals = Withdrawal::where('status', 'approved')->sum('amount');
        $totalReferrals = User::whereNotNull('referred_by')->count();

        // Monthly trends (last 6 months)
        $monthlyUsers = User::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('role', 'user')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyEarnings = Earning::select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(amount) as total_amount'))
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('manager.analytics', compact(
            'totalUsers', 'totalVideos', 'totalEarnings', 'totalWithdrawals', 'totalReferrals',
            'monthlyUsers', 'monthlyEarnings'
        ));
    }


    public function reports(Request $request)
    {
        $reportType = $request->query('type', 'users');

        switch ($reportType) {
            case 'users':
                $data = User::where('role', 'user')->latest()->paginate(15);
                break;
            case 'videos':
                $data = Video::with('category')->latest()->paginate(15);
                break;
            case 'earnings':
                $data = Earning::with('user')->latest()->paginate(15);
                break;
            case 'withdrawals':
                $data = Withdrawal::with('user')->latest()->paginate(15);
                break;
            case 'packages':
                $data = UserPackage::with('user', 'package')->latest()->paginate(15);
                break;
            default:
                $data = collect();
                break;
        }

        return view('manager.reports', compact('reportType', 'data'));
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);
        return view('manager.notifications', compact('notifications'));
    }

    public function markNotificationAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['is_read' => true, 'read_at' => now()]);
        return back()->with('success', 'Notification marked as read.');
    }
}
