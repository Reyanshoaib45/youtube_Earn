@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center">
        <!-- 404 illustration -->
        <div class="mb-8">
            <div class="mx-auto h-32 w-32 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                <i class="fas fa-search text-white text-4xl"></i>
            </div>
            <h1 class="text-6xl font-bold text-gray-900 mb-2">404</h1>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Page Not Found</h2>
            <p class="text-gray-600 mb-8">
                Sorry, we couldn't find the page you're looking for. 
                It might have been moved, deleted, or you entered the wrong URL.
            </p>
        </div>

        <!-- Action buttons -->
        <div class="space-y-4">
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-500 text-white rounded-2xl hover:bg-gray-600 transition-colors mr-4">
                <i class="fas fa-arrow-left mr-2"></i>Go Back
            </a>
            
            @auth
                <a href="{{ route(auth()->user()->role . '.dashboard') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl hover:from-blue-700 hover:to-purple-700 transition-all">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl hover:from-blue-700 hover:to-purple-700 transition-all">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection
