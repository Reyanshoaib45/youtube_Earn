<div class="flex flex-col h-full">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 px-4 bg-gradient-to-r from-blue-600 to-purple-600">
        <h1 class="text-xl font-bold text-white">VideoRewards</h1>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2">
        @if(auth()->user()->role === 'user')
            <a href="{{ route('user.dashboard') }}" 
               class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('user.dashboard') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-home mr-3"></i>
                Dashboard
            </a>
            <a href="{{ route('user.packages') }}" 
               class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('user.packages') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-box mr-3"></i>
                Packages
            </a>
            <a href="{{ route('user.videos') }}" 
               class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('user.videos*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-play-circle mr-3"></i>
                Videos
            </a>
            <a href="{{ route('user.rewards') }}" 
               class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('user.rewards') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-coins mr-3"></i>
                Earnings
            </a>
            <a href="{{ route('user.withdrawals') }}" 
               class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('user.withdrawals') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-wallet mr-3"></i>
                Withdraw
            </a>
        @elseif(auth()->user()->role === 'manager')
            <a href="{{ route('manager.dashboard') }}" 
               class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('manager.dashboard') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            <a href="{{ route('manager.packages') }}" 
               class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('manager.packages') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-box mr-3"></i>
                Packages
            </a>
            <a href="{{ route('manager.videos') }}" 
               class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('manager.videos') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-video mr-3"></i>
                Videos
            </a>
            <a href="{{ route('manager.users') }}" 
               class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('manager.users') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-users mr-3"></i>
                Users
            </a>
            <a href="{{ route('manager.withdrawals') }}" 
               class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('manager.withdrawals') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-money-check-alt mr-3"></i>
                Withdrawals
                @if(\App\Models\Withdrawal::where('status', 'pending')->count() > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1">
                        {{ \App\Models\Withdrawal::where('status', 'pending')->count() }}
                    </span>
                @endif
            </a>
        @elseif(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-chart-line mr-3"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.users') }}" 
               class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('admin.users') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-users-cog mr-3"></i>
                Users & Managers
            </a>
            <a href="{{ route('admin.transactions') }}" 
               class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-colors {{ request()->routeIs('admin.transactions') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-receipt mr-3"></i>
                Transactions
            </a>
        @endif
    </nav>

    <!-- User info -->
    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                <span class="text-white font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
            </div>
        </div>
    </div>
</div>
