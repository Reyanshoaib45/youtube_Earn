@extends('layouts.app')

@section('title', 'Register - Watch & Earn')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
        <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md">
            <div class="text-center mb-8">
                <div class="text-6xl mb-4">🎉</div>
                <h1 class="text-3xl font-bold text-gray-800">Join Watch & Earn!</h1>
                <p class="text-gray-600 mt-2">Start earning money by watching videos</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        👤 Full Name
                    </label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-300"
                        value="{{ old('name') }}" placeholder="Enter your full name">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        📧 Email Address
                    </label>
                    <input type="email" name="email" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-300"
                        value="{{ old('email') }}" placeholder="Enter your email address">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Phone Number
                    </label>
                    <input type="number" name="phone" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-300"
                        value="{{ old('phone') }}" placeholder="Enter your phone Number">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        🔒 Password
                    </label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-300"
                        placeholder="Create a strong password">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        🔒 Confirm Password
                    </label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-300"
                        placeholder="Confirm your password">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        🎁 Referral Code (Optional)
                    </label>
                    <input type="text" name="referral_code"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-300"
                        value="{{ request('referral_code', old('referral_code')) }}"
                        placeholder="Enter referral code if you have one">
                    <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-700">
                            💰 <strong>Referral Bonus:</strong> Your referrer gets Rs. 50 when you join!
                        </p>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold text-lg">
                    🚀 Register & Start Earning
                </button>
            </form>

            <div class="text-center mt-8">
                <p class="text-gray-600">Already have an account?
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                        Login here
                    </a>
                </p>
            </div>

            <!-- Benefits -->
            <div class="mt-8 p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-2">✨ Why Join Us?</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p>✅ Guaranteed profits (110% - 237% ROI)</p>
                    <p>✅ Daily earning opportunities</p>
                    <p>✅ Referral system (Rs. 50 per referral)</p>
                    <p>✅ Multiple withdrawal methods</p>
                    <p>✅ 24/7 customer support</p>
                </div>
            </div>
        </div>
    </div>
@endsection
