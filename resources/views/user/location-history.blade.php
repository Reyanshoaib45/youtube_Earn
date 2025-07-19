@extends('layouts.app')

@section('title', 'Location History')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Your Location History</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Location Logs -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Recent Activity Logs</h2>
            </div>
            <div class="p-6">
                @if ($locationLogs->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400 text-center py-8">No location logs found.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($locationLogs as $log)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($log->action == 'login') bg-green-100 text-green-800
                                        @elseif($log->action == 'logout') bg-red-100 text-red-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $log->created_at->format('M d, Y H:i A') }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    <p><strong>Location:</strong> {{ $log->city ?? 'Unknown' }}, {{ $log->country ?? 'Unknown' }}</p>
                                    <p><strong>IP Address:</strong> {{ $log->ip_address }}</p>
                                    @if($log->details)
                                        <p><strong>Details:</strong> {{ $log->details }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $locationLogs->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- IP Tracking -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">IP Address History</h2>
            </div>
            <div class="p-6">
                @if ($ipTrackings->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400 text-center py-8">No IP tracking data found.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($ipTrackings as $tracking)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-semibold text-gray-800 dark:text-white">{{ $tracking->ip_address }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $tracking->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    <p><strong>Location:</strong> {{ $tracking->city ?? 'Unknown' }}, {{ $tracking->region ?? '' }} {{ $tracking->country ?? 'Unknown' }}</p>
                                    @if($tracking->latitude && $tracking->longitude)
                                        <p><strong>Coordinates:</strong> {{ $tracking->latitude }}, {{ $tracking->longitude }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $ipTrackings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Current Location Info -->
    <div class="mt-6 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-2">Current Session Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-blue-700 dark:text-blue-300"><strong>Current Location:</strong> {{ auth()->user()->current_location ?? 'Unknown' }}</p>
                <p class="text-blue-700 dark:text-blue-300"><strong>Last Login:</strong> {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('M d, Y H:i A') : 'Never' }}</p>
            </div>
            <div>
                <p class="text-blue-700 dark:text-blue-300"><strong>Last Login IP:</strong> {{ auth()->user()->last_login_ip ?? 'Unknown' }}</p>
                <p class="text-blue-700 dark:text-blue-300"><strong>Account Created:</strong> {{ auth()->user()->created_at->format('M d, Y H:i A') }}</p>
            </div>
        </div>
    </div>

    <!-- Privacy Notice -->
    <div class="mt-6 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Privacy Notice</h3>
                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                    <p>We track your location and IP address for security purposes and to prevent fraud. This information helps us protect your account and ensure platform integrity.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
