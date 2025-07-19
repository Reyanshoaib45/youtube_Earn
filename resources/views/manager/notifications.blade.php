@extends('layouts.app')

@section('title', 'Manager Notifications')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Your Notifications</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if ($notifications->isEmpty())
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
            <p class="text-gray-600 dark:text-gray-400">No notifications found.</p>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($notifications as $notification)
                    <div class="p-4 flex items-center justify-between {{ $notification->is_read ? 'bg-gray-50 dark:bg-gray-700' : 'bg-white dark:bg-gray-800' }}">
                        <div class="flex-1">
                            <div class="flex items-center mb-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full mr-2
                                    @if($notification->priority == 'high' || $notification->priority == 'critical') bg-red-100 text-red-800
                                    @elseif($notification->priority == 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($notification->priority) }}
                                </span>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">{{ $notification->title }}</h3>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $notification->message }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500">
                                {{ $notification->created_at?->diffForHumans() ?? 'N/A' }}
                                @if($notification->is_read)
                                    (Read {{ $notification->created_at?->diffForHumans() ?? 'N/A' }})
                                @endif
                            </p>
                            @if($notification->action_url && $notification->action_text)
                                <a href="{{ $notification->action_url }}" class="text-blue-600 hover:underline text-sm mt-2 inline-block">
                                    {{ $notification->action_text }}
                                </a>
                            @endif
                        </div>
                        @unless($notification->is_read)
                            <form action="{{ route('manager.notifications.read', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-1 px-3 rounded-full">
                                    Mark as Read
                                </button>
                            </form>
                        @endunless
                    </div>
                @endforeach
            </div>
            <div class="px-6 py-4">
                {{ $notifications->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
