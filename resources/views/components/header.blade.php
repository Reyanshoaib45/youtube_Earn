<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between h-16 px-4 lg:px-6">
        <!-- Mobile menu button -->
        <button @click="sidebarOpen = !sidebarOpen" 
                class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i class="fas fa-bars text-lg"></i>
        </button>

        <!-- Page title -->
        <div class="flex-1 lg:flex-none">
            <h1 class="text-xl font-semibold text-gray-900">
                @yield('page-title', 'Dashboard')
            </h1>
        </div>

        <!-- Header actions -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 relative">
                    <i class="fas fa-bell text-lg"></i>
                    @if(auth()->user()->notifications()->where('is_read', false)->count() > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ auth()->user()->notifications()->where('is_read', false)->count() }}
                        </span>
                    @endif
                </button>

                <!-- Notifications dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-lg border border-gray-200 z-50">
                    
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                    </div>
                    
                    <div class="max-h-96 overflow-y-auto">
                        @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                            <div class="p-4 border-b border-gray-100 hover:bg-gray-50 {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-bell text-blue-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                        <p class="text-xs text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-gray-500">
                                <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                <p>No notifications</p>
                            </div>
                        @endforelse
                    </div>
                    
                    @if(auth()->user()->notifications()->count() > 0)
                        <div class="p-4 border-t border-gray-200">
                            <a href="{{ route(auth()->user()->role . '.notifications') }}" 
                               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                View all notifications
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- User menu -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center space-x-3 p-2 rounded-2xl hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-medium text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                </button>

                <!-- User dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-lg border border-gray-200 z-50">
                    
                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('user.profile') }}" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-t-2xl">
                            <i class="fas fa-user mr-3 text-gray-400"></i>
                            Profile
                        </a>
                        <a href="{{ route('user.rewards') }}" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-coins mr-3 text-gray-400"></i>
                            Earnings: ${{ number_format(auth()->user()->reward_balance, 2) }}
                        </a>
                    @endif
                    
                    <div class="border-t border-gray-200"></div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-b-2xl">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
