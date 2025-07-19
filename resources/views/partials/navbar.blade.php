<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between px-4 py-3 lg:px-6">
        <!-- Mobile menu button -->
        <button @click="sidebarOpen = !sidebarOpen" 
                class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <!-- Page title -->
        <div class="flex-1 lg:flex-none">
            <h1 class="text-xl font-semibold text-gray-900 lg:hidden">@yield('page-title', 'Dashboard')</h1>
        </div>

        <!-- Right side -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" 
                        class="relative p-2 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
                    <i class="fas fa-bell text-lg"></i>
                    @if(auth()->user()->role === 'manager' && \App\Models\Withdrawal::where('status', 'pending')->count() > 0)
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
                    @endif
                </button>

                <!-- Notifications dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                    <div class="p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Notifications</h3>
                        <div class="space-y-2">
                            @if(auth()->user()->role === 'manager')
                                @php $pendingWithdrawals = \App\Models\Withdrawal::where('status', 'pending')->count(); @endphp
                                @if($pendingWithdrawals > 0)
                                    <div class="flex items-center p-2 bg-yellow-50 rounded-xl">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
                                        <span class="text-sm text-yellow-800">{{ $pendingWithdrawals }} pending withdrawal{{ $pendingWithdrawals > 1 ? 's' : '' }}</span>
                                    </div>
                                @endif
                            @endif
                            <div class="text-sm text-gray-500 text-center py-4">No new notifications</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User menu -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" 
                        class="flex items-center space-x-3 text-sm focus:outline-none">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="hidden lg:block text-left">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                </button>

                <!-- User dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                    <div class="py-2">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            @if(auth()->user()->role === 'user')
                                <p class="text-xs text-green-600 font-medium mt-1">
                                    Balance: ${{ number_format(auth()->user()->reward_balance, 2) }}
                                </p>
                            @endif
                        </div>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-user mr-2"></i>Profile
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-cog mr-2"></i>Settings
                        </a>
                        <div class="border-t border-gray-100 mt-2 pt-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
