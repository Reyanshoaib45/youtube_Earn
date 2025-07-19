<div class="flex flex-col h-full bg-white">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 px-4 bg-gradient-to-r from-blue-600 to-purple-600">
        <h1 class="text-xl font-bold text-white">VideoRewards</h1>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        @if(auth()->user()->role === 'user')
            @include('components.sidebars.user-sidebar')
        @elseif(auth()->user()->role === 'manager')
            @include('components.sidebars.manager-sidebar')
        @elseif(auth()->user()->role === 'admin')
            @include('components.sidebars.admin-sidebar')
        @endif
    </nav>

    <!-- User info -->
    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                <span class="text-white font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
            </div>
        </div>
    </div>
</div>
