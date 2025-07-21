@extends('layouts.app')

@section('title', 'Purchase Requests - Manager Panel')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">💳 Purchase Request Management</h2>
            <p class="text-gray-600">Review and approve user package purchase requests</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Manager Dashboard</h2>
            <p class="text-gray-600">Manage videos, packages, and user withdrawals</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Videos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalVideos }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Packages</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalPackages }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Withdrawals</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingWithdrawals }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📹 Video Management</h3>
                <p class="text-gray-600 mb-4">Add, edit, or remove YouTube videos with custom rewards</p>
                <a href="{{ route('manager.videos') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                    Manage Videos
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📦 Package Management</h3>
                <p class="text-gray-600 mb-4">Create and manage earning packages for users</p>
                <a href="{{ route('manager.packages') }}"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-300">
                    Manage Packages
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">💰 Withdrawal Requests</h3>
                <p class="text-gray-600 mb-4">Review and approve user withdrawal requests</p>
                <a href="{{ route('manager.withdrawals') }}"
                    class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition duration-300">
                    Review Withdrawals
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📊 System Overview</h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-700 mb-3">Platform Statistics</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Active Videos:</span>
                            <span class="font-semibold">{{ $totalVideos }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Available Packages:</span>
                            <span class="font-semibold">{{ $totalPackages }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Registered Users:</span>
                            <span class="font-semibold">{{ $totalUsers }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pending Requests:</span>
                            <span class="font-semibold text-yellow-600">{{ $pendingWithdrawals }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700 mb-3">Quick Actions</h4>
                    <div class="space-y-2">
                        <button
                            class="w-full text-left px-3 py-2 bg-gray-50 rounded hover:bg-gray-100 transition duration-200">
                            + Add New Video
                        </button>
                        <button
                            class="w-full text-left px-3 py-2 bg-gray-50 rounded hover:bg-gray-100 transition duration-200">
                            + Create Package
                        </button>
                        <button
                            class="w-full text-left px-3 py-2 bg-gray-50 rounded hover:bg-gray-100 transition duration-200">
                            📋 View All Withdrawals
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
