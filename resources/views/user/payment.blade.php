@extends('layouts.app')

@section('title', 'Complete Payment')
@section('page-title', 'Complete Your Payment')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Payment Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold">Complete Your Payment</h1>
                <p class="mt-2 opacity-90">Follow the steps below to activate your {{ $userPackage->package->name }} package</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-credit-card text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Package Details -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-box text-blue-600 mr-2"></i>Package Details
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Package Name:</span>
                    <span class="font-semibold text-gray-900">{{ $userPackage->package->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Price:</span>
                    <span class="font-semibold text-green-600">${{ number_format($userPackage->amount_paid, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Validity:</span>
                    <span class="font-semibold text-gray-900">{{ $userPackage->package->validity_days }} days</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Video Limit:</span>
                    <span class="font-semibold text-gray-900">{{ $userPackage->package->video_limit }} videos</span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Reward per Video:</span>
                    <span class="font-semibold text-gray-900">${{ number_format($userPackage->package->reward_per_video, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Min Withdrawal:</span>
                    <span class="font-semibold text-gray-900">${{ number_format($userPackage->package->min_withdrawal, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Referral Bonus:</span>
                    <span class="font-semibold text-gray-900">${{ number_format($userPackage->package->referral_bonus, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Order Date:</span>
                    <span class="font-semibold text-gray-900">{{ $userPackage->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Instructions -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>Payment Instructions
        </h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- JazzCash -->
            <div class="border border-gray-200 rounded-2xl p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center mr-4">
                        <i class="fas fa-mobile-alt text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">JazzCash</h3>
                        <p class="text-sm text-gray-600">Mobile Wallet Payment</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="bg-purple-50 rounded-2xl p-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Account Details:</p>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Number:</span>
                                <span class="font-mono font-semibold text-gray-900">{{ $paymentInfo['jazzcash']['number'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-semibold text-gray-900">{{ $paymentInfo['jazzcash']['name'] }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-sm text-gray-600">
                        <p class="font-medium mb-2">Steps to pay:</p>
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Open JazzCash app</li>
                            <li>Select "Send Money"</li>
                            <li>Enter the number above</li>
                            <li>Send ${{ number_format($userPackage->amount_paid, 2) }}</li>
                            <li>Take screenshot of confirmation</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- EasyPaisa -->
            <div class="border border-gray-200 rounded-2xl p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center mr-4">
                        <i class="fas fa-mobile-alt text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">EasyPaisa</h3>
                        <p class="text-sm text-gray-600">Mobile Wallet Payment</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="bg-green-50 rounded-2xl p-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Account Details:</p>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Number:</span>
                                <span class="font-mono font-semibold text-gray-900">{{ $paymentInfo['easypaisa']['number'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-semibold text-gray-900">{{ $paymentInfo['easypaisa']['name'] }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-sm text-gray-600">
                        <p class="font-medium mb-2">Steps to pay:</p>
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Open EasyPaisa app</li>
                            <li>Select "Send Money"</li>
                            <li>Enter the number above</li>
                            <li>Send ${{ number_format($userPackage->amount_paid, 2) }}</li>
                            <li>Take screenshot of confirmation</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="mt-6 p-4 bg-blue-50 rounded-2xl border border-blue-200">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-phone text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-blue-900">Need Help?</p>
                    <p class="text-sm text-blue-700">Contact us at: <span class="font-mono font-semibold">{{ $paymentInfo['contact_number'] }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Submission Form -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-upload text-green-600 mr-2"></i>Submit Payment Proof
        </h2>
        
        <form action="{{ route('user.payment.submit', $userPackage) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Payment Method -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Payment Method *</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative">
                        <input type="radio" name="payment_method" value="jazzcash" class="sr-only peer" required>
                        <div class="p-4 border-2 border-gray-200 rounded-2xl cursor-pointer peer-checked:border-purple-500 peer-checked:bg-purple-50 hover:border-purple-300 transition-colors">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-mobile-alt text-purple-600"></i>
                                <span class="font-medium text-gray-900">JazzCash</span>
                            </div>
                        </div>
                    </label>
                    
                    <label class="relative">
                        <input type="radio" name="payment_method" value="easypaisa" class="sr-only peer" required>
                        <div class="p-4 border-2 border-gray-200 rounded-2xl cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 hover:border-green-300 transition-colors">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-mobile-alt text-green-600"></i>
                                <span class="font-medium text-gray-900">EasyPaisa</span>
                            </div>
                        </div>
                    </label>
                </div>
                @error('payment_method')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Transaction ID -->
            <div>
                <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">Transaction ID *</label>
                <input type="text" 
                       id="transaction_id" 
                       name="transaction_id" 
                       value="{{ old('transaction_id') }}"
                       placeholder="Enter the transaction ID from your payment receipt"
                       class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
                @error('transaction_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Payment Screenshot -->
            <div>
                <label for="payment_screenshot" class="block text-sm font-medium text-gray-700 mb-2">Payment Screenshot *</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl hover:border-blue-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-4"></i>
                        <div class="flex text-sm text-gray-600">
                            <label for="payment_screenshot" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Upload a screenshot</span>
                                <input id="payment_screenshot" name="payment_screenshot" type="file" accept="image/*" class="sr-only" required onchange="previewImage(this)">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 2MB</p>
                    </div>
                </div>
                
                <!-- Image Preview -->
                <div id="image-preview" class="mt-4 hidden">
                    <img id="preview-img" src="/placeholder.svg" alt="Payment Screenshot Preview" class="max-w-full h-64 object-contain rounded-2xl border border-gray-200">
                </div>
                
                @error('payment_screenshot')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Payment Notes -->
            <div>
                <label for="payment_notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                <textarea id="payment_notes" 
                          name="payment_notes" 
                          rows="3" 
                          placeholder="Any additional information about your payment..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('payment_notes') }}</textarea>
                @error('payment_notes')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Important Notice -->
            <div class="p-4 bg-yellow-50 rounded-2xl border border-yellow-200">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                    <div class="text-sm text-yellow-800">
                        <p class="font-medium mb-1">Important:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Make sure the transaction ID matches your payment receipt</li>
                            <li>Upload a clear screenshot showing the payment confirmation</li>
                            <li>Your package will be activated within 24 hours after verification</li>
                            <li>Contact support if you face any issues</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between pt-6">
                <a href="{{ route('user.packages') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-2xl text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Packages
                </a>
                
                <button type="submit" 
                        class="px-8 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-colors font-medium">
                    <i class="fas fa-paper-plane mr-2"></i>Submit Payment Proof
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Drag and drop functionality
const dropZone = document.querySelector('[for="payment_screenshot"]').closest('.border-dashed');
const fileInput = document.getElementById('payment_screenshot');

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-blue-400', 'bg-blue-50');
});

dropZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-blue-400', 'bg-blue-50');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        previewImage(fileInput);
    }
});
</script>
@endsection
