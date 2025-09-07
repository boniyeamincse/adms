<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Admit Card System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Additional Scripts for 3D Effects -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/framer-motion/11.3.0/framer-motion.min.js"></script>
        <style>
            @keyframes float1 {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                33% { transform: translateY(-20px) rotate(120deg); }
                66% { transform: translateY(10px) rotate(240deg); }
            }
            @keyframes float2 {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                25% { transform: translateY(-15px) rotate(90deg); }
                75% { transform: translateY(15px) rotate(180deg); }
            }
            @keyframes float3 {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-25px) rotate(180deg); }
            }
            @keyframes float4 {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                40% { transform: translateY(20px) rotate(-90deg); }
                80% { transform: translateY(-10px) rotate(90deg); }
            }
            @keyframes float5 {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                30% { transform: translateY(-30px) rotate(360deg); }
                60% { transform: translateY(20px) rotate(180deg); }
            }
            @keyframes float6 {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                20% { transform: translateY(25px) rotate(90deg); }
                70% { transform: translateY(-15px) rotate(-180deg); }
            }
            .animate-float-1 {
                animation: float1 6s ease-in-out infinite;
            }
            .animate-float-2 {
                animation: float2 5s ease-in-out infinite;
            }
            .animate-float-3 {
                animation: float3 7s ease-in-out infinite;
            }
            .animate-float-4 {
                animation: float4 4s ease-in-out infinite;
            }
            .animate-float-5 {
                animation: float5 8s ease-in-out infinite;
            }
            .animate-float-6 {
                animation: float6 9s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="text-gray-900 dark:text-gray-100 overflow-hidden">
        <!-- 3D Background Container -->
        <div class="fixed inset-0 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-100 dark:from-gray-900 dark:via-indigo-950 dark:to-purple-950">
            <!-- Floating Shapes -->
            <div class="absolute top-20 left-20 w-32 h-32 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full opacity-20 animate-float-1"></div>
            <div class="absolute top-40 right-32 w-24 h-24 bg-gradient-to-br from-pink-400 to-indigo-500 rounded-lg rotate-12 opacity-15 animate-float-2"></div>
            <div class="absolute bottom-32 left-1/4 w-20 h-20 bg-gradient-to-br from-green-400 to-blue-500 rounded-full opacity-25 animate-float-3"></div>
            <div class="absolute top-60 left-1/3 w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg -rotate-12 opacity-20 animate-float-4"></div>
            <div class="absolute bottom-1/3 right-1/4 w-28 h-28 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-full opacity-18 animate-float-5"></div>
            <div class="absolute top-1/4 right-1/6 w-36 h-36 bg-gradient-to-br from-purple-400 to-pink-500 rounded-lg rotate-45 opacity-10 animate-float-6"></div>
        </div>

        <div class="relative min-h-screen flex flex-col">
            <!-- Navigation -->
            <nav class="bg-white/20 dark:bg-gray-800/20 backdrop-blur-md shadow-2xl border-b border-white/10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center">
                                <x-application-logo class="block h-9 w-auto" />
                                <span class="ml-2 text-xl font-semibold text-gray-900/90 dark:text-white/90">Admit Card System</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            @if (Route::has('login'))
                                <div class="flex space-x-4">
                                    @auth
                                        <a href="{{ url('/dashboard') }}" class="text-indigo-600/80 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200 px-3 py-2 rounded-lg text-sm font-medium bg-white/30 hover:bg-white/50 transition-all duration-300">
                                            Dashboard
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="text-indigo-600/80 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200 px-3 py-2 rounded-lg text-sm font-medium bg-white/30 hover:bg-white/50 transition-all duration-300">
                                            Login
                                        </a>
                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg hover:shadow-indigo-500/25 transition-all duration-300">
                                                Register
                                            </a>
                                        @endif
                                    @endauth
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <main class="flex-1 flex items-center justify-center relative">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                    <!-- Logo & Title -->
                    <div class="mb-8 transform -translate-y-4">
                        <div class="bg-gradient-to-br from-white/20 to-indigo-200/20 backdrop-blur-sm rounded-3xl p-8 shadow-2xl border border-white/30 dark:border-indigo-500/20">
                            <div class="flex justify-center mb-6">
                                <x-application-logo class="block h-16 w-16" />
                            </div>
                            <h1 class="text-5xl md:text-7xl font-bold bg-gradient-to-r from-gray-900 via-indigo-900 to-purple-900 dark:from-white dark:via-indigo-200 dark:to-purple-200 bg-clip-text text-transparent tracking-tight">
                                Admit Card System
                            </h1>
                            <p class="mt-4 text-xl md:text-2xl text-gray-600 dark:text-gray-300 font-light">
                                Management System
                            </p>
                            <p class="mt-6 max-w-2xl mx-auto text-base text-gray-500 dark:text-gray-300 leading-relaxed">
                                Streamline your school's exam process with our comprehensive Admit Card Management System.
                                Manage students, exams, fees, and generate admit cards effortlessly.
                            </p>
                            <div class="mt-8 flex justify-center space-x-4">
                                @if (Route::has('login'))
                                    <div class="rounded-xl shadow-xl">
                                        @auth
                                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-8 py-4 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-indigo-500/25 transform hover:scale-105 transition-all duration-300">
                                                <span>Go to Dashboard</span>
                                                <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"></path>
                                                </svg>
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-indigo-500/25 transform hover:scale-105 transition-all duration-300">
                                                <span>Get Started</span>
                                                <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"></path>
                                                </svg>
                                            </a>
                                        @endauth
                                    </div>
                                    <button onclick="toggleDarkMode()" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                        </svg>
                                        Dark Mode
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </main>

                <!-- Features -->
                <div class="py-16 bg-white/20 dark:bg-gray-800/20 backdrop-blur-md">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center">
                            <h2 class="text-base text-indigo-400 font-semibold tracking-wide uppercase">Features</h2>
                            <p class="mt-2 text-4xl leading-8 font-extrabold tracking-tight text-gray-900/90 dark:text-white/90 sm:text-5xl">
                                Everything you need to manage exams
                            </p>
                            <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-300 mx-auto">
                                A comprehensive solution for educational institutions to handle student management, exam scheduling, and admit card generation.
                            </p>
                        </div>

                        <div class="mt-16">
                            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                                <div class="bg-white/20 dark:bg-gray-800/20 backdrop-blur-md rounded-2xl p-6 shadow-2xl border border-white/30 dark:border-gray-600/30 hover:bg-white/30 hover:dark:bg-gray-800/30 transition-all duration-500 transform hover:scale-105 hover:-translate-y-2">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white">Student Management</h3>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-gray-600 dark:text-gray-300">
                                        Complete CRUD operations for students with class and section assignment. Support for bulk import via Excel.
                                    </div>
                                </div>

                                <div class="bg-white/20 dark:bg-gray-800/20 backdrop-blur-md rounded-2xl p-6 shadow-2xl border border-white/30 dark:border-gray-600/30 hover:bg-white/30 hover:dark:bg-gray-800/30 transition-all duration-500 transform hover:scale-105 hover:-translate-y-2">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white">Exam Management</h3>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-gray-600 dark:text-gray-300">
                                        Create and manage standard exams with class/section assignment and seat allocation. Term exams and custom exams supported.
                                    </div>
                                </div>

                                <div class="bg-white/20 dark:bg-gray-800/20 backdrop-blur-md rounded-2xl p-6 shadow-2xl border border-white/30 dark:border-gray-600/30 hover:bg-white/30 hover:dark:bg-gray-800/30 transition-all duration-500 transform hover:scale-105 hover:-translate-y-2">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white">Admit Card Generation</h3>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-gray-600 dark:text-gray-300">
                                        Auto-generate admit cards with student & exam info. PDF export with optional watermark and bulk generation.
                                    </div>
                                </div>

                                <div class="bg-white/20 dark:bg-gray-800/20 backdrop-blur-md rounded-2xl p-6 shadow-2xl border border-white/30 dark:border-gray-600/30 hover:bg-white/30 hover:dark:bg-gray-800/30 transition-all duration-500 transform hover:scale-105 hover:-translate-y-2">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white">Fee Management</h3>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-gray-600 dark:text-gray-300">
                                        Track student fees and payments with receipt generation. Block admit card access for unpaid fees.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Overview -->
                <div class="py-16">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-3xl shadow-2xl overflow-hidden">
                            <div class="max-w-4xl mx-auto py-12 px-4 sm:py-16 sm:px-6 lg:px-8 lg:py-20">
                                <div class="max-w-4xl mx-auto text-center">
                                    <h2 class="text-4xl font-extrabold text-white sm:text-5xl">
                                        Ready to streamline your exam management?
                                    </h2>
                                    <p class="mt-4 text-xl text-purple-100 sm:mt-6">
                                        Join thousands of educational institutions using our system to manage exams efficiently.
                                    </p>
                                    @if (!auth()->check())
                                        <div class="mt-8">
                                            <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-indigo-500/25 transform hover:scale-105">
                                                Create Your Account
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white/20 dark:bg-gray-800/20 backdrop-blur-md border-t border-white/20 dark:border-gray-700/20">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
                    <div class="xl:grid xl:grid-cols-3 xl:gap-8">
                        <div class="space-y-8 xl:col-span-1">
                            <div class="flex items-center">
                                <x-application-logo class="h-8 w-8" />
                                <span class="ml-2 text-lg font-bold text-gray-900/90 dark:text-white/90">Admit Card System</span>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-base">
                                Empowering educational institutions with innovative technology for better exam management.
                            </p>
                        </div>
                        <div class="mt-12 grid grid-cols-2 gap-8 xl:mt-0 xl:col-span-2">
                            <div class="md:grid md:grid-cols-2 md:gap-8">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Features</h3>
                                    <ul class="mt-4 space-y-4">
                                        <li><a href="#" class="text-base text-gray-500 dark:text-gray-400 hover:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">Student Management</a></li>
                                        <li><a href="#" class="text-base text-gray-500 dark:text-gray-400 hover:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">Exam Scheduling</a></li>
                                        <li><a href="#" class="text-base text-gray-500 dark:text-gray-400 hover:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">Admit Card Generation</a></li>
                                        <li><a href="#" class="text-base text-gray-500 dark:text-gray-400 hover:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">Fee Tracking</a></li>
                                    </ul>
                                </div>
                                <div class="mt-12 md:mt-0">
                                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Support</h3>
                                    <ul class="mt-4 space-y-4">
                                        <li><a href="#" class="text-base text-gray-500 dark:text-gray-400 hover:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">Documentation</a></li>
                                        <li><a href="#" class="text-base text-gray-500 dark:text-gray-400 hover:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">API Reference</a></li>
                                        <li><a href="#" class="text-base text-gray-500 dark:text-gray-400 hover:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">Contact Us</a></li>
                                        <li><a href="#" class="text-base text-gray-500 dark:text-gray-400 hover:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">Help Center</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-12 border-t border-gray-200/20 dark:border-gray-700/20 pt-8">
                        <p class="text-base text-gray-400 xl:text-center">
                            &copy; 2024 Admit Card System. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Dark Mode Toggle Script -->
        <script>
            function toggleDarkMode() {
                document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            }

            // Check for saved theme preference or default to light mode
            if (localStorage.getItem('theme') === 'dark' ||
               (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        @vite('resources/js/app.js')
    </body>
</html>
