@extends('layouts.app')

@section('title', 'Transactions')
@section('page-title', 'All Transactions')

@section('content')
    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-bold mb-4">Transactions</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">#</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">User</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Amount</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Method</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Requested At</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($transactions as $tx)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $tx->id }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ $tx->user->name }}<br>
                            <span class="text-xs text-gray-500">{{ $tx->user->email }}</span>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-900">${{ number_format($tx->amount,2) }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ ucfirst($tx->method) }}</td>
                        <td class="px-4 py-2 text-sm">
                        <span class="inline-flex items-center px-2 py-1 rounded-full
                            {{ $tx->status === 'approved' ? 'bg-green-100 text-green-800' :
                               ($tx->status === 'processing' ? 'bg-yellow-100 text-yellow-800' :
                               ($tx->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($tx->status) }}
                        </span>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-500">
                            {{ $tx->requested_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-4 py-2 text-sm space-x-2">
                            <a href="{{ route('admin.transactions.show', $tx->id) }}"
                               class="text-blue-600 hover:underline">View</a>
                            {{-- Add approve/reject buttons as needed --}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection
