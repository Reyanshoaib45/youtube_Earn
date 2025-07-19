<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\User;
use App\Models\Category;
use App\Models\Package;
use App\Models\Video;
use App\Models\WatchedVideo;
use App\Models\Withdrawal;
use App\Models\Setting;
use App\Models\ManagerLog;
use App\Models\Notification;
use App\Models\IpTracking;
use App\Models\LocationLog;
use App\Models\Earning;
use App\Models\UserPackage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Counts
        $totalUsers     = User::where('role', 'user')->count();
        $totalManagers  = User::where('role', 'manager')->count();
        $totalWithdrawals = Withdrawal::sum('amount');
        $totalVideos    = Video::count();

        // TODAY’s stats
        $today = Carbon::today();
        $platformStats['today'] = [
            'new_users'      => User::whereDate('created_at', $today)->count(),
            'videos_watched' => WatchedVideo::whereDate('created_at', $today)->count(),
            'earnings'       => Withdrawal::whereDate('requested_at', $today)->sum('amount'),
            'withdrawals'    => Withdrawal::whereDate('requested_at', $today)->count(),
        ];

        // WEEKLY stats (last 7 days total)
        $weekStart = Carbon::today()->subDays(6);
        $platformStats['week'] = [
            'new_users'      => User::whereBetween('created_at', [$weekStart, now()])->count(),
            'videos_watched' => WatchedVideo::whereBetween('created_at', [$weekStart, now()])->count(),
            'earnings'       => Withdrawal::whereBetween('requested_at', [$weekStart, now()])->sum('amount'),
            'withdrawals'    => Withdrawal::whereBetween('requested_at', [$weekStart, now()])->count(),
        ];

        // SYSTEM HEALTH (example for DB only—extend as needed)
        $dbStatus = DB::connection()->getPdo() ? 'healthy' : 'unhealthy';
        $systemHealth = [
            'database' => ['status' => $dbStatus],
        ];
        $totalReferralEarnings = Referral::where('bonus_paid', true)
            ->sum('bonus_amount');
        // RECENT MANAGER LOGS
        $recentManagerLogs = ManagerLog::with('manager')
            ->latest()
            ->take(5)
            ->get();

        // FLAGGED IP ADDRESSES
        $flaggedIps = IpTracking::where('is_flagged', true)
            ->latest('last_seen_at')
            ->take(5)
            ->get();

        // RECENT BANS
        $recentBans = User::where('is_banned', true)
            ->with('bannedBy')
            ->latest('banned_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalManagers',
            'totalWithdrawals',
            'totalVideos',
            'platformStats',
            'totalReferralEarnings',
            'systemHealth',
            'recentManagerLogs',
            'recentManagerLogs',
            'flaggedIps',
            'recentBans'
        ));
    }
//    public function dashboard()
//    {
//        $totalUsers = User::where('role', 'user')->count();
//        $totalManagers = User::where('role', 'manager')->count();
//        $totalVideos = Video::count();
//        $totalCategories = Category::count();
//        $totalPackages = Package::count();
//        $totalEarnings = Earning::sum('amount');
//        $totalWithdrawals = Withdrawal::where('status', 'approved')->sum('amount');
//        $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();
//        $bannedUsers = User::where('is_banned', true)->count();
//
//        $recentUsers = User::where('role', 'user')->latest()->take(5)->get();
//        $recentManagerLogs = ManagerLog::with('manager')->latest()->take(10)->get();
//
//        return view('admin.dashboard', compact(
//            'totalUsers', 'totalManagers', 'totalVideos', 'totalCategories', 'totalPackages',
//            'totalEarnings', 'totalWithdrawals', 'pendingWithdrawals', 'bannedUsers',
//            'recentUsers', 'recentManagerLogs'
//        ));
//    }
    public function analytics(Request $request)
    {
        // Core metrics
        $totalUsers      = User::count();
        $activeUsersWeek = User::where('last_login_at', '>=', Carbon::now()->subWeek())->count();
        $totalVideos     = Video::count();
        $videosThisMonth = WatchedVideo::whereBetween(
            'created_at',
            [Carbon::now()->startOfMonth(), Carbon::now()]
        )->count();
        $totalRevenue    = Withdrawal::where('status', 'approved')->sum('amount');

        // Build daily stats for the past 7 days
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $dailyStats[$dateKey] = [
                'new_users'      => User::whereDate('created_at', $date)->count(),
                'videos_watched' => WatchedVideo::whereDate('created_at', $date)->count(),
                'revenue'        => Withdrawal::whereDate('requested_at', $date)->sum('amount'),
            ];
        }

        return view('admin.analytics', compact(
            'totalUsers',
            'activeUsersWeek',
            'totalVideos',
            'videosThisMonth',
            'totalRevenue',
            'dailyStats'
        ));
    }
    public function transactions (Request $request)
    {
        // you can paginate or filter here
        $transactions = Withdrawal::with('user')
            ->orderByDesc('requested_at')
            ->paginate(15);

        return view('admin.transactions', compact('transactions'));
    }
    public function referral()
    {
        // Example: List all packages with their referral_bonus
        $packages = Package::orderBy('sort_order')->get(['id', 'name', 'referral_bonus']);

        return view('admin.referral_settings', compact('packages'));
    }

    public function users(Request $request)
    {
        // Base query for filters
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
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

        // Get paginated results
        $users = $query->latest()->paginate(15);

        // Build user statistics
        $userStats = [
            'total_users'      => User::count(),
            'total_admins'     => User::where('role', 'admin')->count(),
            'total_managers'   => User::where('role', 'manager')->count(),
            'total_regular'    => User::where('role', 'user')->count(),
            'active_users'     => User::where('is_active', true)->where('is_banned', false)->count(),
            'banned_users'     => User::where('is_banned', true)->count(),
            'inactive_users'   => User::where('is_active', false)->where('is_banned', false)->count(),
        ];

        return view('admin.users', compact('users', 'userStats'));
    }
//    public function show($id)
//    {
//        dd(ihi);
//        // Load the User (with any relationship you need)
//        $user = User::with('referredBy')->findOrFail($id);
//
//        // These two methods must exist in your User model:
//        //   ipTrackings()   → hasMany(IpTracking::class, 'ip_address', 'current_ip')
//        //   locationLogs()  → hasMany(LocationLog::class)
//        $ipTrackings  = $user->ipTrackings()->orderByDesc('last_seen_at')->get();
//        $locationLogs = $user->locationLogs()->orderByDesc('created_at')->get();
//
//        // Pass everything into the view:
//        return view('admin.user-details', compact(
//            'user',
//            'ipTrackings',
//            'locationLogs'
//        ));
//    }

    public function systeminfo()
    {
        $systemInfo = [
            'Laravel Version'   => App::version(),
            'PHP Version'       => PHP_VERSION,
            'Server OS'         => PHP_OS_FAMILY . ' ' . php_uname('r'),
            'Database Connection' => DB::connection()->getName(),
            'Cache Driver'      => Cache::getDefaultDriver(),
            'App Environment'   => config('app.env'),
            'Debug Mode'        => config('app.debug') ? 'On' : 'Off',
            'Storage Used'      => round(disk_total_space(storage_path()) / (1024 ** 3), 2) . ' GB total',
            'Storage Free'      => round(disk_free_space(storage_path()) / (1024 ** 3), 2) . ' GB free',
            'Memory Usage'      => round(memory_get_usage(true) / (1024 ** 2), 2) . ' MB',
            'Uptime'            => trim(shell_exec('uptime -p')) ?: 'N/A',
        ];

        return view('admin.system_info', compact('systemInfo'));
    }
    public function userDetails(User $user)
    {
        // Eager‑load everything you need:
        $user->load([
            'userPackages.package',
            'watchedVideos.video',
            'withdrawals',
            'earnings',
            'referrals.referred',
            'locationLogs',
            'ipTrackings',
        ]);

        // Extract the collections so blade can see them as standalone variables:
        $ipTrackings  = $user->ipTrackings;
        $locationLogs = $user->locationLogs;

        return view('admin.user-details', compact(
            'user',
            'ipTrackings',
            'locationLogs'
        ));
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

        Notification::create([
            'user_id' => $user->id,
            'type' => 'account_banned',
            'title' => 'Account Banned',
            'message' => "Your account has been banned by admin. Reason: {$validated['ban_reason']}",
            'priority' => 'critical',
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

        Notification::create([
            'user_id' => $user->id,
            'type' => 'account_unbanned',
            'title' => 'Account Unbanned',
            'message' => 'Your account has been unbanned by admin. You can now access all features.',
            'priority' => 'high',
        ]);

        return back()->with('success', 'User unbanned successfully!');
    }

    public function changeUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:user,manager,admin',
        ]);

        $oldRole = $user->role;
        $user->update(['role' => $validated['role']]);

        Notification::create([
            'user_id' => $user->id,
            'type' => 'role_changed',
            'title' => 'Role Changed',
            'message' => "Your role has been changed from {$oldRole} to {$validated['role']} by admin.",
            'priority' => 'high',
        ]);

        return back()->with('success', 'User role updated successfully!');
    }

    public function createManager(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $manager = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_number' => $validated['phone_number'],
            'role' => 'manager',
            'is_active' => true,
            'referral_code' => Str::upper(Str::random(8)),
        ]);

        return back()->with('success', 'Manager created successfully!');
    }

    public function ipTracking(Request $request)
    {
        $query = IpTracking::with('user');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('ip_address', 'like', '%' . $request->search . '%')
                  ->orWhere('country', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQ) use ($request) {
                      $userQ->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $ipTrackings = $query->latest()->paginate(20);

        return view('admin.ip-tracking', compact('ipTrackings'));
    }

    public function locationLogs(Request $request)
    {
        $query = LocationLog::with('user');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('ip_address', 'like', '%' . $request->search . '%')
                  ->orWhere('country', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQ) use ($request) {
                      $userQ->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $locationLogs = $query->latest()->paginate(20);

        return view('admin.location-logs', compact('locationLogs'));
    }

    public function categories()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Category::create(array_merge($validated, [
            'is_active' => $request->boolean('is_active'),
        ]));

        return back()->with('success', 'Category created successfully!');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category->update(array_merge($validated, [
            'is_active' => $request->boolean('is_active'),
        ]));

        return back()->with('success', 'Category updated successfully!');
    }

    public function deleteCategory(Category $category)
    {
        // Check if category has videos
        if ($category->videos()->count() > 0) {
            return back()->with('error', 'Cannot delete category that has videos.');
        }

        $category->delete();
        return back()->with('success', 'Category deleted successfully!');
    }

    public function packages()
    {
        $packages = Package::latest()->paginate(10);
        return view('admin.packages', compact('packages'));
    }

    public function storePackage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'validity_days' => 'required|integer|min:1',
            'video_limit' => 'nullable|integer|min:0',
            'daily_video_limit' => 'nullable|integer|min:0',
            'referral_bonus_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        Package::create(array_merge($validated, [
            'is_active' => $request->boolean('is_active'),
        ]));

        return back()->with('success', 'Package created successfully!');
    }

    public function updatePackage(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'validity_days' => 'required|integer|min:1',
            'video_limit' => 'nullable|integer|min:0',
            'daily_video_limit' => 'nullable|integer|min:0',
            'referral_bonus_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $package->update(array_merge($validated, [
            'is_active' => $request->boolean('is_active'),
        ]));

        return back()->with('success', 'Package updated successfully!');
    }

    public function deletePackage(Package $package)
    {
        // Check if package has active users
        if ($package->userPackages()->where('is_active', true)->count() > 0) {
            return back()->with('error', 'Cannot delete package that has active users.');
        }

        $package->delete();
        return back()->with('success', 'Package deleted successfully!');
    }

    public function videos()
    {
        $videos = Video::with('category')->latest()->paginate(15);
        $categories = Category::all();
        return view('admin.videos', compact('videos', 'categories'));
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

        Video::create(array_merge($validated, [
            'created_by' => Auth::id(),
            'is_active' => $request->boolean('is_active'),
            'view_count' => 0,
            'completion_count' => 0,
        ]));

        return back()->with('success', 'Video created successfully!');
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

        return back()->with('success', 'Video updated successfully!');
    }

    public function destroyVideo(Video $video)
    {
        if ($video->thumbnail) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        $video->delete();
        return back()->with('success', 'Video deleted successfully!');
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

        $withdrawals = $query->latest()->paginate(15);

        return view('admin.withdrawals', compact('withdrawals'));
    }

    public function processWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($withdrawal, $validated) {
            $withdrawal->update([
                'status' => $validated['status'],
                'admin_notes' => $validated['admin_notes'],
                'processed_at' => now(),
                'processed_by' => Auth::id(),
            ]);

            if ($validated['status'] === 'approved') {
                Notification::create([
                    'user_id' => $withdrawal->user_id,
                    'type' => 'withdrawal_approved',
                    'title' => 'Withdrawal Approved by Admin!',
                    'message' => "Your withdrawal request of $" . number_format($withdrawal->amount, 2) . " has been approved and processed by admin.",
                    'priority' => 'high',
                ]);
            } else {
                $withdrawal->user->increment('reward_balance', $withdrawal->amount);

                Notification::create([
                    'user_id' => $withdrawal->user_id,
                    'type' => 'withdrawal_rejected',
                    'title' => 'Withdrawal Rejected by Admin',
                    'message' => "Your withdrawal request of $" . number_format($withdrawal->amount, 2) . " was rejected by admin. Reason: {$validated['admin_notes']}. Funds returned to your balance.",
                    'priority' => 'high',
                ]);
            }
        });

        return back()->with('success', 'Withdrawal processed successfully!');
    }

    public function settings()
    {
        $settings = Setting::pluck('value', 'key');
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'required|email',
            'min_withdrawal_amount' => 'required|numeric|min:1',
            'withdrawal_fee_percentage' => 'required|numeric|min:0|max:100',
            'referral_bonus_percentage' => 'required|numeric|min:0|max:100',
            'daily_video_limit' => 'required|integer|min:1',
            'maintenance_mode' => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Settings updated successfully!');
    }

    public function managerLogs(Request $request)
    {
        $query = ManagerLog::with('manager');

        if ($request->filled('manager_id')) {
            $query->where('manager_id', $request->manager_id);
        }
        if ($request->filled('search')) {
            $query->where('action', 'like', '%' . $request->search . '%');
        }

        $managerLogs = $query->latest()->paginate(20);
        $managers = User::where('role', 'manager')->get();

        return view('admin.manager-logs', compact('managerLogs', 'managers'));
    }

    public function reports()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalManagers = User::where('role', 'manager')->count();
        $totalVideos = Video::count();
        $totalEarnings = Earning::sum('amount');
        $totalWithdrawals = Withdrawal::where('status', 'approved')->sum('amount');
        $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();

        // Monthly data for charts
        $monthlyUsers = User::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                            ->where('role', 'user')
                            ->where('created_at', '>=', now()->subMonths(12))
                            ->groupBy('month')
                            ->orderBy('month')
                            ->get();

        $monthlyEarnings = Earning::select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(amount) as total'))
                                  ->where('created_at', '>=', now()->subMonths(12))
                                  ->groupBy('month')
                                  ->orderBy('month')
                                  ->get();

        return view('admin.reports', compact(
            'totalUsers', 'totalManagers', 'totalVideos', 'totalEarnings',
            'totalWithdrawals', 'pendingWithdrawals', 'monthlyUsers', 'monthlyEarnings'
        ));
    }

    public function notifications()
    {
        $notifications = Notification::with('user')->latest()->paginate(20);
        return view('admin.notifications', compact('notifications'));
    }

    public function sendNotification(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high,critical',
            'target_type' => 'required|in:all,users,managers,specific',
            'target_user_id' => 'required_if:target_type,specific|exists:users,id',
            'action_url' => 'nullable|url',
            'action_text' => 'nullable|string|max:50',
        ]);

        $users = collect();

        switch ($validated['target_type']) {
            case 'all':
                $users = User::all();
                break;
            case 'users':
                $users = User::where('role', 'user')->get();
                break;
            case 'managers':
                $users = User::where('role', 'manager')->get();
                break;
            case 'specific':
                $users = User::where('id', $validated['target_user_id'])->get();
                break;
        }

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'admin_announcement',
                'title' => $validated['title'],
                'message' => $validated['message'],
                'priority' => $validated['priority'],
                'action_url' => $validated['action_url'],
                'action_text' => $validated['action_text'],
            ]);
        }

        return back()->with('success', 'Notification sent to ' . $users->count() . ' users successfully!');
    }

    public function deleteNotification(Notification $notification)
    {
        $notification->delete();
        return back()->with('success', 'Notification deleted successfully!');
    }
}
