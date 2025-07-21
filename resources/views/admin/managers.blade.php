<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Managers - Watch & Earn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-2xl font-bold text-red-600">Admin Panel</h1>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-red-600">Dashboard</a>
                    <a href="{{ route('admin.managers') }}" class="text-red-600 font-semibold">Managers</a>
                    <a href="{{ route('admin.withdrawals') }}" class="text-gray-600 hover:text-red-600">Withdrawals</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Manager Management</h2>
            <p class="text-gray-600">Create and manage manager accounts</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Create Manager Form -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">➕ Create New Manager</h3>
            <form method="POST" action="{{ route('admin.create-manager') }}">
                @csrf
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                        <input type="text" name="name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                               placeholder="Enter manager's full name">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                        <input type="email" name="email" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                               placeholder="manager@example.com">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                        <input type="password" name="password" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                               placeholder="Enter secure password">
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" 
                            class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition duration-300">
                        Create Manager
                    </button>
                </div>
            </form>
        </div>

        <!-- Managers List -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800">👨‍💼 All Managers</h3>
            </div>
            
            @if($managers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manager</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($managers as $manager)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                                <span class="text-red-600 font-semibold">{{ substr($manager->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $manager->name }}</div>
                                            <div class="text-sm text-gray-500">Manager</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $manager->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $manager->created_at->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $manager->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $manager->is_banned ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $manager->is_banned ? 'Banned' : 'Active' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="editManager({{ $manager->id }})" 
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                    @if(!$manager->is_banned)
                                        <button onclick="banManager({{ $manager->id }})" 
                                                class="text-red-600 hover:text-red-900">Ban</button>
                                    @else
                                        <button onclick="unbanManager({{ $manager->id }})" 
                                                class="text-green-600 hover:text-green-900">Unban</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">No managers created yet</p>
                    <p class="text-gray-400 mt-2">Create your first manager using the form above</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Manager Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Edit Manager</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                        <input type="text" id="editName" name="name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                        <input type="email" id="editEmail" name="email" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditModal()" 
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit" 
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editManager(managerId) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editForm').action = `/admin/managers/${managerId}`;
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function banManager(managerId) {
            if (confirm('Are you sure you want to ban this manager?')) {
                // In a real app, you'd make an AJAX request here
                alert('Manager ban functionality would be implemented here');
            }
        }

        function unbanManager(managerId) {
            if (confirm('Are you sure you want to unban this manager?')) {
                // In a real app, you'd make an AJAX request here
                alert('Manager unban functionality would be implemented here');
            }
        }
    </script>
</body>
</html>
