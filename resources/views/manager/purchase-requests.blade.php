@extends('layouts.app')

@section('title', 'Purchase Requests - Manager Panel')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">💳 Purchase Requests</h2>
            <p class="text-gray-600">Review and approve user package purchase requests</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800">📋 All Purchase Requests</h3>
            </div>

            @if ($purchaseRequests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Package</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Payment Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($purchaseRequests as $request)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <span
                                                        class="text-indigo-600 font-semibold">{{ substr($request->user->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $request->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $request->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $request->package->name }}</div>
                                        <div class="text-sm text-green-600">Rs. {{ number_format($request->amount) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            <div><strong>From:</strong> {{ $request->sender_number }}</div>
                                            @if ($request->transaction_id)
                                                <div><strong>Transaction ID:</strong> {{ $request->transaction_id }}</div>
                                            @endif
                                            @if ($request->payment_proof)
                                                <div class="mt-1"><strong>Details:</strong>
                                                    {{ Str::limit($request->payment_proof, 50) }}</div>
                                            @endif
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $request->created_at->format('M d, Y h:i A') }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if ($request->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($request->status == 'approved') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                        @if ($request->status == 'approved')
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $request->approved_at->format('M d, Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if ($request->status == 'pending')
                                            <form method="POST"
                                                action="{{ route('manager.approve-purchase-request', $request) }}"
                                                class="inline mr-2">
                                                @csrf
                                                <button type="submit"
                                                    onclick="return confirm('Are you sure you want to approve this purchase request?')"
                                                    class="text-green-600 hover:text-green-900">✅ Approve</button>
                                            </form>
                                            <button onclick="showRejectModal({{ $request->id }})"
                                                class="text-red-600 hover:text-red-900">❌ Reject</button>
                                        @else
                                            <span class="text-gray-400">No actions</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">No purchase requests yet</p>
                    <p class="text-gray-400 mt-2">Purchase requests will appear here when users submit them</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Reject Purchase Request</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Rejection Reason</label>
                    <textarea name="rejection_reason" required rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                        placeholder="Enter reason for rejection..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Reject</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showRejectModal(requestId) {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectForm').action = `/manager/reject-purchase-request/${requestId}`;
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
@endsection
