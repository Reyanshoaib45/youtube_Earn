@extends('layouts.app')

@section('title', 'Admin Manage Withdrawals')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Admin Manage Withdrawals</h1>

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
        <form action="{{ route('admin.withdrawals') }}" method="GET" class="flex items-center space-x-4">
            <input type="text" name="search" placeholder="Search withdrawals..."
                   class="form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-white"
                   value="{{ request('search') }}">
            <select name="status" class="form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending ({{ $pendingCount }})</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved ({{ $approvedCount }})</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected ({{ $rejectedCount }})</option>
            </select>
            <select name="method" class="form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                <option value="">All Methods</option>
                <option value="jazzcash" {{ request('method') == 'jazzcash' ? 'selected' : '' }}>JazzCash</option>
                <option value="easypaisa" {{ request('method') == 'easypaisa' ? 'selected' : '' }}>EasyPaisa</option>
                <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="paypal" {{ request('method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
            </select>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">
                Filter
            </button>
        </form>
    </div>

    @if ($withdrawals->isEmpty())
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
            <p class="text-gray-600 dark:text-gray-400">No withdrawal requests found.</p>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Method
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Account Details
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Requested At
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($withdrawals as $withdrawal)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                    {{ $withdrawal->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    ${{ number_format($withdrawal->amount, 2) }} (Fee: ${{ number_format($withdrawal->fee_amount, 2) }})
                                    <br>Final: ${{ number_format($withdrawal->final_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ ucfirst(str_replace('_', ' ', $withdrawal->method)) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    Name: {{ $withdrawal->account_name }}<br>
                                    Number: {{ $withdrawal->account_number }}<br>
                                    @if($withdrawal->bank_name) Bank: {{ $withdrawal->bank_name }}<br> @endif
                                    @if($withdrawal->branch_code) Branch: {{ $withdrawal->branch_code }} @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($withdrawal->status == 'approved') bg-green-100 text-green-800
                                        @elseif($withdrawal->status == 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($withdrawal->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $withdrawal->requested_at->format('M d, Y H:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if ($withdrawal->status == 'pending')
                                        <button onclick="openProcessWithdrawalModal({{ $withdrawal->id }}, '{{ $withdrawal->user->name }}', '{{ number_format($withdrawal->amount, 2) }}')"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-600 mr-3">
                                            Process
                                        </button>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">Processed</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $withdrawals->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Process Withdrawal Modal -->
<div id="processWithdrawalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Process Withdrawal</h3>
        <p class="text-gray-700 dark:text-gray-300 mb-4">Review withdrawal request for <span id="withdrawal_user_name" class="font-semibold"></span> for $<span id="withdrawal_amount" class="font-semibold"></span>.</p>
        <form id="processWithdrawalForm" method="POST">
            @csrf
            <div class="mb-4">
                <label for="process_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="process_status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="approved">Approve</option>
                    <option value="rejected">Reject</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="manager_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Manager Notes (Optional)</label>
                <textarea name="manager_notes" id="manager_notes" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeProcessWithdrawalModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">Process</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openProcessWithdrawalModal(id, userName, amount) {
        document.getElementById('withdrawal_user_name').innerText = userName;
        document.getElementById('withdrawal_amount').innerText = amount;
        document.getElementById('processWithdrawalForm').action = `/admin/withdrawals/${id}/process`;
        document.getElementById('processWithdrawalModal').classList.remove('hidden');
    }

    function closeProcessWithdrawalModal() {
        document.getElementById('processWithdrawalModal').classList.add('hidden');
    }
</script>
@endsection
