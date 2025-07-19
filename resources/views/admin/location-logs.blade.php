@extends('layouts.app')

@section('title', 'Location Logs')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">User Location Logs</h1>

    <div class="flex justify-between items-center mb-6">
        <form action="{{ route('admin.location.logs') }}" method="GET" class="flex items-center space-x-4">
            <input type="text" name="search" placeholder="Search user, IP, or location..."
                   class="form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-white"
                   value="{{ request('search') }}">
            <select name="action" class="form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                <option value="">All Actions</option>
                <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                <option value="video_watch_started" {{ request('action') == 'video_watch_started' ? 'selected' : '' }}>Video Watch Started</option>
                <option value="video_completed" {{ request('action') == 'video_completed' ? 'selected' : '' }}>Video Completed</option>
                <option value="withdrawal_requested" {{ request('action') == 'withdrawal_requested' ? 'selected' : '' }}>Withdrawal Requested</option>
            </select>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">
                Filter
            </button>
        </form>
    </div>

    @if ($locationLogs->isEmpty())
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
            <p class="text-gray-600 dark:text-gray-400">No location logs found.</p>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Action
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                IP Address
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Location
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Details
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Timestamp
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($locationLogs as $log)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                    {{ $log->user->name ?? 'Unknown User' }}
                                    <br>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $log->user->email ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($log->action == 'login') bg-green-100 text-green-800
                                        @elseif($log->action == 'logout') bg-red-100 text-red-800
                                        @elseif(str_contains($log->action, 'video')) bg-blue-100 text-blue-800
                                        @elseif(str_contains($log->action, 'withdrawal')) bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $log->city ?? 'Unknown' }}, {{ $log->country ?? 'Unknown' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $log->details ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $log->created_at->format('M d, Y H:i A') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $locationLogs->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
