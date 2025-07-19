{{-- resources/views/admin/analytics.blade.php --}}

@extends('layouts.app')

@section('title', 'Analytics')
@section('page-title', 'Platform Analytics')

@section('content')
    <div class="space-y-6">

        {{-- Key Metrics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total Users --}}
            <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition-shadow">
                <p class="text-sm text-gray-600">Total Users</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
            </div>

            {{-- Active Users (Last 7 days) --}}
            <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition-shadow">
                <p class="text-sm text-gray-600">Active Users (7d)</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($activeUsersWeek) }}</p>
            </div>

            {{-- Total Videos --}}
            <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition-shadow">
                <p class="text-sm text-gray-600">Total Videos</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalVideos) }}</p>
            </div>

            {{-- Videos Watched This Month --}}
            <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition-shadow">
                <p class="text-sm text-gray-600">Videos Watched (This Month)</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($videosThisMonth) }}</p>
            </div>
        </div>

        {{-- Revenue Summary --}}
        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition-shadow">
            <p class="text-sm text-gray-600">Total Revenue (Approved)</p>
            <p class="text-4xl font-bold text-green-600">${{ number_format($totalRevenue, 2) }}</p>
        </div>

        {{-- Detailed Charts / Tables Section --}}
        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition-shadow">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Detailed Analytics</h2>

            {{-- Example: Users Growth Table --}}
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">New Users</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">Videos Watched</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">Revenue</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($dailyStats as $date => $stats)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $date }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $stats['new_users'] }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $stats['videos_watched'] }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">${{ number_format($stats['revenue'], 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Placeholder for future charts --}}
            <div class="text-center text-gray-500 py-12 border-2 border-dashed rounded-xl">
                Charts coming soon...
            </div>
        </div>
    </div>
@endsection
