@extends('layouts.app')

@section('title', 'User Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-6 text-white">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-blue-100">Ready to watch some videos and earn rewards?</p>
            </div>
            <div class="mt-4 lg:mt-0">
                @if($activePackage)
                    <div class="bg-white bg-opacity-20 rounded-2xl p-4 text-center">
                        <p class="text-sm text-blue-100">Package expires in</p>
                        <p class="text-2xl font-bold">{{ $user->activePackage?->daysRemaining() }} days</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Balance card -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-2xl">
                    <i class="fas fa-wallet text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Reward Balance</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($totalRewards, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Videos watched -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-2xl">
                    <i class="fas fa-play-circle text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Videos Watched</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalWatched }}</p>
                </div>
            </div>
        </div>

        <!-- Current package -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-2xl">
                    <i class="fas fa-box text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Current Package</p>
                    <p class="text-lg font-bold text-gray-900">
                        {{ $activePackage ? $activePackage->package->name : 'None' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Days remaining -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-2xl">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Days Remaining</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $activePackage ? $user->activePackage?->daysRemaining() : 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Package info or no package warning -->
    @if($activePackage)
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>Package Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-sm text-gray-600 mb-1">Package Name</p>
                    <p class="font-semibold text-gray-900">{{ $activePackage->package->name }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-sm text-gray-600 mb-1">Reward Per Video</p>
                    <p class="font-semibold text-green-600">${{ number_format($activePackage->package->reward_per_video, 2) }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-sm text-gray-600 mb-1">Minimum Withdrawal</p>
                    <p class="font-semibold text-blue-600">${{ number_format($activePackage->package->min_withdrawal, 2) }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-sm text-gray-600 mb-1">Start Date</p>
                    <p class="font-semibold text-gray-900">{{ $activePackage->start_date->format('M d, Y') }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-sm text-gray-600 mb-1">End Date</p>
                    <p class="font-semibold text-gray-900">{{ $activePackage->end_date->format('M d, Y') }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-sm text-gray-600 mb-1">Status</p>
                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $activePackage->isExpired() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        <i class="fas {{ $activePackage->isExpired() ? 'fa-times-circle' : 'fa-check-circle' }} mr-1"></i>
                        {{ $activePackage->isExpired() ? 'Expired' : 'Active' }}
                    </span>
                </div>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-medium text-yellow-800 mb-2">No Active Package</h3>
                    <p class="text-yellow-700 mb-4">You need to purchase a package to start watching videos and earning rewards.</p>
                    <a href="{{ route('user.packages') }}"
                       class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-2xl hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-shopping-cart mr-2"></i>View Packages
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick actions and recent activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick actions -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-bolt text-yellow-500 mr-2"></i>Quick Actions
            </h2>
            <div class="space-y-3">
                <a href="{{ route('user.videos') }}"
                   class="flex items-center justify-between p-4 bg-blue-50 hover:bg-blue-100 rounded-2xl transition-colors group">
                    <div class="flex items-center">
                        <i class="fas fa-play-circle text-blue-600 text-xl mr-3"></i>
                        <span class="font-medium text-blue-900">Watch Videos</span>
                    </div>
                    <i class="fas fa-arrow-right text-blue-600 group-hover:translate-x-1 transition-transform"></i>
                </a>

                <a href="{{ route('user.rewards') }}"
                   class="flex items-center justify-between p-4 bg-green-50 hover:bg-green-100 rounded-2xl transition-colors group">
                    <div class="flex items-center">
                        <i class="fas fa-coins text-green-600 text-xl mr-3"></i>
                        <span class="font-medium text-green-900">View Earnings</span>
                    </div>
                    <i class="fas fa-arrow-right text-green-600 group-hover:translate-x-1 transition-transform"></i>
                </a>

                @if($user->canWithdraw())
                    <a href="{{ route('user.withdrawals') }}"
                       class="flex items-center justify-between p-4 bg-purple-50 hover:bg-purple-100 rounded-2xl transition-colors group">
                        <div class="flex items-center">
                            <i class="fas fa-wallet text-purple-600 text-xl mr-3"></i>
                            <span class="font-medium text-purple-900">Request Withdrawal</span>
                        </div>
                        <i class="fas fa-arrow-right text-purple-600 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                @endif
            </div>
        </div>

        <!-- Recent activity -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-history text-gray-600 mr-2"></i>Recent Activity
            </h2>
            <div class="space-y-3">
                @forelse($user->watchedVideos()->with('video')->where('reward_granted', true)->latest()->take(5)->get() as $watched)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-2xl flex items-center justify-center mr-3">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 truncate">{{ Str::limit($watched->video->title, 30) }}</p>
                                <p class="text-sm text-gray-500">{{ $watched->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="text-green-600 font-semibold">+${{ number_format($watched->video->reward_amount, 2) }}</span>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-history text-gray-300 text-3xl mb-3"></i>
                        <p class="text-gray-500">No recent activity</p>
                        <p class="text-sm text-gray-400">Start watching videos to see your activity here</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
