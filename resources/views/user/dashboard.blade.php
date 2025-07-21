@extends('layouts.app')

@section('title', 'User Dashboard - Watch & Earn')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">Welcome back, {{ $user->name }}! 👋</h1>
                <p class="text-indigo-100 text-lg">Ready to earn more money today?</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold">Rs. {{ number_format($totalEarnings, 2) }}</div>
                <p class="text-indigo-200">Total Earnings</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Earnings -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">💰 Total Earnings</p>
                    <p class="text-2xl font-bold text-gray-900">Rs. {{ number_format($totalEarnings, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Today's Videos -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">📹 Videos Today</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $todayWatched }}
                        @if($currentPackage)
                            / {{ $currentPackage->daily_video_limit }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Referrals -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">👥 Referrals</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $referralCount }}</p>
                    <p class="text-xs {{ $referralCount >= 3 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $referralCount >= 3 ? '✅ Can withdraw' : '❌ Need ' . (3 - $referralCount) . ' more' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Referral Earnings -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">🎁 Referral Earnings</p>
                    <p class="text-2xl font-bold text-gray-900">Rs. {{ number_format($referralEarnings, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Current Package -->
        <div class="lg:col-span-2">
            @if($currentPackage)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">📦 Current Active Package</h2>
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600">{{ $currentPackage->name }}</h3>
                            <p class="text-gray-600">{{ $currentPackage->description ?? 'Active earning package' }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-green-600">Rs. {{ number_format($currentPackage->total_reward) }}</div>
                            <p class="text-sm text-gray-600">Total Reward</p>
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-3 gap-4 mb-4">
                        <div class="text-center">
                            <div class="text-lg font-bold text-gray-800">{{ $currentPackage->daily_video_limit }}</div>
                            <p class="text-sm text-gray-600">Daily Videos</p>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-gray-800">{{ $currentPackage->duration_days }}</div>
                            <p class="text-sm text-gray-600">Days</p>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-green-600">Rs. {{ number_format($currentPackage->total_reward - $currentPackage->price) }}</div>
                            <p class="text-sm text-gray-600">Your Profit</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Today's Progress</span>
                            <span>{{ $todayWatched }}/{{ $currentPackage->daily_video_limit }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $currentPackage->daily_video_limit > 0 ? ($todayWatched / $currentPackage->daily_video_limit) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('user.videos') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 flex-1 text-center">
                            📹 Watch Videos
                        </a>
                        @if($todayWatched >= $currentPackage->daily_video_limit)
                            <div class="bg-green-100 text-green-800 px-6 py-2 rounded-lg flex-1 text-center font-semibold">
                                ✅ Daily Limit Reached
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                <div class="flex items-center">
                    <div class="text-4xl mr-4">⚠️</div>
                    <div>
                        <h3 class="text-xl font-bold text-yellow-800 mb-2">No Active Package</h3>
                        <p class="text-yellow-700 mb-4">You need to purchase a package to start earning money by watching videos.</p>
                        <a href="{{ route('user.packages') }}" class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition duration-300">
                            📦 Browse Packages
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">⚡ Quick Actions</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <a href="{{ route('user.videos') }}" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg transition duration-300 border border-blue-200">
                        <div class="flex items-center">
                            <div class="text-3xl mr-3">📹</div>
                            <div>
                                <h3 class="font-semibold text-blue-800">Watch Videos</h3>
                                <p class="text-sm text-blue-600">Earn Rs.60 per video</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('user.packages') }}" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg transition duration-300 border border-green-200">
                        <div class="flex items-center">
                            <div class="text-3xl mr-3">📦</div>
                            <div>
                                <h3 class="font-semibold text-green-800">Buy Package</h3>
                                <p class="text-sm text-green-600">110% - 237% profit</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('user.referrals') }}" class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg transition duration-300 border border-purple-200">
                        <div class="flex items-center">
                            <div class="text-3xl mr-3">👥</div>
                            <div>
                                <h3 class="font-semibold text-purple-800">Refer Friends</h3>
                                <p class="text-sm text-purple-600">Earn Rs.50 per referral</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('user.withdraw') }}" class="bg-yellow-50 hover:bg-yellow-100 p-4 rounded-lg transition duration-300 border border-yellow-200">
                        <div class="flex items-center">
                            <div class="text-3xl mr-3">💰</div>
                            <div>
                                <h3 class="font-semibold text-yellow-800">Withdraw Money</h3>
                                <p class="text-sm text-yellow-600">{{ $referralCount >= 3 ? 'Available' : 'Need ' . (3 - $referralCount) . ' referrals' }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Referral Section -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">🎁 Referral Program</h3>
                
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-4 mb-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $referralCount }}</div>
                        <p class="text-sm text-purple-700">Total Referrals</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Your Referral Code:</label>
                        <div class="bg-gray-100 p-3 rounded-lg">
                            <code class="text-lg font-bold text-indigo-600">{{ $user->referral_code }}</code>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Referral Link:</label>
                        <div class="bg-gray-100 p-3 rounded-lg">
                            <input type="text" 
                                   value="{{ url('/register?referral_code=' . $user->referral_code) }}" 
                                   class="w-full text-xs text-gray-600 bg-transparent border-none outline-none"
                                   readonly
                                   onclick="this.select()">
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <button onclick="copyReferralLink()" 
                                class="flex-1 bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition duration-300 text-sm">
                            📋 Copy Link
                        </button>
                        <a href="{{ route('user.referrals') }}" 
                           class="flex-1 bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition duration-300 text-center text-sm">
                            👥 View All
                        </a>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-green-50 rounded-lg">
                    <p class="text-sm text-green-700">
                        💡 <strong>Tip:</strong> Share your referral link on social media to earn Rs.50 for each person who joins!
                    </p>
                </div>
            </div>

            <!-- Earnings Breakdown -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📊 Earnings Breakdown</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Video Earnings:</span>
                        <span class="font-semibold">Rs. {{ number_format($totalEarnings - $referralEarnings, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Referral Earnings:</span>
                        <span class="font-semibold text-purple-600">Rs. {{ number_format($referralEarnings, 2) }}</span>
                    </div>
                    <hr>
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total Earnings:</span>
                        <span class="text-green-600">Rs. {{ number_format($totalEarnings, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Account Status -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">👤 Account Status</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Account Type:</span>
                        <span class="font-semibold text-blue-600">{{ ucfirst($user->role) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Member Since:</span>
                        <span class="font-semibold">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Withdrawal Status:</span>
                        <span class="font-semibold {{ $referralCount >= 3 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $referralCount >= 3 ? '✅ Enabled' : '❌ Disabled' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyReferralLink() {
        const input = document.querySelector('input[readonly]');
        input.select();
        document.execCommand('copy');
        
        // Show success message
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = '✅ Copied!';
        button.classList.add('bg-green-600');
        button.classList.remove('bg-purple-600');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-purple-600');
        }, 2000);
    }
</script>
@endpush
@endsection
