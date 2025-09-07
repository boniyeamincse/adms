<!-- Sidebar -->
<div x-show="sidebarOpen"
     x-transition:enter="transition-transform duration-300 ease-out"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition-transform duration-300 ease-in"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg sidebar sidebar-transition lg:static lg:inset-0 lg:translate-x-0 lg:shadow-md">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-graduation-cap text-white text-sm"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">ACMS</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Admit Card System</p>
            </div>
        </div>

        <!-- Mobile close button -->
        <button @click="closeSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2 scrollbar-thin overflow-y-auto">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        @php $user = auth()->user(); @endphp

        <!-- Students Menu -->
        @if($user->isSuperAdmin() || $user->isTeacher() || $user->isAccountant())
        <a href="{{ route('students.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('students.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-user-graduate w-5 h-5 mr-3"></i>
            <span class="font-medium">Students</span>
        </a>
        @endif

        <!-- Exams Menu -->
        @if($user->isSuperAdmin() || $user->isTeacher())
        <a href="{{ route('exams.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('exams.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-clipboard-list w-5 h-5 mr-3"></i>
            <span class="font-medium">Exams</span>
        </a>
        @endif

        <!-- Admit Cards Menu -->
        <a href="{{ route('admit-cards.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('admit-cards.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-id-card w-5 h-5 mr-3"></i>
            <span class="font-medium">Admit Cards</span>
        </a>

        <!-- Seating Plans Menu -->
        @if($user->isSuperAdmin() || $user->isTeacher())
        <a href="{{ route('seating-plans.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('exam.seating.*', 'seating-plans.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-chair w-5 h-5 mr-3"></i>
            <span class="font-medium">Seating Plans</span>
        </a>
        @endif

        <!-- Fees Menu -->
        @if($user->isSuperAdmin() || $user->isAccountant())
        <a href="{{ route('fees.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('fees.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-coins w-5 h-5 mr-3"></i>
            <span class="font-medium">Fees</span>
        </a>
        @endif

        <!-- Payments Menu -->
        @if($user->isSuperAdmin() || $user->isAccountant())
        <a href="{{ route('payments.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('payments.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-credit-card w-5 h-5 mr-3"></i>
            <span class="font-medium">Payments</span>
        </a>
        @endif

        <!-- Classes & Sections (Super Admin only) -->
        @if($user->isSuperAdmin())
        <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>
        <a href="{{ route('classes.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('classes.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-building w-5 h-5 mr-3"></i>
            <span class="font-medium">Classes</span>
        </a>
        <a href="{{ route('sections.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('sections.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-users w-5 h-5 mr-3"></i>
            <span class="font-medium">Sections</span>
        </a>
        <a href="{{ route('subjects.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('subjects.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-book w-5 h-5 mr-3"></i>
            <span class="font-medium">Subjects</span>
        </a>
        @endif

        <!-- Administration (Super Admin & Teacher) -->
        @if($user->isSuperAdmin() || $user->isTeacher())
        <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>
        <div class="px-4 py-2">
            <div class="flex items-center text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">
                <i class="fas fa-tools w-4 h-4 mr-2"></i>
                Administration
            </div>
        </div>

        @if($user->isSuperAdmin())
        <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('users.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-user-shield w-5 h-5 mr-3"></i>
            <span class="font-medium">User Management</span>
        </a>
        @endif

        <a href="{{ route('seating-plans.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('exam.seating.*', 'seating-plans.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : '' }}">
            <i class="fas fa-chair w-5 h-5 mr-3"></i>
            <span class="font-medium">Seating Plans</span>
        </a>
        @endif

        <!-- Settings -->
        <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>
        <div class="px-4 py-4">
            <button @click="settingsOpen = !settingsOpen" class="flex items-center w-full text-left text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200 px-4 py-3">
                <i class="fas fa-cogs w-5 h-5 mr-3"></i>
                <span class="font-medium">Settings</span>
                <i class="fas fa-chevron-down w-4 h-4 ml-auto transition-transform duration-200" :class="{ 'rotate-180': settingsOpen }"></i>
            </button>

            <div x-show="settingsOpen" x-transition:enter="transition-all duration-200 ease-out" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-64" x-transition:leave="transition-all duration-200 ease-in" x-transition:leave-start="opacity-100 max-h-64" x-transition:leave-end="opacity-0 max-h-0" class="overflow-hidden ml-6 mt-2 space-y-2">
                @if($user->isSuperAdmin())
                <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">School Settings</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">System Config</a>
                @endif
                <a href="{{ route('profile.view') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Profile Settings</a>
            </div>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-gray-600 dark:text-gray-300"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">
                    {{ auth()->user()->getRoleName() }}
                </p>
            </div>
            <button onclick="toggleTheme()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200">
                <i class="fas fa-moon"></i>
            </button>
        </div>
    </div>
</div>

<!-- Mobile backdrop -->
<div x-show="sidebarOpen" @click="closeSidebar()" class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden" x-transition:enter="transition-opacity duration-300 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>