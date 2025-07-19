@extends('layouts.app')

@section('title', 'Manage Users')
@section('page-title', 'All Users')

@section('content')
    <div class="space-y-6">
        {{-- Search Form --}}
        <form method="GET" action="{{ route('manager.users') }}" class="mb-4">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name or email"
                class="border rounded p-2"
            >
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Search</button>
        </form>

        {{-- Users Table --}}
        <div class="bg-white rounded-2xl shadow p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">#</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Name</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Email</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Registered</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $loop->iteration + ($users->currentPage()-1)*$users->perPage() }}</td>
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-2">
                            @if($user->is_banned)
                                <span class="text-red-600">Banned</span>
                            @elseif(!$user->is_active)
                                <span class="text-gray-600">Inactive</span>
                            @else
                                <span class="text-green-600">Active</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">No users found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
