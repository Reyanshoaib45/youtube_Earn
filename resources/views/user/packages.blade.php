@extends('layouts.app')

@section('title', 'Packages - Watch & Earn')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Choose Your Package</h2>
        <p class="text-gray-600">Select a package to start earning money by watching videos</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if($userPackage)
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
            <p class="font-bold">Current Active Package: {{ $userPackage->name }}</p>
            <p>You already have an active package. You can purchase a new one after it expires.</p>
        </div>
    @endif

    @if($pendingPurchases->count() > 0)
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
            <h3 class="font-bold mb-2">⏳ Pending Purchase Requests</h3>
            @foreach($pendingPurchases as $purchase)
                <div class="flex justify-between items-center bg-white rounded p-3 mb-2">
                    <div>
                        <span class="font-semibold">{{ $purchase->package->name }}</span>
                        <span class="text-sm text-gray-600">- Rs. {{ number_format($purchase->amount) }}</span>
                    </div>
                    <div class="text-right">
                        <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-xs">{{ $purchase->status_icon }} {{ ucfirst($purchase->status) }}</span>
                        <div class="text-xs text-gray-500">{{ $purchase->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
            @endforeach
            <p class="text-sm mt-2">Your purchase requests are being reviewed by our managers.</p>
        </div>
    @endif

    <!-- Reward Calculation Example -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-800 mb-4">💰 Reward Calculation Example</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Package</th>
                        <th class="px-4 py-2 text-left">Daily Videos</th>
                        <th class="px-4 py-2 text-left">Per Video Reward</th>
                        <th class="px-4 py-2 text-left">Duration</th>
                        <th class="px-4 py-2 text-left">Total Earning</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="px-4 py-2 font-semibold">Rs. 1000</td>
                        <td class="px-4 py-2">5 videos/day</td>
                        <td class="px-4 py-2 text-green-600">Rs. 60</td>
                        <td class="px-4 py-2">7 days</td>
                        <td class="px-4 py-2 font-bold text-green-600">Rs. 2100</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2 font-semibold">Rs. 2000</td>
                        <td class="px-4 py-2">7 videos/day</td>
                        <td class="px-4 py-2 text-green-600">Rs. 80</td>
                        <td class="px-4 py-2">7 days</td>
                        <td class="px-4 py-2 font-bold text-green-600">Rs. 3920</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Packages Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($packages as $package)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 {{ ($userPackage || $pendingPurchases->count() > 0) ? 'opacity-50' : '' }}">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-4">
                <h3 class="text-xl font-bold">{{ $package->name }}</h3>
                <div class="text-3xl font-bold mt-2">Rs. {{ number_format($package->price) }}</div>
            </div>
            
            <div class="p-6">
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Daily Video Limit:</span>
                        <span class="font-semibold">{{ $package->daily_video_limit }} videos</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Duration:</span>
                        <span class="font-semibold">{{ $package->duration_days }} days</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Reward:</span>
                        <span class="font-semibold text-green-600">Rs. {{ number_format($package->total_reward) }}</span>
                    </div>
                    <div class="flex justify-between items-center border-t pt-3">
                        <span class="text-gray-600 font-semibold">Your Profit:</span>
                        <span class="font-bold text-green-600 text-lg">Rs. {{ number_format($package->total_reward - $package->price) }}</span>
                    </div>
                </div>

                @if(!$userPackage && $pendingPurchases->count() == 0)
                    <form method="POST" action="{{ route('user.buy-package') }}">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $package->id }}">
                        <button type="submit" 
                                class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition duration-300">
                            💳 Purchase Package
                        </button>
                    </form>
                @else
                    <button disabled 
                            class="w-full bg-gray-400 text-white py-3 rounded-lg font-semibold cursor-not-allowed">
                        {{ $userPackage ? 'Already Have Package' : 'Purchase Pending' }}
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- How It Works -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
        <h3 class="text-xl font-bold text-gray-800 mb-4">📋 How It Works</h3>
        <div class="grid md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="bg-indigo-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-xl font-bold text-indigo-600">1</span>
                </div>
                <h4 class="font-semibold mb-2">Choose Package</h4>
                <p class="text-sm text-gray-600">Select a package that fits your budget</p>
            </div>
            <div class="text-center">
                <div class="bg-indigo-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-xl font-bold text-indigo-600">2</span>
                </div>
                <h4 class="font-semibold mb-2">Pay via JazzCash</h4>
                <p class="text-sm text-gray-600">Send money to our JazzCash number</p>
            </div>
            <div class="text-center">
                <div class="bg-indigo-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-xl font-bold text-indigo-600">3</span>
                </div>
                <h4 class="font-semibold mb-2">Wait for Approval</h4>
                <p class="text-sm text-gray-600">Manager will verify and approve your payment</p>
            </div>
            <div class="text-center">
                <div class="bg-indigo-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-xl font-bold text-indigo-600">4</span>
                </div>
                <h4 class="font-semibold mb-2">Start Earning</h4>
                <p class="text-sm text-gray-600">Watch videos and earn money daily</p>
            </div>
        </div>
    </div>
</div>
@endsection
