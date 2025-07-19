
{{-- File: resources/views/user/profile.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">

        {{-- Page Title --}}
        <h1 class="text-3xl font-bold text-gray-800 mb-6">My Profile</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- User Info Card --}}
            <div class="col-span-1 bg-white shadow rounded-lg p-6">
                <div class="flex items-center space-x-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff" alt="Avatar" class="w-16 h-16 rounded-full border shadow">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-700">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-500 text-sm">{{ Auth::user()->email }}</p>
                        <p class="text-gray-500 text-sm">{{ Auth::user()->phone ?? 'No phone provided' }}</p>
                    </div>
                </div>

                <div class="mt-4 text-sm text-gray-600">
                    <p><b>Joined:</b> {{ Auth::user()->created_at->format('d M Y') }} ({{ Auth::user()->created_at->diffForHumans() }})</p>
                </div>

                <div class="mt-6">
                    <a href="#" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition text-sm">Edit Profile</a>
                </div>
            </div>

            {{-- Rewards / Package / Referrals Summary --}}
            <div class="col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Package Info --}}
                <div class="bg-white shadow rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Active Package</h3>
                    @php
                        $userPackage = Auth::user()->activePackage?->with('package')->first();
                    @endphp

                    @if($userPackage && $userPackage->package)
                        <p class="text-sm text-gray-600">
                            <b>Name:</b> {{ $userPackage->package->name }}<br>
                            <b>Price:</b> Rs. {{ $userPackage->package->price }}<br>
                            <b>Started:</b> {{ $userPackage->start_date->format('d M Y') }}<br>
                            <b>Expires:</b> {{ $userPackage->end_date->format('d M Y') }}
                        </p>
                    @else
                        <p class="text-sm text-gray-500">No active package</p>
                    @endif
                </div>

                {{-- Rewards Summary --}}
                <div class="bg-white shadow rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Rewards Summary</h3>
                    <p class="text-sm text-gray-600">
                        <b>Total Earnings:</b> Rs. {{ Auth::user()->reward_balance }}<br>
                        <b>Monthly Earnings:</b> Rs. {{ Auth::user()->earnings()->whereMonth('created_at', now()->month)->sum('amount') }}<br>
                        <b>Today:</b> Rs. {{ Auth::user()->earnings()->whereDate('created_at', today())->sum('amount') }}
                    </p>
                </div>

                {{-- Withdrawals Summary --}}
                <div class="bg-white shadow rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Withdrawals</h3>
                    <p class="text-sm text-gray-600">
                        <b>Total:</b> Rs. {{ Auth::user()->withdrawals()->where('status', 'approved')->sum('amount') }}<br>
                        <b>Pending:</b> Rs. {{ Auth::user()->withdrawals()->where('status', 'pending')->sum('amount') }}
                    </p>
                </div>

                {{-- Referrals --}}
                <div class="bg-white shadow rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">My Referrals</h3>
                    <p class="text-sm text-gray-600">
                        <b>Total Referred Users:</b> {{ Auth::user()->referrals()->count() }}
                    </p>
                </div>
            </div>
        </div>

    </div>
@endsection
