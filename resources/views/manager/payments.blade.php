@extends('layouts.app')

@section('title', 'Payment Management')
@section('page-title', 'Payment Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold">Payment Management</h1>
                <p class="mt-2 opacity-90">Review and approve user payment submissions</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-credit-card text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <select name="method" class="w-full px-4 py-2 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Methods</option>
                    <option value="jazzcash" {{ request('method') === 'jazzcash' ? 'selected' : '' }}>JazzCash</option>
                    <option value="easypaisa" {{ request('method') === 'easypaisa' ? 'selected' : '' }}>EasyPaisa</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Payment Submissions</h2>
        </div>
        
        @if($payments->isEmpty())
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-credit-card text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Payment Submissions</h3>
                <p class="text-gray-600">No payment submissions found matching your criteria.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white font-medium text-sm">{{ substr($payment->user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $payment->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $payment->package->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $payment->package->validity_days }} days</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">${{ number_format($payment->amount_paid, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas fa-mobile-alt {{ $payment->payment_method === 'jazzcash' ? 'text-purple-600' : 'text-green-600' }} mr-2"></i>
                                        <span class="text-sm text-gray-900 capitalize">{{ $payment->payment_method }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $payment->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($payment->payment_status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($payment->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $payment->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($payment->payment_status === 'pending')
                                        <button onclick="reviewPayment({{ $payment->id }})" 
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-eye mr-1"></i>Review
                                        </button>
                                    @else
                                        <span class="text-gray-400">Processed</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Payment Review Modal -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Review Payment Submission</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div id="modalContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function reviewPayment(paymentId) {
    showLoading(true);
    
    fetch(`/manager/payments/${paymentId}`)
        .then(response => response.json())
        .then(data => {
            showLoading(false);
            showPaymentModal(data);
        })
        .catch(error => {
            showLoading(false);
            showToast('Error loading payment details', 'error');
        });
}

function showPaymentModal(payment) {
    const modal = document.getElementById('paymentModal');
    const content = document.getElementById('modalContent');
    
    content.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Payment Details -->
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-gray-900">Payment Details</h4>
                
                <div class="bg-gray-50 rounded-2xl p-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">User:</span>
                        <span class="font-medium">${payment.user.name}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium">${payment.user.email}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Package:</span>
                        <span class="font-medium">${payment.package.name}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Amount:</span>
                        <span class="font-medium text-green-600">$${payment.amount_paid}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Method:</span>
                        <span class="font-medium capitalize">${payment.payment_method}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Transaction ID:</span>
                        <span class="font-mono font-medium">${payment.transaction_id || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Submitted:</span>
                        <span class="font-medium">${new Date(payment.created_at).toLocaleString()}</span>
                    </div>
                </div>
                
                ${payment.payment_notes ? `
                    <div>
                        <h5 class="font-medium text-gray-900 mb-2">Additional Notes:</h5>
                        <div class="bg-blue-50 rounded-2xl p-3">
                            <p class="text-sm text-gray-700">${payment.payment_notes}</p>
                        </div>
                    </div>
                ` : ''}
            </div>
            
            <!-- Payment Screenshot -->
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-gray-900">Payment Screenshot</h4>
                
                ${payment.payment_screenshot ? `
                    <div class="border border-gray-200 rounded-2xl p-4">
                        <img src="/storage/${payment.payment_screenshot}" 
                             alt="Payment Screenshot" 
                             class="w-full h-auto rounded-2xl shadow-md">
                    </div>
                ` : `
                    <div class="border border-gray-200 rounded-2xl p-8 text-center">
                        <i class="fas fa-image text-gray-300 text-3xl mb-4"></i>
                        <p class="text-gray-500">No screenshot uploaded</p>
                    </div>
                `}
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
            <button onclick="closeModal()" 
                    class="px-6 py-2 border border-gray-300 rounded-2xl text-gray-700 hover:bg-gray-50">
                Cancel
            </button>
            
            <div class="flex space-x-3">
                <button onclick="processPayment(${payment.id}, 'rejected')" 
                        class="px-6 py-2 bg-red-600 text-white rounded-2xl hover:bg-red-700">
                    <i class="fas fa-times mr-2"></i>Reject
                </button>
                <button onclick="processPayment(${payment.id}, 'approved')" 
                        class="px-6 py-2 bg-green-600 text-white rounded-2xl hover:bg-green-700">
                    <i class="fas fa-check mr-2"></i>Approve
                </button>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function processPayment(paymentId, action) {
    const notes = prompt(`Enter notes for ${action} (optional):`);
    
    if (action === 'rejected' && !notes) {
        alert('Please provide a reason for rejection');
        return;
    }
    
    showLoading(true);
    
    fetch(`/manager/payments/${paymentId}/${action}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ notes: notes })
    })
    .then(response => response.json())
    .then(data => {
        showLoading(false);
        if (data.success) {
            showToast(`Payment ${action} successfully!`, 'success');
            closeModal();
            location.reload();
        } else {
            showToast(data.message || 'Error processing payment', 'error');
        }
    })
    .catch(error => {
        showLoading(false);
        showToast('Error processing payment', 'error');
    });
}

function closeModal() {
    document.getElementById('paymentModal').classList.add('hidden');
}
</script>
@endsection
