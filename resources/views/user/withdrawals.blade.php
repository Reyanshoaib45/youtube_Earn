@extends('layouts.app')

@section('title', 'Withdrawals')
@section('page-title', 'Withdrawals')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Withdrawals</h1>
        <p class="mt-2 text-gray-600">Request withdrawals and track your payment history</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Withdrawal form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-wallet text-blue-600 mr-2"></i>Request Withdrawal
                </h2>
                
                <!-- Balance info -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl p-4 text-white mb-6">
                    <div class="text-center">
                        <p class="text-green-100 text-sm mb-1">Current Balance</p>
                        <p class="text-3xl font-bold">${{ number_format($user->reward_balance, 2) }}</p>
                    </div>
                </div>
                
                @if($activePackage)
                    <div class="bg-blue-50 rounded-2xl p-4 mb-6">
                        <div class="text-center">
                            <p class="text-blue-600 text-sm mb-1">Minimum Withdrawal</p>
                            <p class="text-xl font-bold text-blue-800">${{ number_format($activePackage->package->min_withdrawal, 2) }}</p>
                        </div>
                    </div>
                @endif
                
                @if($canWithdraw)
                    <form action="{{ route('user.withdrawal.request') }}" method="POST" 
                          x-data="{ loading: false, method: '' }"
                          @submit="loading = true; showLoading(true)"
                          class="space-y-4">
                        @csrf
                        
                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-dollar-sign mr-1"></i>Amount
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       id="amount" 
                                       name="amount" 
                                       step="0.01" 
                                       min="1" 
                                       max="{{ $user->reward_balance }}" 
                                       required
                                       class="w-full px-4 py-3 pl-8 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Enter amount">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">$</span>
                                </div>
                            </div>
                            @error('amount')
                                <p class="mt-2 text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Payment method -->
                        <div>
                            <label for="method" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-credit-card mr-1"></i>Payment Method
                            </label>
                            <select id="method" 
                                    name="method" 
                                    x-model="method"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Method</option>
                                <option value="jazzcash">
                                    <i class="fas fa-mobile-alt mr-2"></i>JazzCash
                                </option>
                                <option value="easypaisa">
                                    <i class="fas fa-mobile-alt mr-2"></i>EasyPaisa
                                </option>
                            </select>
                            @error('method')
                                <p class="mt-2 text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Account name -->
                        <div>
                            <label for="account_name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-1"></i>Account Name
                            </label>
                            <input type="text" 
                                   id="account_name" 
                                   name="account_name" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Enter account holder name">
                            @error('account_name')
                                <p class="mt-2 text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Account number -->
                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-hashtag mr-1"></i>
                                <span x-text="method === 'jazzcash' ? 'JazzCash Number' : method === 'easypaisa' ? 'EasyPaisa Number' : 'Account Number'"></span>
                            </label>
                            <input type="text" 
                                   id="account_number" 
                                   name="account_number" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   :placeholder="method === 'jazzcash' ? 'Enter JazzCash number' : method === 'easypaisa' ? 'Enter EasyPaisa number' : 'Enter account number'">
                            @error('account_number')
                                <p class="mt-2 text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <button type="submit" 
                                :disabled="loading"
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-2xl font-medium hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50">
                            <span x-show="!loading">
                                <i class="fas fa-paper-plane mr-2"></i>Request Withdrawal
                            </span>
                            <span x-show="loading" class="flex items-center justify-center">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                Processing...
                            </span>
                        </button>
                    </form>
                @else
                    <!-- Cannot withdraw message -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800 mb-2">Cannot Withdraw</h3>
                                <div class="text-sm text-yellow-700">
                                    @if(!$activePackage)
                                        <p class="mb-2">You need an active package to withdraw.</p>
                                        <a href="{{ route('user.packages') }}" 
                                           class="inline-flex items-center px-3 py-1 bg-yellow-600 text-white rounded-xl text-xs hover:bg-yellow-700">
                                            <i class="fas fa-box mr-1"></i>View Packages
                                        </a>
                                    @elseif($user->reward_balance < $activePackage->package->min_withdrawal)
                                        <p class="mb-3">You need at least ${{ number_format($activePackage->package->min_withdrawal, 2) }} to withdraw.</p>
                                        <!-- Progress bar -->
                                        <div class="w-full bg-yellow-200 rounded-full h-2 mb-2">
                                            <div class="bg-yellow-600 h-2 rounded-full" 
                                                 style="width: {{ min(($user->reward_balance / $activePackage->package->min_withdrawal) * 100, 100) }}%"></div>
                                        </div>
                                        <p class="text-xs">
                                            ${{ number_format($user->reward_balance, 2) }} / ${{ number_format($activePackage->package->min_withdrawal, 2) }}
                                        </p>
                                    @else
                                        <p>You can only request one withdrawal per 24 hours.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Withdrawal history -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-history text-gray-600 mr-2"></i>Withdrawal History
                </h2>
                
                @if($withdrawals->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-receipt text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Withdrawals Yet</h3>
                        <p class="text-gray-600">Your withdrawal requests will appear here</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-4 font-medium text-gray-900">Amount</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-900">Method</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-900">Account</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-900">Status</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-900">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($withdrawals as $withdrawal)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 px-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
                                                <span class="font-semibold text-gray-900">{{ number_format($withdrawal->amount, 2) }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-mobile-alt text-blue-600 mr-2"></i>
                                                <span class="text-gray-700">{{ ucfirst($withdrawal->method) }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $withdrawal->account_name }}</p>
                                                <p class="text-sm text-gray-500">{{ $withdrawal->account_number }}</p>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                                {{ $withdrawal->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                                   ($withdrawal->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                <i class="fas {{ $withdrawal->status === 'approved' ? 'fa-check-circle' : ($withdrawal->status === 'rejected' ? 'fa-times-circle' : 'fa-clock') }} mr-1"></i>
                                                {{ ucfirst($withdrawal->status) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div>
                                                <p class="text-sm text-gray-900">{{ $withdrawal->requested_at->format('M d, Y') }}</p>
                                                <p class="text-xs text-gray-500">{{ $withdrawal->requested_at->format('h:i A') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
