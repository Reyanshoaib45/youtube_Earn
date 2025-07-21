@extends('layouts.app')

@section('title', 'Purchase Requests - Manager Panel')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">💳 Purchase Request Management</h2>
            <p class="text-gray-600">Review and approve user package purchase requests</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Withdrawal Management</h2>
            <p class="text-gray-600">Review and approve user withdrawal requests</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Withdrawal Requests -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800">💰 Withdrawal Requests</h3>
            </div>

            @if ($withdrawals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Request Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User Balance</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($withdrawals as $withdrawal)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <span
                                                        class="text-indigo-600 font-semibold">{{ substr($withdrawal->user->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $withdrawal->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $withdrawal->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-green-600">Rs.
                                            {{ number_format($withdrawal->amount, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if ($withdrawal->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($withdrawal->status === 'approved') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $withdrawal->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $withdrawal->created_at->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Rs.
                                            {{ number_format($withdrawal->user->total_earnings, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if ($withdrawal->status === 'pending')
                                            <div class="flex space-x-2">
                                                <form method="POST"
                                                    action="{{ route('manager.approve-withdrawal', $withdrawal) }}"
                                                    class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        onclick="return confirm('Are you sure you want to approve this withdrawal?')"
                                                        class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">
                                                        Approve
                                                    </button>
                                                </form>
                                                <button onclick="banUser({{ $withdrawal->user->id }})"
                                                    class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                                                    Ban User
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-gray-500">{{ ucfirst($withdrawal->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">No withdrawal requests yet</p>
                    <p class="text-gray-400 mt-2">Withdrawal requests will appear here when users submit them</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Ban User Modal -->
    <div id="banModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Ban User</h3>
            <form id="banForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ban Reason</label>
                    <textarea name="ban_reason" required rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"
                        placeholder="Enter reason for banning this user..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeBanModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Ban
                        User</button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            function banUser(userId) {
                document.getElementById('banModal').classList.remove('hidden');
                document.getElementById('banForm').action = `/manager/ban-user/${userId}`;
            }

            function closeBanModal() {
                document.getElementById('banModal').classList.add('hidden');
            }
        </script>
    @endpush
@endsection
