@extends('layouts.app')

@section('title', 'Packages - Watch & Earn')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">📦 Choose Your Package</h2>
            <p class="text-gray-600">Select a package to start earning money by watching videos</p>
        </div>

        @if ($pendingPurchases->count() > 0)
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                <h3 class="font-bold">⏳ Pending Purchase Requests</h3>
                @foreach ($pendingPurchases as $request)
                    <p class="mt-2">
                        <strong>{{ $request->package->name }}</strong> - Rs. {{ number_format($request->amount) }}
                        <span class="text-sm">(Submitted: {{ $request->created_at->format('M d, Y h:i A') }})</span>
                    </p>
                @endforeach
                <p class="text-sm mt-2">Please wait for manager approval. You will be notified once approved.</p>
            </div>
        @endif

        @if ($userPackage)
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <h3 class="font-bold">✅ Your Active Package</h3>
                <p><strong>{{ $userPackage->name }}</strong> - Expires:
                    {{ auth()->user()->purchases()->where('expires_at', '>', now())->latest()->first()->expires_at->format('M d, Y') }}
                </p>
            </div>
        @endif

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($packages as $package)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6">
                        <h3 class="text-xl font-bold mb-2">{{ $package->name }}</h3>
                        <div class="text-3xl font-bold">Rs. {{ number_format($package->price) }}</div>
                        <div class="text-blue-100">{{ $package->duration_days }} days</div>
                    </div>

                    <div class="p-6">
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Daily Videos:</span>
                                <span class="font-semibold">{{ $package->daily_video_limit }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Reward:</span>
                                <span class="font-semibold text-green-600">Rs.
                                    {{ number_format($package->total_reward) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Profit:</span>
                                <span class="font-semibold text-green-600">Rs.
                                    {{ number_format($package->total_reward - $package->price) }}</span>
                            </div>
                        </div>

                        @if ($userPackage)
                            <button disabled class="w-full bg-gray-400 text-white py-2 px-4 rounded-lg cursor-not-allowed">
                                Already Have Package
                            </button>
                        @elseif($pendingPurchases->count() > 0)
                            <button disabled
                                class="w-full bg-yellow-400 text-white py-2 px-4 rounded-lg cursor-not-allowed">
                                Purchase Pending
                            </button>
                        @else
                            <form method="POST" action="{{ route('user.buy-package') }}">
                                @csrf
                                <input type="hidden" name="package_id" value="{{ $package->id }}">
                                <button type="submit"
                                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition duration-300">
                                    Purchase Package
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if ($packages->count() == 0)
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No packages available at the moment</p>
                <p class="text-gray-400 mt-2">Please check back later</p>
            </div>
        @endif
    </div>
@endsection
