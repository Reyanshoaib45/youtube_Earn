<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw - Watch & Earn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-2xl font-bold text-indigo-600">Watch & Earn</h1>
                <div class="flex space-x-4">
                    <a href="{{ route('user.dashboard') }}" class="text-gray-600 hover:text-indigo-600">Dashboard</a>
                    <a href="{{ route('user.videos') }}" class="text-gray-600 hover:text-indigo-600">Videos</a>
                    <a href="{{ route('user.packages') }}" class="text-gray-600 hover:text-indigo-600">Packages</a>
                    <a href="{{ route('user.withdraw') }}" class="text-indigo-600 font-semibold">Withdraw</a>
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
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Withdraw Money</h2>
            <p class="text-gray-600">Request withdrawal of your earned money</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Withdrawal Form -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">💰 Request Withdrawal</h3>
                
                <!-- Balance Info -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Available Balance:</span>
                        <span class="text-2xl font-bold text-green-600">Rs. {{ number_format($user->total_earnings, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Referrals:</span>
                        <span class="font-semibold {{ $referralCount >= 3 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $referralCount }}/3 Required
                        </span>
                    </div>
                </div>

                @if(!$canWithdraw)
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                        <p class="font-bold">⚠️ Withdrawal Requirements Not Met</p>
                        <p>You need at least 3 referrals to withdraw money. Current referrals: {{ $referralCount }}</p>
                        <p class="mt-2">Share your referral code: <strong>{{ $user->referral_code }}</strong></p>
                    </div>
                @endif

                @if($canWithdraw && $user->total_earnings > 0)
                    <form method="POST" action="{{ route('user.request-withdraw') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Withdrawal Amount</label>
                            <input type="number" 
                                   name="amount" 
                                   min="100" 
                                   max="{{ $user->total_earnings }}" 
                                   step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"
                                   placeholder="Enter amount (Min: Rs. 100)"
                                   required>
                            <p class="text-sm text-gray-500 mt-1">Minimum withdrawal: Rs. 100</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Payment Method</label>
                            <select name="payment_method" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                                <option value="">Select Payment Method</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="jazzcash">JazzCash</option>
                                <option value="easypaisa">EasyPaisa</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Account Details</label>
                            <textarea name="account_details" required rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"
                                      placeholder="Enter your account number, phone number, or bank details..."></textarea>
                        </div>

                        <button type="submit" 
                                class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition duration-300">
                            Request Withdrawal
                        </button>
                    </form>
                @elseif($user->total_earnings <= 0)
                    <div class="text-center py-8">
                        <p class="text-gray-500">No earnings available for withdrawal</p>
                        <a href="{{ route('user.videos') }}" class="text-indigo-600 hover:text-indigo-800 mt-2 inline-block">
                            Start watching videos to earn money
                        </a>
                    </div>
                @endif
            </div>

            <!-- Withdrawal History -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📋 Withdrawal History</h3>
                
                @if($pendingWithdrawals->count() > 0)
                    <div class="space-y-4">
                        @foreach($pendingWithdrawals as $withdrawal)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold">Rs. {{ number_format($withdrawal->amount, 2) }}</span>
                                <span class="px-3 py-1 rounded-full text-sm
                                    @if($withdrawal->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($withdrawal->status === 'approved') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($withdrawal->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">
                                Requested: {{ $withdrawal->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No withdrawal requests yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Referral Section -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">👥 Boost Your Referrals</h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-600 mb-4">Share your referral code to unlock withdrawal privileges!</p>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Your Referral Code:</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ $user->referral_code }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-gray-600 mb-4">Referral Link:</p>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <input type="text" 
                               value="{{ url('/register?referral_code=' . $user->referral_code) }}" 
                               class="w-full text-sm text-gray-600 bg-transparent border-none outline-none"
                               readonly
                               onclick="this.select()">
                    </div>
                    <button onclick="copyReferralLink()" 
                            class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition duration-300">
                        Copy Link
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyReferralLink() {
            const input = document.querySelector('input[readonly]');
            input.select();
            document.execCommand('copy');
            alert('Referral link copied to clipboard!');
        }
    </script>
</body>
</html>
