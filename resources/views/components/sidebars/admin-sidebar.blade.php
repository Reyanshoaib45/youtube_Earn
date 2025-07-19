<!-- Dashboard -->
<a href="{{ route('admin.dashboard') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-chart-line mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Dashboard
</a>

<!-- Users & Managers -->
<a href="{{ route('admin.users') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('admin.users*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-users-cog mr-3 {{ request()->routeIs('admin.users*') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Users & Managers
    <span class="ml-auto text-xs text-gray-500">{{ \App\Models\User::whereIn('role', ['user', 'manager'])->count() }}</span>
</a>

<!-- Transactions -->
<a href="{{ route('admin.transactions') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('admin.transactions') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-receipt mr-3 {{ request()->routeIs('admin.transactions') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Transactions
</a>

<!-- Manager Logs -->
<a href="{{ route('admin.manager.logs') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('admin.manager.logs') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-clipboard-list mr-3 {{ request()->routeIs('admin.manager.logs') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Manager Logs
</a>

<!-- Referral Settings -->
<a href="{{ route('admin.referral.settings') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('admin.referral.settings') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-handshake mr-3 {{ request()->routeIs('admin.referral.settings') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Referral Settings
</a>

<!-- System Settings -->
<a href="{{ route('admin.settings') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('admin.settings') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-cogs mr-3 {{ request()->routeIs('admin.settings') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    System Settings
</a>

<!-- Analytics -->
<a href="{{ route('admin.analytics') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('admin.analytics') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-chart-pie mr-3 {{ request()->routeIs('admin.analytics') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Analytics
</a>

<!-- System Info -->
<a href="{{ route('admin.system.info') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('admin.system.info') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-server mr-3 {{ request()->routeIs('admin.system.info') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    System Info
</a>

<!-- Divider -->
<div class="border-t border-gray-200 my-4"></div>

<!-- Platform Stats -->
<div class="px-4 py-3 bg-gray-50 rounded-2xl">
    <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Platform Stats</h4>
    <div class="space-y-2">
        @php
            $totalUsers = \App\Models\User::where('role', 'user')->count();
            $totalManagers = \App\Models\User::where('role', 'manager')->count();
            $totalEarnings = \App\Models\WatchedVideo::where('reward_granted', true)->sum('reward_amount');
            $totalWithdrawals = \App\Models\Withdrawal::where('status', 'approved')->sum('amount');
        @endphp
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Total Users</span>
            <span class="font-semibold text-gray-900">{{ number_format($totalUsers) }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Managers</span>
            <span class="font-semibold text-blue-600">{{ $totalManagers }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Total Earnings</span>
            <span class="font-semibold text-green-600">${{ number_format($totalEarnings, 2) }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Withdrawals</span>
            <span class="font-semibold text-red-600">${{ number_format($totalWithdrawals, 2) }}</span>
        </div>
    </div>
</div>

<!-- System Actions -->
<div class="px-4 py-3 bg-red-50 rounded-2xl mt-4">
    <h4 class="text-xs font-semibold text-red-600 uppercase tracking-wide mb-3">System Actions</h4>
    <div class="space-y-2">
        <button onclick="clearCache()" class="w-full text-left text-sm text-red-600 hover:text-red-800 py-1">
            <i class="fas fa-broom mr-2"></i>Clear Cache
        </button>
        <button onclick="backup()" class="w-full text-left text-sm text-red-600 hover:text-red-800 py-1">
            <i class="fas fa-download mr-2"></i>Backup System
        </button>
        <button onclick="maintenance()" class="w-full text-left text-sm text-red-600 hover:text-red-800 py-1">
            <i class="fas fa-tools mr-2"></i>Maintenance Mode
        </button>
    </div>
</div>
