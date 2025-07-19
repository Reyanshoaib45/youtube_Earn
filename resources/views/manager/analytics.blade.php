@extends('layouts.app')

@section('title', 'Manager Analytics')
@section('page-title', 'Analytics Dashboard')

@section('content')
    <div class="space-y-6">

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-gray-500 text-sm">Total Users</p>
                <h3 class="text-2xl font-semibold text-gray-900">{{ $totalUsers }}</h3>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-gray-500 text-sm">Total Videos</p>
                <h3 class="text-2xl font-semibold text-gray-900">{{ $totalVideos }}</h3>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-gray-500 text-sm">Total Earnings</p>
                <h3 class="text-2xl font-semibold text-green-600">${{ number_format($totalEarnings, 2) }}</h3>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-gray-500 text-sm">Total Withdrawals</p>
                <h3 class="text-2xl font-semibold text-red-600">${{ number_format($totalWithdrawals, 2) }}</h3>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-gray-500 text-sm">Total Referrals</p>
                <h3 class="text-2xl font-semibold text-blue-600">{{ $totalReferrals }}</h3>
            </div>
        </div>

        {{-- Monthly Trends --}}
        <div class="bg-white rounded-xl shadow p-6 mt-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Monthly Users (Last 6 Months)</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2">Month</th>
                        <th class="text-left px-4 py-2">Users Registered</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($monthlyUsers as $data)
                        <tr>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::create()->month($data->month)->format('F') }}</td>
                            <td class="px-4 py-2">{{ $data->count }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 mt-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Monthly Earnings (Last 6 Months)</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2">Month</th>
                        <th class="text-left px-4 py-2">Total Earnings</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($monthlyEarnings as $data)
                        <tr>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::create()->month($data->month)->format('F') }}</td>
                            <td class="px-4 py-2">${{ number_format($data->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
