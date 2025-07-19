{{-- resources/views/admin/user-details.blade.php --}}

@extends('layouts.app')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
    <div class="space-y-6">

        {{-- Back Link --}}
        <a href="{{ route('admin.users') }}" class="text-blue-600 hover:underline">&larr; Back to Users</a>

        {{-- Basic Info Card --}}
        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-2xl font-bold mb-4">Profile for {{ $user->name }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div><strong>Name:</strong> {{ $user->name }}</div>
                <div><strong>Email:</strong> {{ $user->email }}</div>
                <div><strong>Phone:</strong> {{ $user->phone_number ?? '—' }}</div>
                <div><strong>Role:</strong> {{ ucfirst($user->role) }}</div>
                <div>
                    <strong>Status:</strong>
                    @if($user->is_banned)
                        <span class="text-red-600">Banned</span>
                    @elseif(! $user->is_active)
                        <span class="text-gray-600">Inactive</span>
                    @else
                        <span class="text-green-600">Active</span>
                    @endif
                </div>
                <div><strong>Joined At:</strong> {{ $user->created_at->format('M d, Y') }}</div>
                @if($user->referredBy)
                    <div><strong>Referred By:</strong> {{ $user->referredBy->name }}</div>
                @endif
            </div>
        </div>

        {{-- Tabs: IP Tracking & Location Logs --}}
        <div class="bg-white rounded-2xl shadow p-6">
            <h3 class="text-xl font-semibold mb-4">Activity & Tracking</h3>

            {{-- IP Tracking --}}
            <div class="mb-6">
                <h4 class="font-medium text-gray-800 mb-2">IP Tracking Records</h4>
                @if($ipTrackings->isEmpty())
                    <p class="text-gray-500">No IP tracking records.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">IP Address</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">First Seen</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">Last Seen</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">Flagged</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($ipTrackings as $ip)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $ip->ip_address }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $ip->first_seen_at?->format('M d, Y H:i') }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $ip->last_seen_at?->format('M d, Y H:i') }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        @if($ip->is_flagged)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Yes</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">No</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Location Logs --}}
            <div>
                <h4 class="font-medium text-gray-800 mb-2">Location Logs</h4>
                @if($locationLogs->isEmpty())
                    <p class="text-gray-500">No location logs.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">When</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">IP</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">Location</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">Action</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($locationLogs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $log->created_at->format('M d, Y H:i') }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $log->ip_address }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">
                                        {{ $log->city }}, {{ $log->region }}, {{ $log->country }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ ucfirst($log->action_type) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
