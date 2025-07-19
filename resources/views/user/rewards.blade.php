@extends('layouts.app')

@section('title', 'Rewards History')
@section('page-title', 'Rewards History')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Rewards History</h1>
        <p class="mt-2 text-gray-600">Track your earnings from watched videos</p>
    </div>

    <!-- Stats cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total earned -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-2xl">
                    <i class="fas fa-coins text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Earned</p>
                    <p class="text-2xl font-bold text-green-600">${{ number_format($user->reward_balance + $user->withdrawals->sum('amount'), 2) }}</p>
                </div>
            </div>
        </div>
        
        <!-- Current balance -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-2xl">
                    <i class="fas fa-wallet text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Current Balance</p>
                    <p class="text-2xl font-bold text-blue-600">${{ number_format($user->reward_balance, 2) }}</p>
                </div>
            </div>
        </div>
        
        <!-- Videos watched -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-2xl">
                    <i class="fas fa-play-circle text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Videos Watched</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $watchedVideos->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Rewards history -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-history text-gray-600 mr-2"></i>Rewards History
        </h2>
        
        @if($watchedVideos->isEmpty())
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-video text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Rewards Yet</h3>
                <p class="text-gray-600 mb-6">Start watching videos to earn rewards</p>
                <a href="{{ route('user.videos') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-colors">
                    <i class="fas fa-play mr-2"></i>Watch Videos
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Video</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Date</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Watch Time</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Reward</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($watchedVideos as $watched)
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden mr-3">
                                            <img src="https://img.youtube.com/vi/{{ preg_replace('/.*(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+).*/', '$1', $watched->video->youtube_link) }}/default.jpg" 
                                                 alt="{{ $watched->video->title }}" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 line-clamp-1">{{ $watched->video->title }}</p>
                                            <p class="text-xs text-gray-500">{{ $watched->video->min_watch_minutes }} min required</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div>
                                        <p class="text-sm text-gray-900">{{ $watched->updated_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $watched->updated_at->format('h:i A') }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock text-blue-600 mr-2"></i>
                                        <span class="text-sm text-gray-900">
                                            {{ floor($watched->watched_seconds / 60) }}:{{ str_pad($watched->watched_seconds % 60, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="font-semibold text-green-600">${{ number_format($watched->video->reward_amount, 2) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
