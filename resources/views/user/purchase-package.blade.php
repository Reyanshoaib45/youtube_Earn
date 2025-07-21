@extends('layouts.app')

@section('title', 'Purchase Package - Watch & Earn')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('user.packages') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Packages
                </a>
            </div>

            <!-- Package Details -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">📦 Purchase Package</h1>
                    <p class="text-gray-600">Complete your payment to activate your earning package</p>
                </div>

                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-6 mb-8">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-indigo-600">{{ $package->name }}</h2>
                        <div class="text-4xl font-bold text-green-600 mt-2">Rs. {{ number_format($package->price) }}</div>
                    </div>

                    <div class="grid md:grid-cols-4 gap-4 text-center">
                        <div>
                            <div class="text-lg font-bold text-gray-800">{{ $package->daily_video_limit }}</div>
                            <p class="text-sm text-gray-600">Daily Videos</p>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-gray-800">{{ $package->duration_days }}</div>
                            <p class="text-sm text-gray-600">Days</p>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-green-600">Rs. {{ number_format($package->total_reward) }}
                            </div>
                            <p class="text-sm text-gray-600">Total Reward</p>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-purple-600">Rs.
                                {{ number_format($package->total_reward - $package->price) }}</div>
                            <p class="text-sm text-gray-600">Your Profit</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Instructions -->
                <div class="grid lg:grid-cols-2 gap-8">
                    <!-- JazzCash Payment Details -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <div class="text-center mb-6">
                            <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-red-800 mb-2">💳 JazzCash Payment</h3>
                            <p class="text-red-700">Send money to the following JazzCash number</p>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-white rounded-lg p-4 border-2 border-red-300">
                                <label class="block text-sm font-medium text-gray-700 mb-1">JazzCash Number:</label>
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-bold text-red-600">0324 1179101</span>
                                    <button onclick="copyJazzCashNumber()"
                                        class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                        📋 Copy
                                    </button>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg p-4 border-2 border-red-300">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Amount to Send:</label>
                                <div class="text-2xl font-bold text-green-600">Rs. {{ number_format($package->price) }}
                                </div>
                            </div>

                            <div class="bg-white rounded-lg p-4 border-2 border-red-300">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Account Name:</label>
                                <div class="text-lg font-semibold text-gray-800">Abdul Samad</div>
                            </div>
                        </div>

                        <!-- Payment Steps -->
                        <div class="mt-6">
                            <h4 class="font-bold text-red-800 mb-3">📋 Payment Steps:</h4>
                            <ol class="list-decimal list-inside space-y-2 text-sm text-red-700">
                                <li>Open your JazzCash app or dial *786#</li>
                                <li>Select "Send Money" option</li>
                                <li>Enter the number: <strong>0324 1179101</strong></li>
                                <li>Enter amount: <strong>Rs. {{ number_format($package->price) }}</strong></li>
                                <li>Complete the transaction</li>
                                <li>Note down the Transaction ID</li>
                                <li>Fill the form on the right side</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Payment Confirmation Form -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="text-center mb-6">
                            <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-green-800 mb-2">✅ Confirm Payment</h3>
                            <p class="text-green-700">After sending money, fill this form</p>
                        </div>

                        <form method="POST" action="{{ route('user.submit-purchase-request') }}">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Your Mobile Number *</label>
                                    <input type="text" name="sender_number" placeholder="0324 1179101"
                                        pattern="03[0-9]{9}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500"
                                        required>
                                    <p class="text-xs text-gray-500 mt-1">Enter the number you sent money from</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Transaction ID
                                        (Optional)</label>
                                    <input type="text" name="transaction_id" placeholder="e.g., TXN123456789"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500">
                                    <p class="text-xs text-gray-500 mt-1">If you have transaction ID, please enter it</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Details
                                        (Optional)</label>
                                    <textarea name="payment_proof" rows="3" placeholder="Any additional details about your payment..."
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500"></textarea>
                                </div>

                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <div class="text-yellow-600 mr-3">⚠️</div>
                                        <div>
                                            <h4 class="font-semibold text-yellow-800">Important Notes:</h4>
                                            <ul class="text-sm text-yellow-700 mt-1 space-y-1">
                                                <li>• Make sure you send the exact amount</li>
                                                <li>• Your package will be activated after manager approval</li>
                                                <li>• Keep your transaction receipt safe</li>
                                                <li>• Approval usually takes 1-24 hours</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="w-full bg-green-600 text-white py-4 rounded-lg font-semibold hover:bg-green-700 transition duration-300 text-lg">
                                    🚀 Submit Purchase Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">❓ Frequently Asked Questions</h3>
                <div class="space-y-4">
                    <div class="border-b pb-4">
                        <h4 class="font-semibold text-gray-800 mb-2">How long does approval take?</h4>
                        <p class="text-gray-600">Usually 1-24 hours. Our managers check payments regularly.</p>
                    </div>
                    <div class="border-b pb-4">
                        <h4 class="font-semibold text-gray-800 mb-2">What if I send wrong amount?</h4>
                        <p class="text-gray-600">Contact support immediately. We'll help resolve the issue.</p>
                    </div>
                    <div class="border-b pb-4">
                        <h4 class="font-semibold text-gray-800 mb-2">Can I cancel my purchase request?</h4>
                        <p class="text-gray-600">Yes, before approval. Contact support to cancel.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">When will my package activate?</h4>
                        <p class="text-gray-600">Immediately after manager approves your payment.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function copyJazzCashNumber() {
                const number = '0324 1179101';
                navigator.clipboard.writeText(number).then(function() {
                    const button = event.target;
                    const originalText = button.textContent;
                    button.textContent = '✅ Copied!';
                    button.classList.add('bg-green-600');
                    button.classList.remove('bg-red-600');

                    setTimeout(() => {
                        button.textContent = originalText;
                        button.classList.remove('bg-green-600');
                        button.classList.add('bg-red-600');
                    }, 2000);
                });
            }
        </script>
    @endpush
@endsection
