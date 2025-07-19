<!-- Dashboard -->
<a href="{{ route('manager.dashboard') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('manager.dashboard') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-tachometer-alt mr-3 {{ request()->routeIs('manager.dashboard') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Dashboard
</a>

<!-- Payments -->
<a href="{{ route('manager.payments') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('manager.payments') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-credit-card mr-3 {{ request()->routeIs('manager.payments') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Payments
    @php
        $pendingPayments = \App\Models\UserPackage::where('payment_status', 'pending')->count();
    @endphp
    @if($pendingPayments > 0)
        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1">
            {{ $pendingPayments }}
        </span>
    @endif
</a>

<!-- Packages -->
<a href="{{ route('manager.packages') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('manager.packages') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-box mr-3 {{ request()->routeIs('manager.packages') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Packages
    <span class="ml-auto text-xs text-gray-500">{{ \App\Models\Package::count() }}</span>
</a>

<!-- Videos -->
<a href="{{ route('manager.videos') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('manager.videos*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-video mr-3 {{ request()->routeIs('manager.videos*') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Videos
    <span class="ml-auto text-xs text-gray-500">{{ \App\Models\Video::count() }}</span>
</a>

<!-- Users -->
<a href="{{ route('manager.users') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('manager.users*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-users mr-3 {{ request()->routeIs('manager.users*') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Users
    <span class="ml-auto text-xs text-gray-500">{{ \App\Models\User::where('role', 'user')->count() }}</span>
</a>

<!-- Withdrawals -->
<a href="{{ route('manager.withdrawals') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('manager.withdrawals') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-money-check-alt mr-3 {{ request()->routeIs('manager.withdrawals') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Withdrawals
    @php
        $pendingWithdrawals = \App\Models\Withdrawal::where('status', 'pending')->count();
    @endphp
    @if($pendingWithdrawals > 0)
        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1">
            {{ $pendingWithdrawals }}
        </span>
    @endif
</a>

<!-- Categories -->
<a href="{{ route('manager.categories') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('manager.categories') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-tags mr-3 {{ request()->routeIs('manager.categories') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Categories
</a>

<!-- Analytics -->
<a href="{{ route('manager.analytics') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('manager.analytics') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-chart-bar mr-3 {{ request()->routeIs('manager.analytics') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Analytics
</a>

<!-- Reports -->
<a href="{{ route('manager.reports') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('manager.reports') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-file-alt mr-3 {{ request()->routeIs('manager.reports') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Reports
</a>

<!-- Notifications -->
<a href="{{ route('manager.notifications') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('manager.notifications') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-bell mr-3 {{ request()->routeIs('manager.notifications') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Notifications
    @if(auth()->user()->notifications()->where('is_read', false)->count() > 0)
        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1">
            {{ auth()->user()->notifications()->where('is_read', false)->count() }}
        </span>
    @endif
</a>

<!-- Divider -->
<div class="border-t border-gray-200 my-4"></div>

<!-- Quick Stats -->
<div class="px-4 py-3 bg-gray-50 rounded-2xl">
    <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Today's Stats</h4>
    <div class="space-y-2">
        @php
            $todayUsers = \App\Models\User::where('role', 'user')->whereDate('created_at', today())->count();
            $todayEarnings = \App\Models\WatchedVideo::whereDate('completed_at', today())->where('reward_granted', true)->sum('reward_amount');
            $todayVideos = \App\Models\WatchedVideo::whereDate('completed_at', today())->where('is_completed', true)->count();
        @endphp
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">New Users</span>
            <span class="font-semibold text-gray-900">{{ $todayUsers }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Earnings</span>
            <span class="font-semibold text-green-600">${{ number_format($todayEarnings, 2) }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Videos Watched</span>
            <span class="font-semibold text-blue-600">{{ $todayVideos }}</span>
        </div>
    </div>
</div>
