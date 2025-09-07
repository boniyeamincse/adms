<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Admit Card System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased relative overflow-hidden">

        <!-- 3D Background for Login -->
        <div class="fixed inset-0 bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 dark:from-gray-900 dark:via-indigo-950 dark:to-purple-950">
            <!-- Floating Shapes -->
            <div class="absolute top-16 left-16 w-24 h-24 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full opacity-15 animate-float-1"></div>
            <div class="absolute top-32 right-20 w-16 h-16 bg-gradient-to-br from-pink-400 to-indigo-500 rounded-lg rotate-12 opacity-20 animate-float-2"></div>
            <div class="absolute bottom-32 left-1/3 w-20 h-20 bg-gradient-to-br from-green-400 to-blue-500 rounded-full opacity-25 animate-float-3"></div>
            <div class="absolute top-48 right-1/4 w-14 h-14 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg -rotate-12 opacity-20 animate-float-4"></div>
            <div class="absolute bottom-1/4 left-1/5 w-28 h-28 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-full opacity-18 animate-float-5"></div>
        </div>

        <div class="relative min-h-screen flex flex-col justify-center items-center py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md mb-8">
                <div class="flex justify-center">
                    <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 via-indigo-800 to-purple-800 dark:from-white dark:via-indigo-200 dark:to-purple-200 bg-clip-text text-transparent">
                        Admit Card System
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">School Management Portal</p>
                    <div class="mt-4 flex justify-center">
                        <button onclick="toggleDarkMode()" class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-200 bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-700 transition-all duration-200 backdrop-blur-sm">
                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            Dark
                        </button>
                    </div>
                </div>
            </div>

            <div class="sm:mx-auto sm:w-full sm:max-w-md relative">
                <div class="bg-white/20 dark:bg-gray-800/20 backdrop-blur-lg py-8 px-6 shadow-2xl ring-1 ring-white/30 dark:ring-gray-700/30 rounded-3xl border border-white/20 dark:border-gray-600/20"
                     style="backdrop-filter: blur(20px); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.05);">
                    {{ $slot }}

                    @if(Route::has('login') && Route::has('register'))
                        <div class="mt-6 text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                @yield('auth-link', 'Need to register an account? <a href="' . route('register') . '" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">Sign up here</a>')
                            </p>
                        </div>
                    @endif
                </div>

                <div class="mt-8 text-center">
                    <a href="/" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-400 dark:hover:text-indigo-300 transition-all duration-200 inline-flex items-center">
                        <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>

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
            .animate-float-1 { animation: float1 6s ease-in-out infinite; }
            .animate-float-2 { animation: float2 5s ease-in-out infinite; }
            .animate-float-3 { animation: float3 7s ease-in-out infinite; }
            .animate-float-4 { animation: float4 4s ease-in-out infinite; }
            .animate-float-5 { animation: float5 8s ease-in-out infinite; }
        </style>

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
    </body>
</html>
