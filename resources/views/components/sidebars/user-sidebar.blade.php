<!-- Dashboard -->
<a href="{{ route('user.dashboard') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('user.dashboard') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-home mr-3 {{ request()->routeIs('user.dashboard') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Dashboard
</a>

<!-- Packages -->
<a href="{{ route('user.packages') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('user.packages*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-box mr-3 {{ request()->routeIs('user.packages*') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Packages
    @if(!auth()->user()->activePackage)
        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1">!</span>
    @endif
</a>

<!-- Videos -->
<a href="{{ route('user.videos') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('user.videos*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-play-circle mr-3 {{ request()->routeIs('user.videos*') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Videos
    @if(auth()->user()->activePackage)
        <span class="ml-auto text-xs text-gray-500">
            {{ auth()->user()->watchedVideos()->where('is_completed', true)->count() }}
        </span>
    @endif
</a>

<!-- Earnings -->
<a href="{{ route('user.rewards') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('user.rewards') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-coins mr-3 {{ request()->routeIs('user.rewards') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Earnings
    <span class="ml-auto text-xs {{ request()->routeIs('user.rewards') ? 'text-blue-700' : 'text-green-600' }} font-semibold">
        ${{ number_format(auth()->user()->reward_balance, 2) }}
    </span>
</a>

<!-- Withdrawals -->
<a href="{{ route('user.withdrawals') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('user.withdrawals') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-wallet mr-3 {{ request()->routeIs('user.withdrawals') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Withdraw
    @if(auth()->user()->canWithdraw())
        <span class="ml-auto bg-green-500 text-white text-xs rounded-full px-2 py-1">
            <i class="fas fa-check"></i>
        </span>
    @endif
</a>

<!-- Referrals -->
<a href="{{ route('user.referrals') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('user.referrals') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-users mr-3 {{ request()->routeIs('user.referrals') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Referrals
    <span class="ml-auto text-xs {{ request()->routeIs('user.referrals') ? 'text-blue-700' : 'text-gray-500' }}">
        {{ auth()->user()->referredUsers()->count() }}
    </span>
</a>

<!-- Profile -->
<a href="{{ route('user.profile') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('user.profile') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-user mr-3 {{ request()->routeIs('user.profile') ? 'text-blue-700' : 'text-gray-400' }}"></i>
    Profile
</a>

<!-- Notifications -->
<a href="{{ route('user.notifications') }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('user.notifications') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
    <i class="fas fa-bell mr-3 {{ request()->routeIs('user.notifications') ? 'text-blue-700' : 'text-gray-400' }}"></i>
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
    <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Quick Stats</h4>
    <div class="space-y-2">
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Videos Watched</span>
            <span class="font-semibold text-gray-900">{{ auth()->user()->watchedVideos()->where('is_completed', true)->count() }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Total Earned</span>
            <span class="font-semibold text-green-600">${{ number_format(auth()->user()->getTotalEarnings(), 2) }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Referrals</span>
            <span class="font-semibold text-blue-600">{{ auth()->user()->referredUsers()->count() }}</span>
        </div>
    </div>
</div>
