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

            @auth
                <div class="flex space-x-4">
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
                </div>
            @else
                <div class="flex space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">Login</a>
                    <a href="{{ route('register') }}"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Register</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
