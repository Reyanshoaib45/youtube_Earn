@extends('layouts.app')

@section('title', 'Purchase Package - Watch & Earn')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Package Details -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">📦 Package Details</h2>
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 mb-4">
                    <h3 class="text-xl font-bold text-indigo-600">{{ $package->name }}</h3>
                    <div class="grid grid-cols-2 gap-4 mt-3">
                        <div>
                            <span class="text-gray-600">Price:</span>
                            <span class="font-bold text-green-600">Rs. {{ number_format($package->price) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Duration:</span>
                            <span class="font-bold">{{ $package->duration_days }} days</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Daily Videos:</span>
                            <span class="font-bold">{{ $package->daily_video_limit }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Total Reward:</span>
                            <span class="font-bold text-green-600">Rs. {{ number_format($package->total_reward) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">💳 Payment Instructions</h2>

                <!-- Step 1 -->
                <div class="flex items-start mb-6">
                    <div
                        class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 mt-1">
                        1</div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg mb-2">Send Money via JazzCash</h3>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-gray-700 mb-2">Send <strong class="text-red-600">Rs.
                                    {{ number_format($package->price) }}</strong> to:</p>
                            <div class="flex items-center justify-between bg-white rounded-lg p-3 border-2 border-red-300">
                                <span class="text-2xl font-bold text-red-600" id="jazzcashNumber">03241179101</span>
                                <button onclick="copyJazzCashNumber()"
                                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                    📋 Copy
                                </button>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex items-start mb-6">
                    <div
                        class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 mt-1">
                        2</div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg mb-2">Fill the Form Below</h3>
                        <p class="text-gray-600">After sending money, fill out the confirmation form with your payment
                            details.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex items-start">
                    <div
                        class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 mt-1">
                        3</div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg mb-2">Wait for Approval</h3>
                        <p class="text-gray-600">Our manager will verify your payment and activate your package within 24
                            hours.</p>
                    </div>
                </div>
            </div>

            <!-- Payment Confirmation Form -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">✅ Payment Confirmation</h2>

                <form method="POST" action="{{ route('user.submit-purchase-request') }}">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">

                    <div class="grid gap-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Your JazzCash Number *</label>
                            <input type="text" name="sender_number" required pattern="03[0-9]{9}"
                                placeholder="03XXXXXXXXX"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Enter the number you sent money from</p>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Transaction ID (Optional)</label>
                            <input type="text" name="transaction_id" placeholder="Enter transaction ID if available"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Additional Details (Optional)</label>
                            <textarea name="payment_proof" rows="3" placeholder="Any additional information about your payment..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"></textarea>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-yellow-800 text-sm">
                                <strong>⚠️ Important:</strong> Make sure you have sent the exact amount (Rs.
                                {{ number_format($package->price) }})
                                to the JazzCash number above before submitting this form.
                            </p>
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit"
                                class="flex-1 bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 transition duration-300 font-bold">
                                🚀 Submit Purchase Request
                            </button>
                            <a href="{{ route('user.packages') }}"
                                class="flex-1 bg-gray-500 text-white py-3 px-6 rounded-lg hover:bg-gray-600 transition duration-300 font-bold text-center">
                                ← Back to Packages
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function copyJazzCashNumber() {
            const number = document.getElementById('jazzcashNumber').textContent;
            navigator.clipboard.writeText(number).then(function() {
                // Show success message
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '✅ Copied!';
                button.classList.add('bg-green-500');
                button.classList.remove('bg-red-500');

                setTimeout(function() {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-500');
                    button.classList.add('bg-red-500');
                }, 2000);
            });
        }
    </script>
@endsection
