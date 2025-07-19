@extends('layouts.guest')

@section('title', 'Account Banned')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-red-50">
    <div class="max-w-md w-full">
        <!-- Ban icon -->
        <div class="text-center mb-8">
            <div class="mx-auto h-20 w-20 bg-red-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-ban text-red-600 text-3xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-red-900">Account Banned</h2>
            <p class="mt-2 text-sm text-red-600">Your account has been suspended</p>
        </div>

        <!-- Ban details -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-red-200">
            <div class="text-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Status</h3>
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <span class="font-medium text-red-800">Banned</span>
                    </div>
                    @if(auth()->user()->banned_at)
                        <p class="text-sm text-red-600">
                            Banned on: {{ auth()->user()->banned_at->format('M d, Y \a\t g:i A') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Ban reason -->
            @if(auth()->user()->ban_reason)
                <div class="mb-6">
                    <h4 class="font-medium text-gray-900 mb-2">Reason for Ban:</h4>
                    <div class="bg-gray-50 rounded-xl p-4 border">
                        <p class="text-gray-700">{{ auth()->user()->ban_reason }}</p>
                    </div>
                </div>
            @endif

            <!-- Banned by -->
            @if(auth()->user()->bannedBy)
                <div class="mb-6">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-user-shield mr-1"></i>
                        Banned by: <span class="font-medium">{{ auth()->user()->bannedBy->name }}</span>
                    </p>
                </div>
            @endif

            <!-- Contact support -->
            <div class="border-t pt-6">
                <h4 class="font-medium text-gray-900 mb-3">Need Help?</h4>
                <p class="text-sm text-gray-600 mb-4">
                    If you believe this ban was issued in error, please contact our support team.
                </p>
                <div class="space-y-2">
                    <a href="mailto:support@example.com" 
                       class="flex items-center text-blue-600 hover:text-blue-700 text-sm">
                        <i class="fas fa-envelope mr-2"></i>
                        support@example.com
                    </a>
                    <a href="tel:+1234567890" 
                       class="flex items-center text-blue-600 hover:text-blue-700 text-sm">
                        <i class="fas fa-phone mr-2"></i>
                        +1 (234) 567-890
                    </a>
                </div>
            </div>

            <!-- Logout button -->
            <div class="mt-6 pt-6 border-t">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-gray-600 text-white py-3 px-4 rounded-xl hover:bg-gray-700 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
