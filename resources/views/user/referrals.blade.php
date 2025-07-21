@extends('layouts.app')

@section('title', 'Referral System - Watch & Earn')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">🎁 Referral System</h1>
        <p class="text-gray-600 text-lg">Invite friends and earn Rs. 50 for each successful referral!</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Referrals -->
        <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Total Referrals</p>
                    <p class="text-3xl font-bold">{{ $totalReferrals }}</p>
                </div>
                <div class="text-4xl">👥</div>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Referral Earnings</p>
                    <p class="text-3xl font-bold">Rs. {{ number_format($totalReferralEarnings) }}</p>
                </div>
                <div class="text-4xl">💰</div>
            </div>
        </div>

        <!-- This Month -->
        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">This Month</p>
                    <p class="text-3xl font-bold">{{ $thisMonthReferrals }}</p>
                </div>
                <div class="text-4xl">📅</div>
            </div>
        </div>

        <!-- Referral Level -->
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Referral Level</p>
                    <p class="text-3xl font-bold">Level {{ $referralLevel }}</p>
                </div>
                <div class="text-4xl">🏆</div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Referral Tools -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Share Your Link -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">🔗 Share Your Referral Link</h2>
                
                <div class="space-y-4">
                    <!-- Referral Code -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Referral Code:</label>
                        <div class="flex items-center space-x-2">
                            <div class="bg-gray-100 px-4 py-3 rounded-lg flex-1">
                                <code class="text-lg font-bold text-indigo-600">{{ $user->referral_code }}</code>
                            </div>
                            <button onclick="copyReferralCode()" class="bg-indigo-600 text-white px-4 py-3 rounded-lg hover:bg-indigo-700">
                                📋 Copy
                            </button>
                        </div>
                    </div>

                    <!-- Referral Link -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Referral Link:</label>
                        <div class="flex items-center space-x-2">
                            <input type="text" 
                                   id="referralLink"
                                   value="{{ $referralLink }}" 
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-gray-50"
                                   readonly>
                            <button onclick="copyReferralLink()" class="bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700">
                                📋 Copy
                            </button>
                        </div>
                    </div>

                    <!-- Social Sharing -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Share on Social Media:</label>
                        <div class="flex space-x-3">
                            <a href="https://wa.me/?text=Join Watch & Earn and start making money by watching videos! Use my referral link: {{ urlencode($referralLink) }}" 
                               target="_blank"
                               class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 flex items-center">
                                📱 WhatsApp
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($referralLink) }}" 
                               target="_blank"
                               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                                📘 Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?text=Join Watch & Earn and start making money!&url={{ urlencode($referralLink) }}" 
                               target="_blank"
                               class="bg-blue-400 text-white px-4 py-2 rounded-lg hover:bg-blue-500 flex items-center">
                                🐦 Twitter
                            </a>
                            <a href="https://t.me/share/url?url={{ urlencode($referralLink) }}&text=Join Watch & Earn and start making money by watching videos!" 
                               target="_blank"
                               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 flex items-center">
                                ✈️ Telegram
                            </a>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div class="text-center">
                        <label class="block text-sm font-medium text-gray-700 mb-2">QR Code for Easy Sharing:</label>
                        <div class="inline-block bg-white p-4 rounded-lg border">
                            <div id="qrcode" class="mx-auto"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Scan this QR code to join via your referral link</p>
                    </div>
                </div>
            </div>

            <!-- Referral Tree -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">🌳 Your Referral Network</h2>
                
                @if($referralTree->count() > 0)
                    <div class="space-y-4">
                        @foreach($referralTree as $referral)
                        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-purple-500">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="bg-purple-100 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-purple-600 font-semibold">{{ substr($referral->referred->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ $referral->referred->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $referral->referred->email }}</p>
                                        <p class="text-xs text-gray-500">Joined: {{ $referral->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-green-600 font-bold">+Rs. {{ number_format($referral->reward) }}</div>
                                    @if($referral->referred->referrals_count > 0)
                                        <div class="text-xs text-purple-600">{{ $referral->referred->referrals_count }} sub-referrals</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-6xl mb-4">🌱</div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">No Referrals Yet</h3>
                        <p class="text-gray-600">Start sharing your referral link to grow your network!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Referral Rewards -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">🎁 Referral Rewards</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <span class="text-sm font-medium">Per Referral</span>
                        <span class="font-bold text-green-600">Rs. 50</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                        <span class="text-sm font-medium">5 Referrals Bonus</span>
                        <span class="font-bold text-purple-600">Rs. 100</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                        <span class="text-sm font-medium">10 Referrals Bonus</span>
                        <span class="font-bold text-blue-600">Rs. 250</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                        <span class="text-sm font-medium">25 Referrals Bonus</span>
                        <span class="font-bold text-yellow-600">Rs. 500</span>
                    </div>
                </div>
            </div>

            <!-- Progress to Next Milestone -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">🎯 Next Milestone</h3>
                @php
                    $nextMilestone = 5;
                    if($totalReferrals >= 25) $nextMilestone = 50;
                    elseif($totalReferrals >= 10) $nextMilestone = 25;
                    elseif($totalReferrals >= 5) $nextMilestone = 10;
                    
                    $progress = ($totalReferrals / $nextMilestone) * 100;
                    $remaining = $nextMilestone - $totalReferrals;
                @endphp
                
                <div class="text-center mb-4">
                    <div class="text-2xl font-bold text-indigo-600">{{ $totalReferrals }}/{{ $nextMilestone }}</div>
                    <p class="text-sm text-gray-600">Referrals to next bonus</p>
                </div>
                
                <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                    <div class="bg-indigo-600 h-3 rounded-full transition-all duration-300" style="width: {{ min($progress, 100) }}%"></div>
                </div>
                
                @if($remaining > 0)
                    <p class="text-center text-sm text-gray-600">{{ $remaining }} more referrals needed!</p>
                @else
                    <p class="text-center text-sm text-green-600 font-semibold">🎉 Milestone achieved!</p>
                @endif
            </div>

            <!-- Top Referrers -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">🏆 Top Referrers</h3>
                <div class="space-y-3">
                    @foreach($topReferrers as $index => $referrer)
                    <div class="flex items-center justify-between p-2 {{ $referrer->id === $user->id ? 'bg-yellow-50 border border-yellow-200 rounded' : '' }}">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center text-white font-bold text-sm mr-3">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <div class="font-semibold text-sm {{ $referrer->id === $user->id ? 'text-yellow-800' : 'text-gray-800' }}">
                                    {{ $referrer->id === $user->id ? 'You' : $referrer->name }}
                                </div>
                            </div>
                        </div>
                        <div class="text-sm font-bold text-purple-600">{{ $referrer->referrals_count }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📈 Recent Referrals</h3>
                @if($recentReferrals->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentReferrals as $referral)
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                            <div>
                                <div class="font-semibold text-sm text-gray-800">{{ $referral->referred->name }}</div>
                                <div class="text-xs text-gray-500">{{ $referral->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="text-green-600 font-bold text-sm">+Rs. {{ number_format($referral->reward) }}</div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm text-center py-4">No recent referrals</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
    // Generate QR Code
    QRCode.toCanvas(document.getElementById('qrcode'), '{{ $referralLink }}', {
        width: 150,
        height: 150,
        colorDark: '#4F46E5',
        colorLight: '#FFFFFF',
    });

    function copyReferralCode() {
        const code = '{{ $user->referral_code }}';
        navigator.clipboard.writeText(code).then(function() {
            showCopySuccess('Referral code copied!');
        });
    }

    function copyReferralLink() {
        const link = document.getElementById('referralLink');
        link.select();
        document.execCommand('copy');
        showCopySuccess('Referral link copied!');
    }

    function showCopySuccess(message) {
        // Create a temporary success message
        const successDiv = document.createElement('div');
        successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        successDiv.textContent = message;
        document.body.appendChild(successDiv);
        
        setTimeout(() => {
            document.body.removeChild(successDiv);
        }, 3000);
    }
</script>
@endpush
@endsection
