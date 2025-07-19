@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-red-600 to-pink-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold">Admin Control Center</h1>
                    <p class="mt-2 opacity-90">Complete platform overview and management</p>
                    <div class="mt-4 flex items-center space-x-4 text-sm opacity-80">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Super Admin Access
                        </div>
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
                        <i class="fas fa-crown text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Platform Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
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
                            <i class="fas fa-arrow-up mr-1"></i>{{ $platformStats['today']['new_users'] }} today
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Managers -->
            <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-2xl">
                        <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Managers</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalManagers) }}</p>
                        <p class="text-xs text-gray-600 mt-1">Active staff</p>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-2xl">
                        <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Earnings</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($totalWithdrawals, 2) }}</p>
                        <p class="text-xs text-blue-600 mt-1">
                            <i
                                class="fas fa-chart-line mr-1"></i>${{ number_format($platformStats['today']['earnings'], 2) }}
                            today
                        </p>
                    </div>
                </div>
            </div>

            <!-- Videos & Content -->
            <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-2xl">
                        <i class="fas fa-video text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Videos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalVideos) }}</p>
                        <p class="text-xs text-purple-600 mt-1">
                            {{ $platformStats['today']['videos_watched'] }} watched today
                        </p>
                    </div>
                </div>
            </div>

            <!-- System Health -->
            <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div
                        class="p-3 bg-{{ $systemHealth['database']['status'] === 'healthy' ? 'green' : 'red' }}-100 rounded-2xl">
                        <i
                            class="fas fa-server text-{{ $systemHealth['database']['status'] === 'healthy' ? 'green' : 'red' }}-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">System Health</p>
                        <p
                            class="text-lg font-bold text-{{ $systemHealth['database']['status'] === 'healthy' ? 'green' : 'red' }}-600">
                            {{ ucfirst($systemHealth['database']['status']) }}
                        </p>
                        <p class="text-xs text-gray-600 mt-1">All systems operational</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Admin Actions -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">
                <i class="fas fa-tools text-red-500 mr-2"></i>Admin Controls
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <a href="{{ route('admin.users') }}"
                    class="flex flex-col items-center p-4 bg-blue-50 rounded-2xl hover:bg-blue-100 transition-colors group">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-blue-200">
                        <i class="fas fa-users-cog text-blue-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">User Management</span>
                    <span class="text-xs text-gray-500 mt-1">{{ $totalUsers + $totalManagers }} total</span>
                </a>

                <a href="{{ route('admin.transactions') }}"
                    class="flex flex-col items-center p-4 bg-green-50 rounded-2xl hover:bg-green-100 transition-colors group">
                    <div
                        class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-green-200">
                        <i class="fas fa-receipt text-green-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Transactions</span>
                    <span class="text-xs text-gray-500 mt-1">Financial overview</span>
                </a>

                <a href="{{ route('admin.manager.logs') }}"
                    class="flex flex-col items-center p-4 bg-purple-50 rounded-2xl hover:bg-purple-100 transition-colors group">
                    <div
                        class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-purple-200">
                        <i class="fas fa-clipboard-list text-purple-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Manager Logs</span>
                    <span class="text-xs text-gray-500 mt-1">Activity tracking</span>
                </a>

                <a href="{{ route('admin.referral.settings') }}"
                    class="flex flex-col items-center p-4 bg-yellow-50 rounded-2xl hover:bg-yellow-100 transition-colors group">
                    <div
                        class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-yellow-200">
                        <i class="fas fa-handshake text-yellow-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Referral System</span>
                    <span class="text-xs text-gray-500 mt-1">${{ number_format($totalReferralEarnings, 2) }} paid</span>
                </a>

                <a href="{{ route('admin.analytics') }}"
                    class="flex flex-col items-center p-4 bg-indigo-50 rounded-2xl hover:bg-indigo-100 transition-colors group">
                    <div
                        class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-indigo-200">
                        <i class="fas fa-chart-pie text-indigo-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Analytics</span>
                    <span class="text-xs text-gray-500 mt-1">Deep insights</span>
                </a>

                <a href="{{ route('admin.settings') }}"
                    class="flex flex-col items-center p-4 bg-red-50 rounded-2xl hover:bg-red-100 transition-colors group">
                    <div
                        class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-red-200">
                        <i class="fas fa-cogs text-red-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">System Settings</span>
                    <span class="text-xs text-gray-500 mt-1">Configuration</span>
                </a>
            </div>
        </div>

        <!-- Platform Statistics Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Today's Activity -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-calendar-day text-blue-600 mr-2"></i>Today's Activity
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-xl">
                        <div class="flex items-center">
                            <i class="fas fa-user-plus text-blue-600 mr-2"></i>
                            <span class="text-sm font-medium">New Users</span>
                        </div>
                        <span class="font-bold text-blue-600">{{ $platformStats['today']['new_users'] }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-xl">
                        <div class="flex items-center">
                            <i class="fas fa-play text-green-600 mr-2"></i>
                            <span class="text-sm font-medium">Videos Watched</span>
                        </div>
                        <span class="font-bold text-green-600">{{ $platformStats['today']['videos_watched'] }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-purple-50 rounded-xl">
                        <div class="flex items-center">
                            <i class="fas fa-coins text-purple-600 mr-2"></i>
                            <span class="text-sm font-medium">Earnings</span>
                        </div>
                        <span
                            class="font-bold text-purple-600">${{ number_format($platformStats['today']['earnings'], 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-orange-50 rounded-xl">
                        <div class="flex items-center">
                            <i class="fas fa-money-check-alt text-orange-600 mr-2"></i>
                            <span class="text-sm font-medium">Withdrawals</span>
                        </div>
                        <span class="font-bold text-orange-600">{{ $platformStats['today']['withdrawals'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Weekly Trends -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>Weekly Trends
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm font-medium text-gray-700">New Users</span>
                        <div class="flex items-center">
                            <span class="font-bold text-gray-900 mr-2">{{ $platformStats['week']['new_users'] }}</span>
                            <i class="fas fa-arrow-up text-green-500 text-sm"></i>
                        </div>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm font-medium text-gray-700">Videos Watched</span>
                        <div class="flex items-center">
                            <span
                                class="font-bold text-gray-900 mr-2">{{ number_format($platformStats['week']['videos_watched']) }}</span>
                            <i class="fas fa-arrow-up text-green-500 text-sm"></i>
                        </div>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm font-medium text-gray-700">Total Earnings</span>
                        <div class="flex items-center">
                            <span
                                class="font-bold text-gray-900 mr-2">${{ number_format($platformStats['week']['earnings'], 2) }}</span>
                            <i class="fas fa-arrow-up text-green-500 text-sm"></i>
                        </div>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm font-medium text-gray-700">Withdrawals</span>
                        <div class="flex items-center">
                            <span class="font-bold text-gray-900 mr-2">{{ $platformStats['week']['withdrawals'] }}</span>
                            <i class="fas fa-arrow-up text-green-500 text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-server text-red-600 mr-2"></i>System Status
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-xl">
                        <div class="flex items-center">
                            <i class="fas fa-database text-green-600 mr-2"></i>
                            <span class="text-sm font-medium">Database</span>
                        </div>
                        <span
                            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Healthy
                        </span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-xl">
                        <div class="flex items-center">
                            <i class="fas fa-hdd text-green-600 mr-2"></i>
                            <span class="text-sm font-medium">Storage</span>
                        </div>
                        <span
                            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Normal
                        </span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-xl">
                        <div class="flex items-center">
                            <i class="fas fa-memory text-green-600 mr-2"></i>
                            <span class="text-sm font-medium">Cache</span>
                        </div>
                        <span
                            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Active
                        </span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-xl">
                        <div class="flex items-center">
                            <i class="fas fa-tasks text-yellow-600 mr-2"></i>
                            <span class="text-sm font-medium">Queue</span>
                        </div>
                        <span
                            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>Processing
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Manager Activity -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">
                    <i class="fas fa-history text-purple-600 mr-2"></i>Recent Manager Activity
                </h2>
                <a href="{{ route('admin.manager.logs') }}" class="text-sm text-blue-600 hover:text-blue-800">View all
                    logs</a>
            </div>

            @if ($recentManagerLogs->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-clipboard-list text-gray-300 text-3xl mb-4"></i>
                    <p class="text-gray-500">No recent manager activity</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-medium text-gray-900">Manager</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-900">Action</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-900">Description</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-900">Time</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-900">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($recentManagerLogs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center mr-3">
                                                <span
                                                    class="text-white font-medium text-xs">{{ substr($log->manager->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $log->manager->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $log->manager->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $log->severity === 'high'
                                            ? 'bg-red-100 text-red-800'
                                            : ($log->severity === 'warning'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-blue-100 text-blue-800') }}">
                                            {{ str_replace('_', ' ', ucfirst($log->action_type)) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <p class="text-sm text-gray-900">{{ Str::limit($log->description, 60) }}</p>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div>
                                            <p class="text-sm text-gray-900">{{ $log->created_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $log->created_at->format('h:i A') }}</p>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="text-sm text-gray-600 font-mono">{{ $log->ip_address }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Platform Health & Alerts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Flagged IPs -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-flag text-red-600 mr-2"></i>Flagged IP Addresses
                </h3>
                @php
                    $flaggedIps = \App\Models\IpTracking::where('is_flagged', true)->latest()->take(5)->get();
                @endphp
                <div class="space-y-3">
                    @forelse($flaggedIps as $ip)
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl">
                            <div>
                                <p class="font-medium text-gray-900">{{ $ip->ip_address }}</p>
                                <p class="text-xs text-gray-600">{{ $ip->account_count }} accounts</p>
                            </div>
                            <div class="text-right">
                                <span
                                    class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Flagged
                                </span>
                                <p class="text-xs text-gray-500 mt-1">{{ $ip->last_seen_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-shield-alt text-green-500 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-600">No flagged IPs</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Bans -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-ban text-red-600 mr-2"></i>Recent Account Bans
                </h3>
                @php
                    $recentBans = \App\Models\User::where('is_banned', true)
                        ->with('bannedBy')
                        ->latest('banned_at')
                        ->take(5)
                        ->get();
                @endphp
                <div class="space-y-3">
                    @forelse($recentBans as $bannedUser)
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl">
                            <div>
                                <p class="font-medium text-gray-900">{{ $bannedUser->name }}</p>
                                <p class="text-xs text-gray-600">{{ Str::limit($bannedUser->ban_reason, 40) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Banned by {{ $bannedUser->bannedBy->name ?? 'System' }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $bannedUser->banned_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-user-check text-green-500 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-600">No recent bans</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- System Actions -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">
                <i class="fas fa-tools text-red-600 mr-2"></i>System Actions
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button onclick="clearCache()"
                    class="flex flex-col items-center p-4 bg-yellow-50 rounded-2xl hover:bg-yellow-100 transition-colors group">
                    <div
                        class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-yellow-200">
                        <i class="fas fa-broom text-yellow-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Clear Cache</span>
                </button>

                <button onclick="backup()"
                    class="flex flex-col items-center p-4 bg-blue-50 rounded-2xl hover:bg-blue-100 transition-colors group">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-blue-200">
                        <i class="fas fa-download text-blue-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Backup System</span>
                </button>

                <button onclick="maintenance()"
                    class="flex flex-col items-center p-4 bg-red-50 rounded-2xl hover:bg-red-100 transition-colors group">
                    <div
                        class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-red-200">
                        <i class="fas fa-tools text-red-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Maintenance Mode</span>
                </button>

                <a href="{{ route('admin.system.info') }}"
                    class="flex flex-col items-center p-4 bg-green-50 rounded-2xl hover:bg-green-100 transition-colors group">
                    <div
                        class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-green-200">
                        <i class="fas fa-info-circle text-green-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">System Info</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        function clearCache() {
            if (confirm('Are you sure you want to clear the system cache?')) {
                showLoading(true);
                fetch('/admin/clear-cache', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        showLoading(false);
                        if (data.success) {
                            showToast('Cache cleared successfully!', 'success');
                        } else {
                            showToast('Error clearing cache', 'error');
                        }
                    })
                    .catch(error => {
                        showLoading(false);
                        showToast('Error clearing cache', 'error');
                    });
            }
        }

        function backup() {
            if (confirm('Are you sure you want to start a system backup?')) {
                showLoading(true);
                fetch('/admin/backup', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        showLoading(false);
                        if (data.success) {
                            showToast('Backup initiated successfully!', 'success');
                        } else {
                            showToast('Error starting backup', 'error');
                        }
                    })
                    .catch(error => {
                        showLoading(false);
                        showToast('Error starting backup', 'error');
                    });
            }
        }

        function maintenance() {
            const action = confirm('Enable maintenance mode?') ? 'enable' :
                confirm('Disable maintenance mode?') ? 'disable' : null;

            if (action) {
                showLoading(true);
                fetch('/admin/maintenance', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: action
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        showLoading(false);
                        if (data.success) {
                            showToast(`Maintenance mode ${action}d successfully!`, 'success');
                        } else {
                            showToast('Error changing maintenance mode', 'error');
                        }
                    })
                    .catch(error => {
                        showLoading(false);
                        showToast('Error changing maintenance mode', 'error');
                    });
            }
        }

        // Auto-refresh dashboard every 10 minutes
        setInterval(function() {
            location.reload();
        }, 600000);
    </script>
@endsection
