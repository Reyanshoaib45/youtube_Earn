<footer class="bg-gray-800 text-white py-12 mt-auto">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div>
                <h3 class="text-xl font-bold mb-4 text-indigo-400">💰 Watch & Earn</h3>
                <p class="text-gray-300 mb-4">
                    Make money by watching YouTube videos. Simple, easy, and profitable platform for everyone!
                </p>
                <div class="flex space-x-4">
                    <a href="https://wa.me/+923327257594" class="text-green-400 hover:text-green-300">
                        📱 WhatsApp
                    </a>
                    <a href="mailto:support@watchearn.com" class="text-blue-400 hover:text-blue-300">
                        📧 Email
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold mb-4 text-gray-200">🔗 Quick Links</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('landing') }}" class="hover:text-white transition duration-300">🏠 Home</a>
                    </li>
                    @auth
                        @if (auth()->user()->role === 'user')
                            <li><a href="{{ route('user.dashboard') }}" class="hover:text-white transition duration-300">📊
                                    Dashboard</a></li>
                            <li><a href="{{ route('user.packages') }}" class="hover:text-white transition duration-300">📦
                                    Packages</a></li>
                            <li><a href="{{ route('user.videos') }}" class="hover:text-white transition duration-300">📹
                                    Videos</a></li>
                        @elseif(auth()->user()->role === 'manager')
                            <li><a href="{{ route('manager.dashboard') }}"
                                    class="hover:text-white transition duration-300">📊 Manager Panel</a></li>
                        @elseif(auth()->user()->role === 'admin')
                            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-white transition duration-300">⚙️
                                    Admin Panel</a></li>
                        @endif
                    @else
                        <li><a href="{{ route('register') }}" class="hover:text-white transition duration-300">📝
                                Register</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition duration-300">🔐 Login</a>
                        </li>
                    @endauth
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="font-semibold mb-4 text-gray-200">🆘 Support</h4>
                <ul class="space-y-2 text-gray-400">
                    <li>
                        <a href="https://wa.me/+923327257594" class="hover:text-white transition duration-300">
                            📱 WhatsApp: +92 332 7257594
                        </a>
                    </li>
                    <li>
                        <a href="mailto:support@watchearn.com" class="hover:text-white transition duration-300">
                            📧 support@watchearn.com
                        </a>
                    </li>
                    <li class="text-gray-400">🕒 Support: 24/7 Available</li>
                    <li class="text-gray-400">💬 Live Chat: Available</li>
                </ul>
            </div>

            <!-- Statistics -->
            <div>
                <h4 class="font-semibold mb-4 text-gray-200">📈 Platform Stats</h4>
                <div class="space-y-2 text-gray-400">
                    <p>👥 Active Users: {{ \App\Models\User::where('role', 'user')->count() }}+</p>
                    <p>📦 Packages: {{ \App\Models\Package::where('is_active', true)->count() }}</p>
                    <p>📹 Videos: {{ \App\Models\Video::where('is_active', true)->count() }}+</p>
                    <p>💰 Payouts: Rs.
                        {{ number_format(\App\Models\WithdrawalRequest::where('status', 'approved')->sum('amount'), 0) }}+
                    </p>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-gray-700 mt-8 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-center md:text-left">
                    <p class="text-gray-400">
                        &copy; {{ date('Y') }} Watch & Earn. All rights reserved.
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
                        💡 Start small, earn big - Your financial freedom starts here!
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="flex space-x-4 text-sm text-gray-400">
                        <span>🔒 Secure Platform</span>
                        <span>✅ Verified Payouts</span>
                        <span>🎯 Guaranteed Returns</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
