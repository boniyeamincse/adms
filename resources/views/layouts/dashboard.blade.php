<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ACMS') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- TailwindCSS and Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,alpinejs"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }

        .sidebar-transition {
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        .content-transition {
            transition: margin-left 0.3s ease;
        }

        /* Custom scrollbar */
        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: rgb(203 213 225) transparent;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: rgb(203 213 225);
            border-radius: 3px;
        }

        /* Dark mode support */
        .dark {
            --tw-bg-opacity: 1;
            background-color: rgb(17 24 39 / var(--tw-bg-opacity));
            color: rgb(229 231 235 / var(--tw-bg-opacity));
        }

        .dark .card {
            background-color: rgb(31 41 55 / var(--tw-bg-opacity));
            border-color: rgb(75 85 99 / var(--tw-bg-opacity));
        }

        .dark .sidebar {
            background-color: rgb(17 24 39 / var(--tw-bg-opacity));
            border-color: rgb(55 65 81 / var(--tw-bg-opacity));
        }

        /* Notification badge */
        .notification-badge {
            position: relative;
        }

        .notification-badge::after {
            content: '';
            position: absolute;
            top: -8px;
            right: -8px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid white;
        }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-300" x-data="app">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden" :class="{ 'lg:ml-64': sidebarOpen }">
            <!-- Top Navigation -->
            @include('components.navbar')

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Dark Mode Toggle Script -->
    <script>
        // Dark mode functionality
        function initTheme() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }

        // Initialize theme on page load
        initTheme();

        // Alpine.js data
        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                sidebarOpen: window.innerWidth >= 1024,

                init() {
                    window.addEventListener('resize', () => {
                        if (window.innerWidth >= 1024) {
                            this.sidebarOpen = true;
                        } else {
                            this.sidebarOpen = false;
                        }
                    });
                },

                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                },

                closeSidebar() {
                    if (window.innerWidth < 1024) {
                        this.sidebarOpen = false;
                    }
                }
            }));
        });

        // Chart.js defaults
        Chart.defaults.responsive = true;
        Chart.defaults.maintainAspectRatio = false;
        Chart.defaults.plugins.legend.display = true;
    </script>

    @stack('scripts')
</body>
</html>