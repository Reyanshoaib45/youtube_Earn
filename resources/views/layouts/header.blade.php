<nav class="bg-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <h1 class="text-2xl font-bold text-indigo-600">
                @if (auth()->check())
                    @if (auth()->user()->role === 'admin')
                        Admin Panel
                    @elseif(auth()->user()->role === 'manager')
                        Manager Panel
                    @else
                        Watch & Earn
                    @endif
                @else
                    Watch & Earn
                @endif
            </h1>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" class="text-gray-600 hover:text-indigo-600 focus:outline-none"
                    onclick="toggleMenu()">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Desktop menu -->
            <div class="hidden md:flex space-x-4">
                @auth
                    @if (auth()->user()->role === 'user')
                        <a href="{{ route('user.dashboard') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('user.dashboard') ? 'text-indigo-600 font-semibold' : '' }}">Dashboard</a>
                        <a href="{{ route('user.videos') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('user.videos') ? 'text-indigo-600 font-semibold' : '' }}">Videos</a>
                        <a href="{{ route('user.packages') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('user.packages') ? 'text-indigo-600 font-semibold' : '' }}">Packages</a>
                        <a href="{{ route('user.referrals') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('user.referrals') ? 'text-indigo-600 font-semibold' : '' }}">Referrals</a>
                        <a href="{{ route('user.withdraw') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('user.withdraw') ? 'text-indigo-600 font-semibold' : '' }}">Withdraw</a>
                    @elseif(auth()->user()->role === 'manager')
                        <a href="{{ route('manager.dashboard') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('manager.dashboard') ? 'text-indigo-600 font-semibold' : '' }}">Dashboard</a>
                        <a href="{{ route('manager.videos') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('manager.videos') ? 'text-indigo-600 font-semibold' : '' }}">Videos</a>
                        <a href="{{ route('manager.packages') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('manager.packages') ? 'text-indigo-600 font-semibold' : '' }}">Packages</a>
                        <a href="{{ route('manager.purchase-requests') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('manager.purchase-requests') ? 'text-indigo-600 font-semibold' : '' }}">Purchase
                            Requests</a>
                        <a href="{{ route('manager.withdrawals') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('manager.withdrawals') ? 'text-indigo-600 font-semibold' : '' }}">Withdrawals</a>
                    @elseif(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 font-semibold' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.managers') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('admin.managers') ? 'text-indigo-600 font-semibold' : '' }}">Managers</a>
                        <a href="{{ route('admin.withdrawals') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('admin.withdrawals') ? 'text-indigo-600 font-semibold' : '' }}">Withdrawals</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">Login</a>
                    <a href="{{ route('register') }}"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Register</a>
                @endauth
            </div>
        </div>

        <!-- Mobile menu (hidden by default) -->
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            @auth
                @if (auth()->user()->role === 'user')
                    <div class="flex flex-col space-y-3">
                        <a href="{{ route('user.dashboard') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('user.dashboard') ? 'text-indigo-600 font-semibold' : '' }}">Dashboard</a>
                        <a href="{{ route('user.videos') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('user.videos') ? 'text-indigo-600 font-semibold' : '' }}">Videos</a>
                        <a href="{{ route('user.packages') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('user.packages') ? 'text-indigo-600 font-semibold' : '' }}">Packages</a>
                        <a href="{{ route('user.referrals') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('user.referrals') ? 'text-indigo-600 font-semibold' : '' }}">Referrals</a>
                        <a href="{{ route('user.withdraw') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('user.withdraw') ? 'text-indigo-600 font-semibold' : '' }}">Withdraw</a>
                    </div>
                @elseif(auth()->user()->role === 'manager')
                    <div class="flex flex-col space-y-3">
                        <a href="{{ route('manager.dashboard') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('manager.dashboard') ? 'text-indigo-600 font-semibold' : '' }}">Dashboard</a>
                        <a href="{{ route('manager.videos') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('manager.videos') ? 'text-indigo-600 font-semibold' : '' }}">Videos</a>
                        <a href="{{ route('manager.packages') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('manager.packages') ? 'text-indigo-600 font-semibold' : '' }}">Packages</a>
                        <a href="{{ route('manager.purchase-requests') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('manager.purchase-requests') ? 'text-indigo-600 font-semibold' : '' }}">Purchase
                            Requests</a>
                        <a href="{{ route('manager.withdrawals') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('manager.withdrawals') ? 'text-indigo-600 font-semibold' : '' }}">Withdrawals</a>
                    </div>
                @elseif(auth()->user()->role === 'admin')
                    <div class="flex flex-col space-y-3">
                        <a href="{{ route('admin.dashboard') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 font-semibold' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.managers') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('admin.managers') ? 'text-indigo-600 font-semibold' : '' }}">Managers</a>
                        <a href="{{ route('admin.withdrawals') }}"
                            class="text-gray-600 hover:text-indigo-600 {{ request()->routeIs('admin.withdrawals') ? 'text-indigo-600 font-semibold' : '' }}">Withdrawals</a>
                    </div>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
                </form>
            @else
                <div class="flex flex-col space-y-3">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">Login</a>
                    <a href="{{ route('register') }}"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-center">Register</a>
                </div>
            @endauth
        </div>
    </div>
</nav>

<script>
    function toggleMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }
</script>
