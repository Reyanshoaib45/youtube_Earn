@extends('layouts.app')

@section('title', 'Packages')
@section('page-title', 'Packages')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Choose Your Package</h1>
            <p class="mt-2 text-gray-600">Select the perfect package to start earning rewards</p>
        </div>
    </div>

    <!-- Current package alert -->
    @if($userPackage)
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-blue-800 mb-2">Current Active Package</h3>
                    <p class="text-blue-700 mb-2">
                        You currently have the <strong>{{ $userPackage->package->name }}</strong> package active until
                        <strong>{{ $userPackage->end_date->format('M d, Y') }}</strong>.
                    </p>
                    <p class="text-sm text-blue-600">
                        <i class="fas fa-lightbulb mr-1"></i>
                        Purchasing a new package will replace your current one.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Packages grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($packages as $package)
            <div class="relative bg-white rounded-2xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:scale-105 {{ $userPackage && $userPackage->package_id === $package->id ? 'ring-2 ring-blue-500' : '' }}">
                @if($userPackage && $userPackage->package_id === $package->id)
                    <div class="absolute top-4 right-4 bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                        <i class="fas fa-crown mr-1"></i>Current
                    </div>
                @endif



        <!-- Package header -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white text-center">
                    <h3 class="text-2xl font-bold mb-2">{{ $package->name }}</h3>
                    <div class="mb-2">
                        <span class="text-4xl font-bold">${{ number_format($package->price, 0) }}</span>
                    </div>
                    <p class="text-blue-100">{{ $package->validity_days }} days validity</p>
                </div>

                <!-- Package features -->
                <div class="p-6">
                    <ul class="space-y-4 mb-6">
                        <li class="flex items-center">
                            <div class="flex-shrink-0 w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-gray-700">Up to <strong>{{ $package->video_limit }}</strong> videos</span>
                        </li>
                        <li class="flex items-center">
                            <div class="flex-shrink-0 w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-gray-700"><strong>${{ number_format($package->reward_per_video, 2) }}</strong> per video</span>
                        </li>
                        <li class="flex items-center">
                            <div class="flex-shrink-0 w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-gray-700">Min withdrawal: <strong>${{ number_format($package->min_withdrawal, 2) }}</strong></span>
                        </li>
                        <li class="flex items-center">
                            <div class="flex-shrink-0 w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-gray-700"><strong>{{ $package->validity_days }}</strong> days access</span>
                        </li>
                    </ul>

                    <!-- Purchase button -->
                    @if($userPackage && $userPackage->package_id === $package->id)
                        <button disabled class="w-full bg-gray-300 text-gray-500 py-3 px-4 rounded-2xl font-medium cursor-not-allowed">
                            <i class="fas fa-check mr-2"></i>Current Package
                        </button>
                    @else
                        <form action="{{ route('user.package.purchase', $package) }}" method="POST"
                              x-data="{ loading: false }"
                              @submit="loading = true; showLoading(true)">
                            @csrf
                            <button type="submit"
                                    :disabled="loading"
                                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-2xl font-medium hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50">
                                <span x-show="!loading">
                                    <i class="fas fa-shopping-cart mr-2"></i>Purchase Package
                                </span>
                                <span x-show="loading" class="flex items-center justify-center">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                    Processing...
                                </span>
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Potential earnings -->
                <div class="bg-gray-50 px-6 py-4">
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Potential Earnings</p>
                        <p class="text-lg font-bold text-green-600">
                            Up to ${{ number_format($package->video_limit * $package->reward_per_video, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Package comparison -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-chart-bar text-blue-600 mr-2"></i>Package Comparison
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-medium text-gray-900">Feature</th>
                        @foreach($packages as $package)
                            <th class="text-center py-3 px-4 font-medium text-gray-900">{{ $package->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="py-3 px-4 text-gray-700">Price</td>
                        @foreach($packages as $package)
                            <td class="text-center py-3 px-4 font-semibold text-blue-600">${{ number_format($package->price, 2) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-gray-700">Video Limit</td>
                        @foreach($packages as $package)
                            <td class="text-center py-3 px-4">{{ $package->video_limit }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-gray-700">Reward per Video</td>
                        @foreach($packages as $package)
                            <td class="text-center py-3 px-4 font-semibold text-green-600">${{ number_format($package->reward_per_video, 2) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-gray-700">Min Withdrawal</td>
                        @foreach($packages as $package)
                            <td class="text-center py-3 px-4">${{ number_format($package->min_withdrawal, 2) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-gray-700">Validity</td>
                        @foreach($packages as $package)
                            <td class="text-center py-3 px-4">{{ $package->validity_days }} days</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
