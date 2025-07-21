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
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Package Management</h2>
            <p class="text-gray-600">Create and manage earning packages for users</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add Package Form -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">➕ Create New Package</h3>
            <form method="POST" action="{{ route('manager.store-package') }}">
                @csrf
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Package Name</label>
                        <input type="text" name="name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"
                            placeholder="e.g., Starter Package">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Price (Rs.)</label>
                        <input type="number" name="price" min="1" step="0.01" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"
                            placeholder="1000.00">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Duration (Days)</label>
                        <input type="number" name="duration_days" min="1" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"
                            placeholder="7">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Daily Video Limit</label>
                        <input type="number" name="daily_video_limit" min="1" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"
                            placeholder="10">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Total Reward (Rs.)</label>
                        <input type="number" name="total_reward" min="1" step="0.01" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"
                            placeholder="2000.00">
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-300">
                            Create Package
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Packages Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($packages as $package)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-4">
                        <h3 class="text-xl font-bold">{{ $package->name }}</h3>
                        <div class="text-3xl font-bold mt-2">Rs. {{ number_format($package->price) }}</div>
                    </div>

                    <div class="p-6">
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Duration:</span>
                                <span class="font-semibold">{{ $package->duration_days }} days</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Daily Videos:</span>
                                <span class="font-semibold">{{ $package->daily_video_limit }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total Reward:</span>
                                <span class="font-semibold text-green-600">Rs.
                                    {{ number_format($package->total_reward) }}</span>
                            </div>
                            <div class="flex justify-between items-center border-t pt-3">
                                <span class="text-gray-600">Profit:</span>
                                <span class="font-bold text-green-600">Rs.
                                    {{ number_format($package->total_reward - $package->price) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Status:</span>
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $package->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <button onclick="editPackage({{ $package->id }})"
                                class="flex-1 bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-300">
                                Edit
                            </button>
                            <form method="POST" action="{{ route('manager.delete-package', $package) }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this package?')"
                                    class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition duration-300">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($packages->count() === 0)
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <p class="text-gray-500 text-lg">No packages created yet</p>
                <p class="text-gray-400 mt-2">Create your first package using the form above</p>
            </div>
        @endif
    </div>

    <!-- Edit Package Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Edit Package</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Package Name</label>
                        <input type="text" id="editName" name="name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Price (Rs.)</label>
                        <input type="number" id="editPrice" name="price" min="1" step="0.01" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Duration (Days)</label>
                        <input type="number" id="editDuration" name="duration_days" min="1" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Daily Video Limit</label>
                        <input type="number" id="editVideoLimit" name="daily_video_limit" min="1" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Total Reward (Rs.)</label>
                        <input type="number" id="editTotalReward" name="total_reward" min="1" step="0.01"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Update</button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            function editPackage(packageId) {
                // This is a simplified version - in a real app, you'd fetch package data via AJAX
                document.getElementById('editModal').classList.remove('hidden');
                document.getElementById('editForm').action = `/manager/packages/${packageId}`;
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }
        </script>
    @endpush
@endsection
