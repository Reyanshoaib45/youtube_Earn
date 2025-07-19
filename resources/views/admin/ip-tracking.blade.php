@extends('layouts.app')

@section('title', 'IP Tracking')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">IP Address Tracking</h1>

    <div class="flex justify-between items-center mb-6">
        <form action="{{ route('admin.ip.tracking') }}" method="GET" class="flex items-center space-x-4">
            <input type="text" name="search" placeholder="Search IP, location, or user..."
                   class="form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-white"
                   value="{{ request('search') }}">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">
                Search
            </button>
        </form>
    </div>

    @if ($ipTrackings->isEmpty())
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
            <p class="text-gray-600 dark:text-gray-400">No IP tracking data found.</p>
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
                                IP Address
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Location
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Coordinates
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                First Seen
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($ipTrackings as $tracking)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                    {{ $tracking->user->name ?? 'Unknown User' }}
                                    <br>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $tracking->user->email ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $tracking->ip_address }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $tracking->city ?? 'Unknown' }}, {{ $tracking->region ?? '' }}<br>
                                    {{ $tracking->country ?? 'Unknown' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">
                                    @if($tracking->latitude && $tracking->longitude)
                                        {{ $tracking->latitude }}, {{ $tracking->longitude }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $tracking->created_at->format('M d, Y H:i A') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $ipTrackings->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
