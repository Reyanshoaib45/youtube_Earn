@extends('layouts.app')

@section('title', 'Manager Dashboard')
@section('page-title', 'Manager Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="mt-2 opacity-90">Here's what's happening with your platform today</p>
                <div class="mt-4 flex items-center space-x-4 text-sm opacity-80">
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        {{ auth()->user()->getCurrentLocation() }}
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-1"></i>
                        {{ now()->format('M d, Y - H:i') }}
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-tachometer-alt text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-2xl">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>{{ $monthlyStats['new_users'] }} this month
                    </p>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-2xl">
                    <i class="fas fa-user-check text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($activeUsers) }}</p>
                    <p class="text-xs text-gray-600 mt-1">
                        {{ $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0 }}% of total
                    </p>
                </div>
            </div>
        </div>

        <!-- Today's Earnings -->
        <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-2xl">
                    <i class="fas fa-coins text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Today's Earnings</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($todayEarnings, 2) }}</p>
                    <p class="text-xs text-blue-600 mt-1">
                        <i class="fas fa-chart-line mr-1"></i>{{ $monthlyStats['videos_watched'] }} videos watched
                    </p>
                </div>
            </div>
        </div>

        <!-- Pending Actions -->
        <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-2xl">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Actions</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingWithdrawals + \App\Models\UserPackage::where('payment_status', 'pending')->count() }}</p>
                    <p class="text-xs text-red-600 mt-1">
                        {{ $pendingWithdrawals }} withdrawals, {{ \App\Models\UserPackage::where('payment_status', 'pending')->count() }} payments
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>Quick Actions
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('manager.payments') }}" 
               class="flex flex-col items-center p-4 bg-blue-50 rounded-2xl hover:bg-blue-100 transition-colors group">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-blue-200">
                    <i class="fas fa-credit-card text-blue-600"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Review Payments</span>
                @if(\App\Models\UserPackage::where('payment_status', 'pending')->count() > 0)
                    <span class="mt-1 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                        {{ \App\Models\UserPackage::where('payment_status', 'pending')->count() }}
                    </span>
                @endif
            </a>

            <a href="{{ route('manager.withdrawals') }}" 
               class="flex flex-col items-center p-4 bg-green-50 rounded-2xl hover:bg-green-100 transition-colors group">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-green-200">
                    <i class="fas fa-money-check-alt text-green-600"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Process Withdrawals</span>
                @if($pendingWithdrawals > 0)
                    <span class="mt-1 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                        {{ $pendingWithdrawals }}
                    </span>
                @endif
            </a>

            <a href="{{ route('manager.videos') }}" 
               class="flex flex-col items-center p-4 bg-purple-50 rounded-2xl hover:bg-purple-100 transition-colors group">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-purple-200">
                    <i class="fas fa-video text-purple-600"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Manage Videos</span>
                <span class="text-xs text-gray-500 mt-1">{{ $totalVideos }} total</span>
            </a>

            <a href="{{ route('manager.users') }}" 
               class="flex flex-col items-center p-4 bg-orange-50 rounded-2xl hover:bg-orange-100 transition-colors group">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-orange-200">
                    <i class="fas fa-users text-orange-600"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Manage Users</span>
                <span class="text-xs text-gray-500 mt-1">{{ $totalUsers }} users</span>
            </a>

            <a href="{{ route('manager.packages') }}" 
               class="flex flex-col items-center p-4 bg-indigo-50 rounded-2xl hover:bg-indigo-100 transition-colors group">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-indigo-200">
                    <i class="fas fa-box text-indigo-600"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Manage Packages</span>
                <span class="text-xs text-gray-500 mt-1">{{ $totalPackages }} packages</span>
            </a>

            <a href="{{ route('manager.analytics') }}" 
               class="flex flex-col items-center p-4 bg-pink-50 rounded-2xl hover:bg-pink-100 transition-colors group">
                <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-pink-200">
                    <i class="fas fa-chart-bar text-pink-600"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">View Analytics</span>
                <span class="text-xs text-gray-500 mt-1">Reports</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity & Notifications -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">
                    <i class="fas fa-user-plus text-blue-600 mr-2"></i>Recent Users
                </h2>
                <a href="{{ route('manager.users') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
            </div>
            
            <div class="space-y-4">
                @forelse($recentUsers as $user)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-medium text-sm">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                @if($user->current_location)
                                    <p class="text-xs text-gray-400">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $user->current_location }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            @if($user->is_banned)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-ban mr-1"></i>Banned
                                </span>
                            @elseif($user->activePackage)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>No Package
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-users text-gray-300 text-3xl mb-4"></i>
                        <p class="text-gray-500">No recent users</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Notifications -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">
                    <i class="fas fa-bell text-yellow-500 mr-2"></i>Recent Notifications
                </h2>
                <a href="{{ route('manager.notifications') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
            </div>
            
            <div class="space-y-4">
                @forelse($notifications as $notification)
                    <div class="flex items-start space-x-3 p-3 {{ !$notification->is_read ? 'bg-blue-50' : 'bg-gray-50' }} rounded-2xl hover:bg-gray-100 transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-bell text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($notification->message, 60) }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!$notification->is_read)
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-bell-slash text-gray-300 text-3xl mb-4"></i>
                        <p class="text-gray-500">No recent notifications</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Withdrawals -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">
                <i class="fas fa-money-check-alt text-green-600 mr-2"></i>Recent Withdrawal Requests
            </h2>
            <a href="{{ route('manager.withdrawals') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
        </div>
        
        @if($recentWithdrawals->isEmpty())
            <div class="text-center py-8">
                <i class="fas fa-wallet text-gray-300 text-3xl mb-4"></i>
                <p class="text-gray-500">No recent withdrawal requests</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-medium text-gray-900">User</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Amount</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Method</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Status</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Date</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recentWithdrawals as $withdrawal)
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white font-medium text-xs">{{ substr($withdrawal->user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $withdrawal->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $withdrawal->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="font-semibold text-gray-900">${{ number_format($withdrawal->amount, 2) }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="capitalize text-sm text-gray-600">{{ str_replace('_', ' ', $withdrawal->method) }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                        {{ $withdrawal->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($withdrawal->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($withdrawal->status === 'processing' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ ucfirst($withdrawal->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div>
                                        <p class="text-sm text-gray-900">{{ $withdrawal->requested_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $withdrawal->requested_at->format('h:i A') }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    @if($withdrawal->status === 'pending')
                                        <a href="{{ route('manager.withdrawals') }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Review
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Platform Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Monthly Growth -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-line text-green-600 mr-2"></i>Monthly Growth
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">New Users</span>
                    <span class="font-semibold text-green-600">+{{ $monthlyStats['new_users'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Videos Watched</span>
                    <span class="font-semibold text-blue-600">{{ number_format($monthlyStats['videos_watched']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Earnings</span>
                    <span class="font-semibold text-purple-600">${{ number_format($monthlyStats['total_earnings'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Withdrawals</span>
                    <span class="font-semibold text-red-600">{{ $monthlyStats['withdrawals_processed'] }}</span>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-trophy text-yellow-600 mr-2"></i>Top Performers
            </h3>
            <div class="space-y-3">
                @php
                    $topUsers = \App\Models\User::where('role', 'user')
                        ->orderBy('reward_balance', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                @forelse($topUsers as $index => $user)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                        </div>
                        <span class="text-sm font-semibold text-green-600">${{ number_format($user->reward_balance, 2) }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No data available</p>
                @endforelse
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-server text-blue-600 mr-2"></i>System Status
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Database</span>
                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>Healthy
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Storage</span>
                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>Normal
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Cache</span>
                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>Active
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Queue</span>
                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-1"></i>Processing
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh dashboard every 5 minutes
setInterval(function() {
    location.reload();
}, 300000);

// Show loading state for quick actions
document.querySelectorAll('a[href*="manager"]').forEach(link => {
    link.addEventListener('click', function() {
        showLoading(true);
    });
});
</script>
@endsection
