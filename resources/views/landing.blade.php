@extends('layouts.app')

@section('title', 'Watch & Earn - Make Money Watching Videos')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-50 to-indigo-100 py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-6xl font-bold text-gray-800 mb-6">
                💰 Start with Rs. 1,000<br>
                <span class="text-green-600">Earn Rs. 2,100 in 7 days!</span>
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                Watch YouTube videos daily and earn guaranteed money. Simple, easy, and profitable!
                Join thousands of users already earning daily.
            </p>

            <!-- Key Benefits -->
            <div class="grid md:grid-cols-3 gap-6 mb-12 max-w-4xl mx-auto">
                <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg">
                    <h3 class="font-bold text-lg">🎯 Guaranteed Returns</h3>
                    <p>110% to 237% Profit!</p>
                </div>
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-6 py-4 rounded-lg">
                    <h3 class="font-bold text-lg">🔒 No Risk</h3>
                    <p>No hidden fees, secure platform</p>
                </div>
                <div class="bg-purple-100 border border-purple-400 text-purple-700 px-6 py-4 rounded-lg">
                    <h3 class="font-bold text-lg">⚡ Quick Start</h3>
                    <p>Start earning in 5 minutes</p>
                </div>
            </div>

            <div class="space-y-4">
                <a href="{{ route('register') }}"
                    class="bg-green-500 text-white px-8 py-4 rounded-lg text-xl font-semibold hover:bg-green-600 transition duration-300 inline-block">
                    🚀 Start Earning Now - Free Registration!
                </a>
                <p class="text-sm text-gray-600">
                    ✅ No credit card required • ✅ Instant activation • ✅ 24/7 support
                </p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">
                💡 How It Works - Simple 3 Steps
            </h2>
            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="text-center">
                    <div class="bg-indigo-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl font-bold text-indigo-600">1</span>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">💳 Choose Package</h3>
                    <p class="text-gray-600 text-lg">
                        Select any package from Rs.1,000 to Rs.8,000. All packages guarantee profit and have realistic
                        returns!
                    </p>
                </div>
                <div class="text-center">
                    <div class="bg-indigo-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl font-bold text-indigo-600">2</span>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">📺 Watch Videos</h3>
                    <p class="text-gray-600 text-lg">
                        Watch YouTube videos daily within your package limit. Earn Rs.60 per video with our anti-cheat
                        system!
                    </p>
                </div>
                <div class="text-center">
                    <div class="bg-indigo-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl font-bold text-indigo-600">3</span>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">💰 Earn & Withdraw</h3>
                    <p class="text-gray-600 text-lg">
                        Get guaranteed profit! Refer 3 friends to unlock withdrawal. Multiple payment methods available.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-gray-800 mb-4">
                📦 Choose Your Earning Package
            </h2>
            <p class="text-center text-gray-600 mb-12 text-lg">
                All packages guarantee profit - pick the one that fits your budget!
            </p>

            <!-- Calculation Formula -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-12 max-w-4xl mx-auto">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                    🧮 Earning Calculation Formula
                </h3>
                <div class="grid md:grid-cols-4 gap-6 text-center">
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h4 class="font-semibold text-blue-800 mb-2">Daily Videos</h4>
                        <p class="text-3xl font-bold text-blue-600">5-15</p>
                        <p class="text-sm text-gray-600">Based on package</p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h4 class="font-semibold text-green-800 mb-2">Per Video</h4>
                        <p class="text-3xl font-bold text-green-600">Rs. 60</p>
                        <p class="text-sm text-gray-600">Fixed reward</p>
                    </div>
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h4 class="font-semibold text-purple-800 mb-2">Duration</h4>
                        <p class="text-3xl font-bold text-purple-600">7-30</p>
                        <p class="text-sm text-gray-600">Days</p>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-lg">
                        <h4 class="font-semibold text-yellow-800 mb-2">Formula</h4>
                        <p class="text-lg font-bold text-yellow-600">Videos × Days × Rs.60</p>
                        <p class="text-sm text-gray-600">= Total earnings</p>
                    </div>
                </div>
            </div>

            <!-- Packages Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
                @forelse($packages as $package)
                    <div
                        class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition duration-300 border-2 border-transparent hover:border-indigo-200 overflow-hidden transform hover:-translate-y-2">
                        <!-- Package Header -->
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-6 text-center">
                            <h3 class="text-2xl font-bold mb-2">{{ $package->name }}</h3>
                            <div class="text-5xl font-bold mb-2">Rs. {{ number_format($package->price) }}</div>
                            <p class="text-indigo-100">{{ $package->description ?? 'Great earning opportunity!' }}</p>
                        </div>

                        <!-- Package Details -->
                        <div class="p-6">
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">📹 Daily Videos:</span>
                                    <span class="font-semibold text-lg">{{ $package->daily_video_limit }} videos</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">⏰ Duration:</span>
                                    <span class="font-semibold text-lg">{{ $package->duration_days }} days</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">💰 Per Video:</span>
                                    <span class="font-semibold text-green-600 text-lg">Rs. 60</span>
                                </div>
                                <div class="flex justify-between items-center border-t pt-3">
                                    <span class="text-gray-600">🎯 Total Earning:</span>
                                    <span class="font-bold text-green-600 text-xl">Rs.
                                        {{ number_format($package->total_reward) }}</span>
                                </div>
                                <div class="flex justify-between items-center bg-green-50 p-4 rounded-lg">
                                    <span class="text-green-700 font-semibold">🚀 Your Profit:</span>
                                    <span class="font-bold text-green-700 text-2xl">Rs.
                                        {{ number_format($package->total_reward - $package->price) }}</span>
                                </div>
                                <div class="text-center">
                                    <span
                                        class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-lg font-semibold">
                                        {{ number_format((($package->total_reward - $package->price) / $package->price) * 100, 0) }}%
                                        ROI
                                    </span>
                                </div>
                            </div>

                            <!-- Calculation Breakdown -->
                            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                                <h4 class="font-semibold text-gray-700 mb-2">📊 Calculation:</h4>
                                <p class="text-gray-600">
                                    {{ $package->daily_video_limit }} videos × {{ $package->duration_days }} days × Rs.60 =
                                    <span class="font-bold text-green-600">Rs.
                                        {{ number_format($package->total_reward) }}</span>
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    Profit: Rs.{{ number_format($package->total_reward - $package->price) }}
                                    ({{ number_format((($package->total_reward - $package->price) / $package->price) * 100, 0) }}%
                                    return)
                                </p>
                            </div>

                            <!-- Action Button -->
                            <a href="https://wa.me/+923327257594?text=I want to buy {{ $package->name }} for Rs.{{ number_format($package->price) }}"
                                class="w-full bg-green-500 text-white py-4 rounded-lg font-semibold hover:bg-green-600 transition duration-300 inline-block text-center text-lg">
                                💬 Buy via WhatsApp
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-xl">No packages available at the moment.</p>
                        <p class="text-gray-400 mt-2">Please check back later or contact support.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">
                💬 What Our Users Say
            </h2>
            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <div class="bg-white p-8 rounded-lg shadow-lg border border-gray-200">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                            <span class="font-bold text-indigo-600 text-xl">AH</span>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-lg">Ahmed Hassan</h4>
                            <p class="text-gray-600">Karachi, Pakistan</p>
                            <div class="text-yellow-400">⭐⭐⭐⭐⭐</div>
                        </div>
                    </div>
                    <p class="text-gray-600 text-lg italic">
                        "I started with Rs.1000 and earned Rs.2100 in just 7 days! This platform is amazing and actually
                        pays. Highly recommended!"
                    </p>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg border border-gray-200">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="font-bold text-green-600 text-xl">SF</span>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-lg">Sara Fatima</h4>
                            <p class="text-gray-600">Lahore, Pakistan</p>
                            <div class="text-yellow-400">⭐⭐⭐⭐⭐</div>
                        </div>
                    </div>
                    <p class="text-gray-600 text-lg italic">
                        "Premium package gave me Rs.8400 profit! Best investment I ever made. The referral system is also
                        great for extra income."
                    </p>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg border border-gray-200">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="font-bold text-purple-600 text-xl">MA</span>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-lg">Muhammad Ali</h4>
                            <p class="text-gray-600">Islamabad, Pakistan</p>
                            <div class="text-yellow-400">⭐⭐⭐⭐⭐</div>
                        </div>
                    </div>
                    <p class="text-gray-600 text-lg italic">
                        "Diamond package earned me Rs.27000! Now I'm buying more packages and referring friends. This is
                        life-changing!"
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-6">
                🎯 Ready to Start Your Earning Journey?
            </h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Join thousands of users who are already earning daily. Start with any package and see guaranteed returns!
            </p>
            <div class="space-y-4">
                <a href="{{ route('register') }}"
                    class="bg-white text-indigo-600 px-8 py-4 rounded-lg text-xl font-semibold hover:bg-gray-100 transition duration-300 inline-block">
                    🚀 Register Free & Start Earning
                </a>
                <p class="text-indigo-200">
                    ✅ Instant activation • ✅ No hidden fees • ✅ Guaranteed profits
                </p>
            </div>
        </div>
    </section>
@endsection
