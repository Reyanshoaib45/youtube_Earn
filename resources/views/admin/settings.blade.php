@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">System Settings</h1>

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

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">General Settings</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">App Name</label>
                    <input type="text" name="app_name" id="app_name" value="{{ $settings['app_name'] ?? '' }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Email</label>
                    <input type="email" name="contact_email" id="contact_email" value="{{ $settings['contact_email'] ?? '' }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Phone</label>
                    <input type="text" name="contact_phone" id="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                    <label for="maintenance_mode" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Maintenance Mode</label>
                </div>
            </div>

            <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">Payment Settings</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="jazzcash_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">JazzCash Number</label>
                    <input type="text" name="jazzcash_number" id="jazzcash_number" value="{{ $settings['jazzcash_number'] ?? '' }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="jazzcash_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">JazzCash Account Name</label>
                    <input type="text" name="jazzcash_name" id="jazzcash_name" value="{{ $settings['jazzcash_name'] ?? '' }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="easypaisa_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">EasyPaisa Number</label>
                    <input type="text" name="easypaisa_number" id="easypaisa_number" value="{{ $settings['easypaisa_number'] ?? '' }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="easypaisa_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">EasyPaisa Account Name</label>
                    <input type="text" name="easypaisa_name" id="easypaisa_name" value="{{ $settings['easypaisa_name'] ?? '' }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">Referral & Security Settings</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="min_referral_bonus" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum Referral Bonus ($)</label>
                    <input type="number" name="min_referral_bonus" id="min_referral_bonus" step="0.01" min="0" value="{{ $settings['min_referral_bonus'] ?? '' }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="max_accounts_per_ip" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Accounts Per IP</label>
                    <input type="number" name="max_accounts_per_ip" id="max_accounts_per_ip" min="1" value="{{ $settings['max_accounts_per_ip'] ?? '' }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
