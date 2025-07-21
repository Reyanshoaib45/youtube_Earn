<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Watch & Earn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-2xl font-bold text-red-600">Admin Panel</h1>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-red-600 font-semibold">Dashboard</a>
                    <a href="{{ route('admin.managers') }}" class="text-gray-600 hover:text-red-600">Managers</a>
                    <a href="{{ route('admin.withdrawals') }}" class="text-gray-600 hover:text-red-600">Withdrawals</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Admin Dashboard</h2>
            <p class="text-gray-600">System overview and management</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Managers</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalManagers }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Withdrawals</p>
                        <p class="text-2xl font-bold text-gray-900">Rs. {{ number_format($totalWithdrawals, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-red-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Withdrawals</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingWithdrawals }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">👨‍💼 Manager Management</h3>
                <p class="text-gray-600 mb-4">Create and manage manager accounts</p>
                <a href="{{ route('admin.managers') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                    Manage Managers
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">💰 Withdrawal History</h3>
                <p class="text-gray-600 mb-4">View all withdrawal transactions</p>
                <a href="{{ route('admin.withdrawals') }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-300">
                    View Withdrawals
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 System Analytics</h3>
                <p class="text-gray-600 mb-4">Monitor platform performance</p>
                <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition duration-300">
                    View Analytics
                </button>
            </div>
        </div>

        <!-- System Overview -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6">🔍 System Overview</h3>
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h4 class="font-semibold text-gray-700 mb-4">Platform Statistics</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <span class="text-gray-600">Registered Users:</span>
                            <span class="font-semibold text-blue-600">{{ $totalUsers }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <span class="text-gray-600">Active Managers:</span>
                            <span class="font-semibold text-green-600">{{ $totalManagers }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <span class="text-gray-600">Total Payouts:</span>
                            <span class="font-semibold text-yellow-600">Rs. {{ number_format($totalWithdrawals, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <span class="text-gray-600">Pending Requests:</span>
                            <span class="font-semibold text-red-600">{{ $pendingWithdrawals }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700 mb-4">Recent Activity</h4>
                    <div class="space-y-3">
                        <div class="p-3 bg-blue-50 rounded border-l-4 border-blue-400">
                            <p class="text-sm text-blue-800">System running smoothly</p>
                            <p class="text-xs text-blue-600 mt-1">All services operational</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded border-l-4 border-green-400">
                            <p class="text-sm text-green-800">{{ $totalUsers }} users registered</p>
                            <p class="text-xs text-green-600 mt-1">Growing user base</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded border-l-4 border-yellow-400">
                            <p class="text-sm text-yellow-800">{{ $pendingWithdrawals }} pending withdrawals</p>
                            <p class="text-xs text-yellow-600 mt-1">Requires manager attention</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
