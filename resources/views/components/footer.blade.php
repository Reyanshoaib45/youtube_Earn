<footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="px-4 py-6 lg:px-6">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-sm text-gray-600 mb-4 md:mb-0">
                © {{ date('Y') }} VideoRewards Platform. All rights reserved.
            </div>
            
            <div class="flex items-center space-x-6 text-sm text-gray-600">
                <a href="#" class="hover:text-gray-900 transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-gray-900 transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-gray-900 transition-colors">Support</a>
                
                <!-- System Status -->
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-xs">System Online</span>
                </div>
            </div>
        </div>
        
        <!-- Additional footer info for admins -->
        @if(auth()->check() && auth()->user()->role === 'admin')
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex flex-wrap items-center justify-between text-xs text-gray-500">
                    <div class="flex items-center space-x-4">
                        <span>Laravel {{ app()->version() }}</span>
                        <span>PHP {{ PHP_VERSION }}</span>
                        <span>Server Time: {{ now()->format('Y-m-d H:i:s') }}</span>
                    </div>
                    <div class="flex items-center space-x-4 mt-2 md:mt-0">
                        <span>Memory: {{ round(memory_get_usage(true) / 1024 / 1024, 2) }}MB</span>
                        <span>Load Time: {{ round((microtime(true) - LARAVEL_START) * 1000, 2) }}ms</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</footer>
