@extends('layouts.app')

@section('title', 'Login - Watch & Earn')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md">
        <div class="text-center mb-8">
            <div class="text-6xl mb-4">🔐</div>
            <h1 class="text-3xl font-bold text-gray-800">Welcome Back!</h1>
            <p class="text-gray-600 mt-2">Login to continue earning money</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    📧 Email Address
                </label>
                <input type="email" name="email" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-300"
                       value="{{ old('email') }}"
                       placeholder="Enter your email address">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    🔒 Password
                </label>
                <input type="password" name="password" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-300"
                       placeholder="Enter your password">
            </div>

            <button type="submit" 
                    class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold text-lg">
                🚀 Login & Start Earning
            </button>
        </form>

        <div class="text-center mt-8">
            <p class="text-gray-600">Don't have an account? 
                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                    Register here for free
                </a>
            </p>
        </div>

        <!-- Demo Accounts Info -->
        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-semibold text-gray-700 mb-2">🎯 Demo Accounts:</h3>
            <div class="text-sm text-gray-600 space-y-1">
                <p><strong>Admin:</strong> admin@watchearn.com / password</p>
                <p><strong>Manager:</strong> manager@watchearn.com / password</p>
                <p><strong>User:</strong> user@test.com / password</p>
            </div>
        </div>
    </div>
</div>
@endsection
