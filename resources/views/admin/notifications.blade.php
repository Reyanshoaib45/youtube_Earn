@extends('layouts.app')

@section('title', 'Admin Notifications')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Admin Notifications</h1>

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

    <div class="flex justify-between items-center mb-6">
        <form action="{{ route('admin.notifications') }}" method="GET" class="flex items-center space-x-4">
            <input type="text" name="search" placeholder="Search notifications..."
                   class="form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-white"
                   value="{{ request('search') }}">
            <select name="type" class="form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                <option value="">All Types</option>
                <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                <option value="payment_submitted" {{ request('type') == 'payment_submitted' ? 'selected' : '' }}>Payment Submitted</option>
                <option value="withdrawal_requested" {{ request('type') == 'withdrawal_requested' ? 'selected' : '' }}>Withdrawal Requested</option>
                <option value="video_watched" {{ request('type') == 'video_watched' ? 'selected' : '' }}>Video Watched</option>
            </select>
            <select name="priority" class="form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                <option value="">All Priorities</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
            </select>
            <select name="read_status" class="form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                <option value="">All</option>
                <option value="read" {{ request('read_status') == 'read' ? 'selected' : '' }}>Read</option>
                <option value="unread" {{ request('read_status') == 'unread' ? 'selected' : '' }}>Unread</option>
            </select>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">
                Filter
            </button>
        </form>
        <button onclick="openSendNotificationModal()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md">
            Send Notification
        </button>
    </div>

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
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full mr-2 bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                                </span>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">{{ $notification->title }}</h3>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $notification->message }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500">
                                Sent to: {{ $notification->user->name ?? 'Unknown User' }} ({{ $notification->user->email ?? 'N/A' }})
                                - {{ $notification->created_at->diffForHumans() }}
                                @if($notification->is_read)
                                    (Read {{ $notification->read_at->diffForHumans() }})
                                @endif
                            </p>
                            @if($notification->action_url && $notification->action_text)
                                <a href="{{ $notification->action_url }}" class="text-blue-600 hover:underline text-sm mt-2 inline-block">
                                    {{ $notification->action_text }}
                                </a>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="openDeleteNotificationModal({{ $notification->id }}, '{{ $notification->title }}')"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600 text-xs font-bold py-1 px-3 rounded-full">
                                Delete
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-6 py-4">
                {{ $notifications->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Send Notification Modal -->
<div id="sendNotificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Send Notification</h3>
        <form action="{{ route('admin.notifications.send') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select User</label>
                <select name="user_id" id="user_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" name="title" id="title" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
                <textarea name="message" id="message" rows="4" required
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
            </div>
            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type (Optional)</label>
                <input type="text" name="type" id="type"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priority</label>
                <select name="priority" id="priority"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="action_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Action URL (Optional)</label>
                <input type="url" name="action_url" id="action_url"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="action_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Action Text (Optional)</label>
                <input type="text" name="action_text" id="action_text"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeSendNotificationModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">Send Notification</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Notification Modal -->
<div id="deleteNotificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Delete Notification</h3>
        <p class="text-gray-700 dark:text-gray-300 mb-4">Are you sure you want to delete "<span id="delete_notification_title" class="font-semibold"></span>"? This action cannot be undone.</p>
        <form id="deleteNotificationForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeDeleteNotificationModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md">Cancel</button>
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openSendNotificationModal() {
        document.getElementById('sendNotificationModal').classList.remove('hidden');
    }

    function closeSendNotificationModal() {
        document.getElementById('sendNotificationModal').classList.add('hidden');
    }

    function openDeleteNotificationModal(id, title) {
        document.getElementById('delete_notification_title').innerText = title;
        document.getElementById('deleteNotificationForm').action = `/admin/notifications/${id}`;
        document.getElementById('deleteNotificationModal').classList.remove('hidden');
    }

    function closeDeleteNotificationModal() {
        document.getElementById('deleteNotificationModal').classList.add('hidden');
    }
</script>
@endsection
