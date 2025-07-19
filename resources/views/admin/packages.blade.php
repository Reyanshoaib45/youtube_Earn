@extends('layouts.app')

@section('title', 'Manage Packages')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Manage Packages</h1>

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
        <form action="{{ route('admin.packages') }}" method="GET" class="flex items-center space-x-4">
            <input type="text" name="search" placeholder="Search packages..."
                   class="form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-white"
                   value="{{ request('search') }}">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">
                Search
            </button>
        </form>
        <button onclick="openAddPackageModal()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md">
            Add New Package
        </button>
    </div>

    @if ($packages->isEmpty())
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
            <p class="text-gray-600 dark:text-gray-400">No packages found.</p>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Price
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Validity (Days)
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Daily Video Limit
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Min Withdrawal
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Referral Bonus
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Active
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($packages as $package)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                    {{ $package->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    ${{ number_format($package->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $package->validity_days }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $package->daily_video_limit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    ${{ number_format($package->min_withdrawal, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    ${{ number_format($package->referral_bonus, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    @if($package->is_active)
                                        <span class="text-green-500">&#10004;</span>
                                    @else
                                        <span class="text-red-500">&#10006;</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick="openEditPackageModal({{ $package->id }}, '{{ $package->name }}', '{{ $package->description }}', {{ $package->price }}, {{ $package->validity_days }}, {{ $package->daily_video_limit }}, {{ $package->min_withdrawal }}, {{ $package->referral_bonus }}, {{ $package->is_active ? 'true' : 'false' }})"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 mr-3">
                                        Edit
                                    </button>
                                    <button onclick="openDeletePackageModal({{ $package->id }}, '{{ $package->name }}')"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $packages->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Add Package Modal -->
<div id="addPackageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Add New Package</h3>
        <form action="{{ route('admin.package.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="add_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" name="name" id="add_name" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="add_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description (Optional)</label>
                <textarea name="description" id="add_description" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
            </div>
            <div class="mb-4">
                <label for="add_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price ($)</label>
                <input type="number" name="price" id="add_price" step="0.01" min="0" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="add_validity_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Validity (Days)</label>
                <input type="number" name="validity_days" id="add_validity_days" min="1" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="add_daily_video_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Daily Video Limit</label>
                <input type="number" name="daily_video_limit" id="add_daily_video_limit" min="0" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="add_min_withdrawal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum Withdrawal ($)</label>
                <input type="number" name="min_withdrawal" id="add_min_withdrawal" step="0.01" min="0" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="add_referral_bonus" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Referral Bonus ($)</label>
                <input type="number" name="referral_bonus" id="add_referral_bonus" step="0.01" min="0" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="is_active" id="add_is_active" value="1" checked
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                <label for="add_is_active" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Is Active</label>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeAddPackageModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">Add Package</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Package Modal -->
<div id="editPackageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Edit Package</h3>
        <form id="editPackageForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="package_id" id="edit_package_id">
            <div class="mb-4">
                <label for="edit_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" name="name" id="edit_name" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="edit_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description (Optional)</label>
                <textarea name="description" id="edit_description" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
            </div>
            <div class="mb-4">
                <label for="edit_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price ($)</label>
                <input type="number" name="price" id="edit_price" step="0.01" min="0" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="edit_validity_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Validity (Days)</label>
                <input type="number" name="validity_days" id="edit_validity_days" min="1" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="edit_daily_video_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Daily Video Limit</label>
                <input type="number" name="daily_video_limit" id="edit_daily_video_limit" min="0" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="edit_min_withdrawal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum Withdrawal ($)</label>
                <input type="number" name="min_withdrawal" id="edit_min_withdrawal" step="0.01" min="0" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="edit_referral_bonus" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Referral Bonus ($)</label>
                <input type="number" name="referral_bonus" id="edit_referral_bonus" step="0.01" min="0" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                <label for="edit_is_active" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Is Active</label>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeEditPackageModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">Update Package</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Package Modal -->
<div id="deletePackageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Delete Package</h3>
        <p class="text-gray-700 dark:text-gray-300 mb-4">Are you sure you want to delete "<span id="delete_package_name" class="font-semibold"></span>"? This action cannot be undone.</p>
        <form id="deletePackageForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeDeletePackageModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md">Cancel</button>
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddPackageModal() {
        document.getElementById('addPackageModal').classList.remove('hidden');
    }

    function closeAddPackageModal() {
        document.getElementById('addPackageModal').classList.add('hidden');
    }

    function openEditPackageModal(id, name, description, price, validityDays, dailyVideoLimit, minWithdrawal, referralBonus, isActive) {
        document.getElementById('edit_package_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_validity_days').value = validityDays;
        document.getElementById('edit_daily_video_limit').value = dailyVideoLimit;
        document.getElementById('edit_min_withdrawal').value = minWithdrawal;
        document.getElementById('edit_referral_bonus').value = referralBonus;
        document.getElementById('edit_is_active').checked = isActive;
        document.getElementById('editPackageForm').action = `/admin/packages/${id}`;
        document.getElementById('editPackageModal').classList.remove('hidden');
    }

    function closeEditPackageModal() {
        document.getElementById('editPackageModal').classList.add('hidden');
    }

    function openDeletePackageModal(id, name) {
        document.getElementById('delete_package_name').innerText = name;
        document.getElementById('deletePackageForm').action = `/admin/packages/${id}`;
        document.getElementById('deletePackageModal').classList.remove('hidden');
    }

    function closeDeletePackageModal() {
        document.getElementById('deletePackageModal').classList.add('hidden');
    }
</script>
@endsection
