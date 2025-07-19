@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold">User Management</h1>
                <p class="mt-2 opacity-90">Manage users, managers, and their locations</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users-cog text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-2xl">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $userStats['total_users'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-2xl">
                    <i class="fas fa-user-check text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $userStats['active_users'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-2xl">
                    <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Managers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $userStats['total_managers'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-2xl">
                    <i class="fas fa-user-slash text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Banned Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::where('is_banned', true)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and search -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Name or email..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Roles</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Users</option>
                    <option value="manager" {{ request('role') === 'manager' ? 'selected' : '' }}>Managers</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admins</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banned</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-xl hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Users table -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Users & Managers</h3>
                <button onclick="openCreateManagerModal()" 
                        class="bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Create Manager
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        @if($user->registration_ip)
                                            <div class="text-xs text-gray-400">Reg IP: {{ $user->registration_ip }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 
                                       ($user->role === 'manager' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800') }}">
                                    <i class="fas {{ $user->role === 'admin' ? 'fa-crown' : ($user->role === 'manager' ? 'fa-user-shield' : 'fa-user') }} mr-1"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($user->getCurrentLocation() !== 'Unknown Location')
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                                        {{ $user->getCurrentLocation() }}
                                    </div>
                                    @if($user->current_ip)
                                        <div class="text-xs text-gray-500 font-mono">{{ $user->current_ip }}</div>
                                    @endif
                                @else
                                    <span class="text-gray-400">Unknown</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($user->activePackage)
                                    <div class="text-sm font-medium">{{ $user->activePackage->package->name }}</div>
                                    <div class="text-xs text-gray-500">
                                        Expires: {{ $user->activePackage->end_date->format('M d, Y') }}
                                    </div>
                                @else
                                    <span class="text-gray-400">No Package</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->is_banned)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-ban mr-1"></i>Banned
                                    </span>
                                @elseif($user->is_active)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Active
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <i class="fas fa-pause-circle mr-1"></i>Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.user.details', $user) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($user->role !== 'admin' || $user->id !== auth()->id())
                                        <!-- Role change dropdown -->
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" 
                                                    class="text-purple-600 hover:text-purple-900">
                                                <i class="fas fa-user-cog"></i>
                                            </button>
                                            <div x-show="open" 
                                                 @click.away="open = false"
                                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                                <div class="py-1">
                                                    @if($user->role !== 'admin')
                                                        <button onclick="changeUserRole({{ $user->id }}, 'admin')" 
                                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i class="fas fa-crown mr-2"></i>Make Admin
                                                        </button>
                                                    @endif
                                                    @if($user->role !== 'manager')
                                                        <button onclick="changeUserRole({{ $user->id }}, 'manager')" 
                                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i class="fas fa-user-shield mr-2"></i>Make Manager
                                                        </button>
                                                    @endif
                                                    @if($user->role !== 'user')
                                                        <button onclick="changeUserRole({{ $user->id }}, 'user')" 
                                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i class="fas fa-user mr-2"></i>Make User
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Ban/Unban button -->
                                        @if($user->is_banned)
                                            <button onclick="unbanUser({{ $user->id }})" 
                                                    class="text-green-600 hover:text-green-900" 
                                                    title="Unban User">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        @else
                                            <button onclick="banUser({{ $user->id }})" 
                                                    class="text-red-600 hover:text-red-900" 
                                                    title="Ban User">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p>No users found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Ban User Modal -->
<div id="banUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ban User Account</h3>
            <form id="banUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Ban</label>
                    <textarea name="ban_reason" 
                              rows="4" 
                              required
                              placeholder="Enter the reason for banning this user..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeBanModal()" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700">
                        <i class="fas fa-ban mr-2"></i>Ban User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Manager Modal -->
<div id="createManagerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Create New Manager</h3>
            <form method="POST" action="{{ route('admin.manager.create') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" 
                               name="name" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" 
                               name="email" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" 
                               name="phone_number" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" 
                               name="password" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                            onclick="closeCreateManagerModal()" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700">
                        <i class="fas fa-plus mr-2"></i>Create Manager
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function banUser(userId) {
    document.getElementById('banUserForm').action = `/admin/users/${userId}/ban`;
    document.getElementById('banUserModal').classList.remove('hidden');
}

function unbanUser(userId) {
    if (confirm('Are you sure you want to unban this user?')) {
        fetch(`/admin/users/${userId}/unban`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('User unbanned successfully!', 'success');
                location.reload();
            } else {
                showToast('Error unbanning user', 'error');
            }
        });
    }
}

function changeUserRole(userId, role) {
    if (confirm(`Are you sure you want to change this user's role to ${role}?`)) {
        fetch(`/admin/users/${userId}/role`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ role: role })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(`User role changed to ${role} successfully!`, 'success');
                location.reload();
            } else {
                showToast('Error changing user role', 'error');
            }
        });
    }
}

function closeBanModal() {
    document.getElementById('banUserModal').classList.add('hidden');
}

function openCreateManagerModal() {
    document.getElementById('createManagerModal').classList.remove('hidden');
}

function closeCreateManagerModal() {
    document.getElementById('createManagerModal').classList.add('hidden');
}

// Handle ban form submission
document.getElementById('banUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('User banned successfully!', 'success');
            closeBanModal();
            location.reload();
        } else {
            showToast('Error banning user', 'error');
        }
    })
    .catch(error => {
        showToast('Error banning user', 'error');
    });
});
</script>
@endsection
