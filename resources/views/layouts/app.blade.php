<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }" x-init="$watch('sidebarOpen', value => value || (sidebarOpen = false))">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Video Rewards Platform')</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100 font-sans antialiased">
    <!-- Toast Notifications -->
    <div x-data="{ show: false, message: '', type: 'success' }" x-show="show" x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @toast.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 5000)"
        class="fixed top-4 right-4 z-50 max-w-sm w-full">
        <div :class="type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'"
            class="rounded-2xl shadow-lg text-white p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i
                        :class="type === 'success' ? 'fas fa-check-circle' : type === 'error' ?
                            'fas fa-exclamation-circle' : 'fas fa-info-circle'"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium" x-text="message"></p>
                </div>
                <div class="ml-auto pl-3">
                    <button @click="show = false" class="text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @auth
        <div class="flex h-screen bg-gray-100">
            <!-- Sidebar -->
            <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                @include('components.sidebar')
            </div>

            <!-- Overlay for mobile -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-600 bg-opacity-75 lg:hidden"></div>

            <!-- Main content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Header -->
                @include('components.header')

                <!-- Page content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 lg:p-6">
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                            class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button @click="show = false" class="text-green-400 hover:text-green-600">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                            class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button @click="show = false" class="text-red-400 hover:text-red-600">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('info'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                            class="mb-6 bg-blue-50 border border-blue-200 rounded-2xl p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button @click="show = false" class="text-blue-400 hover:text-blue-600">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </main>

                <!-- Footer -->
                @include('components.footer')
            </div>
        </div>
    @else
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
            @yield('content')
        </div>
    @endauth

    <!-- Loading Indicator -->
    <div x-data="{ loading: false }" x-show="loading" @loading.window="loading = $event.detail.show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 shadow-xl">
            <div class="flex items-center space-x-3">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span class="text-gray-700 font-medium">Loading...</span>
            </div>
        </div>
    </div>

    <script>
        // Global toast function
        window.showToast = function(message, type = 'success') {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    message,
                    type
                }
            }));
        };

        // Global loading function
        window.showLoading = function(show = true) {
            window.dispatchEvent(new CustomEvent('loading', {
                detail: {
                    show
                }
            }));
        };

        // CSRF token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Admin system actions
        function clearCache() {
            if (confirm('Are you sure you want to clear the cache?')) {
                fetch('/admin/clear-cache', {
                        method: 'POST'
                    })
                    .then(() => showToast('Cache cleared successfully!'))
                    .catch(() => showToast('Failed to clear cache', 'error'));
            }
        }

        function backup() {
            if (confirm('Are you sure you want to create a system backup?')) {
                fetch('/admin/backup', {
                        method: 'POST'
                    })
                    .then(() => showToast('Backup initiated successfully!'))
                    .catch(() => showToast('Failed to create backup', 'error'));
            }
        }

        function maintenance() {
            if (confirm('Are you sure you want to toggle maintenance mode?')) {
                fetch('/admin/maintenance', {
                        method: 'POST'
                    })
                    .then(() => showToast('Maintenance mode toggled!'))
                    .catch(() => showToast('Failed to toggle maintenance mode', 'error'));
            }
        }
    </script>
</body>

</html>
