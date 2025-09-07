<!-- Top Navbar -->
<header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between px-4 py-3">
        <!-- Left Section -->
        <div class="flex items-center">
            <!-- Mobile menu button -->
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden mr-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200">
                <i class="fas fa-bars text-lg"></i>
            </button>

            <!-- Page Title -->
            <div class="hidden md:block">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                    @yield('page-title', 'Dashboard')
                </h1>
            </div>
        </div>

        <!-- Center Section - Search Bar -->
        <div class="flex-1 max-w-md mx-6 hidden sm:block">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-sm"></i>
                </div>
                <input type="text"
                       placeholder="Search students, exams..."
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-sm">
            </div>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-4">
            <!-- Mobile Search Button -->
            <button class="sm:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200">
                <i class="fas fa-search text-lg"></i>
            </button>

            <!-- Quick Actions Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 relative transition-colors duration-200">
                    <i class="fas fa-plus-circle text-lg"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-3 w-56 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50" x-transition>
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Quick Actions</p>
                    </div>

                    <div class="py-1">
                        @php $user = auth()->user(); @endphp

                        @if($user->isSuperAdmin() || $user->isTeacher())
                        <a href="{{ route('students.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                            <i class="fas fa-user-plus w-4 h-4 mr-3 text-gray-500 dark:text-gray-400"></i>
                            Add Student
                        </a>
                        <a href="{{ route('exams.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                            <i class="fas fa-plus-circle w-4 h-4 mr-3 text-gray-500 dark:text-gray-400"></i>
                            Create Exam
                        </a>
                        @endif

                        @if($user->isSuperAdmin() || $user->isAccountant())
                        <a href="{{ route('fees.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                            <i class="fas fa-coins w-4 h-4 mr-3 text-gray-500 dark:text-gray-400"></i>
                            Create Fee
                        </a>
                        @endif

                        @if($user->isSuperAdmin())
                        <a href="{{ route('classes.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                            <i class="fas fa-building w-4 h-4 mr-3 text-gray-500 dark:text-gray-400"></i>
                            Add Class
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200 notification-badge">
                    <i class="fas fa-bell text-lg"></i>
                </button>

                <!-- Notification Dropdown -->
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-3 w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50" x-transition>
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Notifications</p>
                    </div>

                    <div class="max-h-64 overflow-y-auto">
                        <!-- Sample Notifications - Replace with real data -->
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-plus text-blue-600 dark:text-blue-400 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 dark:text-white">New student registered</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</p>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 dark:text-white">Exam completed successfully</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">5 hours ago</p>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 dark:text-white">Fee payment pending</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">1 day ago</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200">View all notifications</a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button id="profile-dropdown-button" @click="open = !open" class="flex items-center space-x-3 text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-gray-100 transition-colors duration-200">
                    <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-gray-600 dark:text-gray-300"></i>
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ auth()->user()->getRoleName() }}</p>
                    </div>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>

                <!-- Profile Dropdown Menu -->
                <div id="profile-dropdown-menu" x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-3 w-56 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50" x-transition>
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                    </div>

                    <div class="py-1">
                        <a href="{{ route('profile.view') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                            <i class="fas fa-user w-4 h-4 mr-3"></i>
                            Your Profile
                        </a>

                        <button onclick="toggleTheme()" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                            <i class="fas fa-moon w-4 h-4 mr-3"></i>
                            <span id="theme-text">Toggle Theme</span>
                        </button>

                        <div class="border-t border-gray-100 dark:border-gray-600 my-2"></div>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center">
                                <i class="fas fa-sign-out-alt w-4 h-4 mr-3"></i>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile search bar -->
    <div class="sm:hidden px-4 pb-3">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-sm"></i>
            </div>
            <input type="text"
                   placeholder="Search..."
                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-sm">
        </div>
    </div>
</header>

@verbatim
<script>
    // Update theme text in dropdown
    function updateThemeText() {
        const isDark = document.documentElement.classList.contains('dark');
        const themeText = document.getElementById('theme-text');
        if (themeText) {
            themeText.textContent = isDark ? 'Light Mode' : 'Dark Mode';
        }
    }

    // Define toggleTheme function
    function toggleTheme() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');
        if (isDark) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
        updateThemeText();
    }

    // Call initially and when theme changes
    document.addEventListener('DOMContentLoaded', function() {
        updateThemeText();

        // Profile dropdown logging
        const profileButton = document.querySelector('#profile-dropdown-button');
        const profileMenu = document.querySelector('#profile-dropdown-menu');

        if (profileMenu) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                        console.log('Profile Dropdown visibility change');
                    }
                });
            });

            observer.observe(profileMenu, {
                attributes: true,
                attributeFilter: ['style']
            });
        }
    });
</script>
@endverbatim