@extends('layouts.app')

@section('title', 'Referrals')
@section('page-title', 'Referral Program')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold">Referral Program</h1>
                <p class="mt-2 opacity-90">Invite friends and earn rewards together</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Referrals -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-2xl">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Referrals</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $referralCount }}</p>
                </div>
            </div>
        </div>
        
        <!-- Monthly Referrals -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-2xl">
                    <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-2xl font-bold text-green-600">{{ $monthlyReferrals }}</p>
                </div>
            </div>
        </div>
        
        <!-- Total Earnings -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-2xl">
                    <i class="fas fa-coins text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Earnings</p>
                    <p class="text-2xl font-bold text-purple-600">${{ number_format($referralEarnings, 2) }}</p>
                </div>
            </div>
        </div>
        
        <!-- Current Bonus -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-2xl">
                    <i class="fas fa-gift text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Bonus Per Referral</p>
                    <p class="text-2xl font-bold text-orange-600">${{ number_format($referralBonus, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Link Section -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">
                <i class="fas fa-link text-blue-600 mr-2"></i>Your Referral Link
            </h2>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Share and earn</span>
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <div class="flex-1 lg:mr-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Unique Referral Link</label>
                    <div class="relative">
                        <input type="text" 
                               id="referral-link" 
                               value="{{ $referralLink }}" 
                               readonly
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-2xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <button onclick="copyReferralLink()" 
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 p-2 text-gray-400 hover:text-blue-600 transition-colors">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <button onclick="copyReferralLink()" 
                            class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-copy mr-2"></i>Copy Link
                    </button>
                    
                    <a href="https://wa.me/?text=Join%20me%20on%20VideoRewards%20and%20earn%20money%20watching%20videos!%20Use%20my%20referral%20link:%20{{ urlencode($referralLink) }}" 
                       target="_blank"
                       class="flex items-center px-4 py-2 bg-green-600 text-white rounded-2xl hover:bg-green-700 transition-colors">
                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                    </a>
                    
                    <a href="https://t.me/share/url?url={{ urlencode($referralLink) }}&text=Join%20me%20on%20VideoRewards%20and%20earn%20money%20watching%20videos!" 
                       target="_blank"
                       class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-2xl hover:bg-blue-600 transition-colors">
                        <i class="fab fa-telegram mr-2"></i>Telegram
                    </a>
                    
                    <button onclick="shareOnFacebook()" 
                            class="flex items-center px-4 py-2 bg-blue-800 text-white rounded-2xl hover:bg-blue-900 transition-colors">
                        <i class="fab fa-facebook mr-2"></i>Facebook
                    </button>
                </div>
            </div>
            
            <div class="mt-4 p-4 bg-white rounded-2xl border border-blue-200">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                    <div class="text-sm text-gray-700">
                        <p class="font-medium mb-1">How to maximize your referrals:</p>
                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                            <li>Share your link on social media platforms</li>
                            <li>Send directly to friends and family via messaging apps</li>
                            <li>Explain the benefits of joining VideoRewards</li>
                            <li>Help your referrals get started with their first package</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-question-circle text-blue-600 mr-2"></i>How the Referral Program Works
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-share-alt text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">1. Share Your Link</h3>
                <p class="text-gray-600 text-sm">Share your unique referral link with friends, family, and on social media</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-plus text-green-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">2. Friends Sign Up</h3>
                <p class="text-gray-600 text-sm">When they register using your link, they become your referral automatically</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-credit-card text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">3. They Purchase Package</h3>
                <p class="text-gray-600 text-sm">Your referral purchases and activates their first package on the platform</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-coins text-orange-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">4. You Earn Bonus</h3>
                <p class="text-gray-600 text-sm">You receive ${{ number_format($referralBonus, 2) }} bonus instantly when their package is approved</p>
            </div>
        </div>
        
        <div class="mt-8 p-6 bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl border border-green-200">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-lightbulb text-green-600"></i>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Pro Tip</h4>
                    <p class="text-gray-700">The more active your referrals are, the more you both benefit! Help them understand how to maximize their earnings by watching videos and completing tasks.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral History -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">
                <i class="fas fa-history text-gray-600 mr-2"></i>Referral History
            </h2>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">{{ $referrals->total() }} total referrals</span>
            </div>
        </div>
        
        @if($referrals->isEmpty())
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-users text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Referrals Yet</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">Start sharing your referral link to earn bonuses when your friends join VideoRewards!</p>
                <button onclick="copyReferralLink()" 
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-colors">
                    <i class="fas fa-copy mr-2"></i>Copy Referral Link
                </button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Referred User</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Join Date</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Status</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Package</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Bonus Earned</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($referrals as $referral)
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white font-medium">{{ substr($referral->referred->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $referral->referred->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $referral->referred->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div>
                                        <p class="text-sm text-gray-900">{{ $referral->created_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $referral->created_at->format('h:i A') }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    @if($referral->bonus_paid)
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Bonus Paid
                                        </span>
                                    @elseif($referral->referred->activePackage)
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <i class="fas fa-clock mr-1"></i>Processing
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-hourglass-half mr-1"></i>Pending Package
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @if($referral->referred->activePackage)
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $referral->referred->activePackage->package->name }}</p>
                                            <p class="text-xs text-gray-500">${{ number_format($referral->referred->activePackage->package->price, 2) }}</p>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">No package yet</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @if($referral->bonus_paid)
                                        <span class="font-semibold text-green-600">${{ number_format($referral->bonus_amount, 2) }}</span>
                                    @else
                                        <span class="text-gray-400">$0.00</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-2">
                                        @if(!$referral->referred->activePackage)
                                            <button onclick="remindUser('{{ $referral->referred->name }}')" 
                                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                                <i class="fas fa-paper-plane mr-1"></i>Remind
                                            </button>
                                        @endif
                                        <button onclick="viewUserDetails({{ $referral->referred->id }})" 
                                                class="text-gray-600 hover:text-gray-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $referrals->links() }}
            </div>
        @endif
    </div>

    <!-- Referral Leaderboard -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">
                <i class="fas fa-trophy text-yellow-500 mr-2"></i>Top Referrers This Month
            </h2>
            <span class="text-sm text-gray-600">Your rank: #{{ $userRank ?? 'N/A' }}</span>
        </div>
        
        <div class="space-y-4">
            @forelse($topReferrers as $index => $topReferrer)
                <div class="flex items-center justify-between p-4 {{ $topReferrer->id === auth()->id() ? 'bg-blue-50 border border-blue-200' : 'bg-gray-50' }} rounded-2xl">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center justify-center w-8 h-8 {{ $index === 0 ? 'bg-yellow-100 text-yellow-600' : ($index === 1 ? 'bg-gray-100 text-gray-600' : ($index === 2 ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600')) }} rounded-full font-bold text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-medium">{{ substr($topReferrer->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $topReferrer->id === auth()->id() ? 'You' : $topReferrer->name }}
                            </p>
                            <p class="text-sm text-gray-600">{{ $topReferrer->monthly_referrals }} referrals this month</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-green-600">${{ number_format($topReferrer->monthly_earnings, 2) }}</p>
                        <p class="text-xs text-gray-500">earned</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <i class="fas fa-trophy text-gray-300 text-3xl mb-4"></i>
                    <p class="text-gray-500">No referrers this month yet. Be the first!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function copyReferralLink() {
    const referralLink = document.getElementById('referral-link');
    referralLink.select();
    referralLink.setSelectionRange(0, 99999); // For mobile devices
    
    navigator.clipboard.writeText(referralLink.value).then(function() {
        showToast('Referral link copied to clipboard!', 'success');
    }).catch(function() {
        // Fallback for older browsers
        document.execCommand('copy');
        showToast('Referral link copied to clipboard!', 'success');
    });
}

function shareOnFacebook() {
    const url = encodeURIComponent('{{ $referralLink }}');
    const text = encodeURIComponent('Join me on VideoRewards and earn money watching videos!');
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${text}`, '_blank', 'width=600,height=400');
}

function remindUser(userName) {
    if (confirm(`Send a reminder to ${userName} to purchase a package?`)) {
        // This would typically send a notification or email
        showToast(`Reminder sent to ${userName}!`, 'success');
    }
}

function viewUserDetails(userId) {
    // This would open a modal or redirect to user details
    showToast('User details feature coming soon!', 'info');
}
</script>
@endsection
