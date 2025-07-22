@extends('layouts.app')

@section('title', 'Referrals - Watch & Earn')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">👥 Referral System</h2>
            <p class="text-gray-600">Invite friends and earn rewards when they purchase packages</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Referrals</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalReferrals }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Earnings</p>
                        <p class="text-2xl font-bold text-green-600">Rs. {{ number_format($totalReferralEarnings, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">This Month</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $thisMonthReferrals }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Referral Level</p>
                        <p class="text-2xl font-bold text-yellow-600">Level {{ $referralLevel }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Referral Link -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">🔗 Your Referral Link</h3>
            <div class="flex items-center space-x-4">
                <input type="text" id="referralLink" value="{{ $referralLink }}" readonly
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-gray-50">
                <button onclick="copyReferralLink()"
                    class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
                    📋 Copy Link
                </button>
                <button onclick="shareReferralLink()"
                    class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                    📱 Share
                </button>
            </div>
            <p class="text-sm text-gray-600 mt-2">Share this link with friends to earn 10% commission when they purchase
                packages!</p>
        </div>

        <!-- Referral Tree -->
        <div class="grid lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">🌳 Your Referral Network</h3>
                @if ($referralTree->count() > 0)
                    <div class="space-y-4">
                        @foreach ($referralTree as $referral)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                        <span
                                            class="text-indigo-600 font-semibold">{{ substr($referral->referred->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $referral->referred->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $referral->created_at->format('M d, Y') }}</p>
                                        @if ($referral->referred->referrals_count > 0)
                                            <p class="text-xs text-blue-600">{{ $referral->referred->referrals_count }}
                                                sub-referrals</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">Rs. {{ number_format($referral->reward, 2) }}</p>
                                    <p class="text-xs text-gray-500">Commission</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No referrals yet</p>
                        <p class="text-sm text-gray-400 mt-1">Start sharing your referral link to build your network!</p>
                    </div>
                @endif
            </div>

            <!-- Leaderboard -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">🏆 Top Referrers</h3>
                @if ($topReferrers->count() > 0)
                    <div class="space-y-3">
                        @foreach ($topReferrers as $index => $referrer)
                            <div
                                class="flex items-center justify-between p-3 {{ $referrer->id === auth()->id() ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50' }} rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 flex items-center justify-center mr-3">
                                        @if ($index === 0)
                                            <span class="text-2xl">🥇</span>
                                        @elseif($index === 1)
                                            <span class="text-2xl">🥈</span>
                                        @elseif($index === 2)
                                            <span class="text-2xl">🥉</span>
                                        @else
                                            <span class="text-gray-600 font-bold">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p
                                            class="font-medium {{ $referrer->id === auth()->id() ? 'text-yellow-800' : 'text-gray-900' }}">
                                            {{ $referrer->id === auth()->id() ? 'You' : $referrer->name }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-indigo-600">{{ $referrer->referrals_count }}</p>
                                    <p class="text-xs text-gray-500">referrals</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No data available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Referrals -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📈 Recent Referral Activity</h3>
            @if ($recentReferrals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Commission</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($recentReferrals as $referral)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                                <span
                                                    class="text-indigo-600 font-semibold text-sm">{{ substr($referral->referred->name, 0, 1) }}</span>
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $referral->referred->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $referral->created_at->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-green-600">Rs.
                                            {{ number_format($referral->reward, 2) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">No referral activity yet</p>
                    <p class="text-sm text-gray-400 mt-1">Your referral history will appear here</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function copyReferralLink() {
            const linkInput = document.getElementById('referralLink');
            linkInput.select();
            linkInput.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(linkInput.value).then(function() {
                // Show success message
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '✅ Copied!';
                button.classList.add('bg-green-600');
                button.classList.remove('bg-indigo-600');

                setTimeout(function() {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-indigo-600');
                }, 2000);
            });
        }

        function shareReferralLink() {
            const referralLink = document.getElementById('referralLink').value;
            const shareText =
                `Join Watch & Earn and start making money by watching videos! Use my referral link: ${referralLink}`;

            if (navigator.share) {
                navigator.share({
                    title: 'Join Watch & Earn',
                    text: shareText,
                    url: referralLink
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(shareText)}`;
                window.open(whatsappUrl, '_blank');
            }
        }
    </script>
@endsection
