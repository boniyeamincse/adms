@extends('layouts.dashboard')

@section('title', 'Education Portal - Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 xl:px-12 py-4 sm:py-6 lg:py-8">
        <!-- Header Section -->
        <div class="mb-6 sm:mb-8 lg:mb-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <div class="flex-1">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl xl:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Welcome back, {{ auth()->user()->name }} 👋
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 max-w-2xl">
                        Here's what's happening with your school management system today.
                    </p>
                </div>
                <div class="flex items-center justify-start lg:justify-end space-x-2 sm:space-x-3">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm px-3 py-2 sm:px-4 rounded-lg border border-gray-200 dark:border-gray-700">
                        <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Last updated:</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white ml-1">{{ now()->format('M j, g:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        @if(auth()->user()->isSuperAdmin())
        <div class="mb-6 sm:mb-8 lg:mb-10">
            <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white mb-4 sm:mb-6">Quick Actions</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
                <a href="{{ route('classes.index') }}" class="group relative overflow-hidden bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 text-white shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-school text-lg sm:text-xl"></i>
                            </div>
                            <i class="fas fa-arrow-right text-white/70 group-hover:text-white transition-colors text-sm sm:text-base"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold mb-2">Manage Classes</h3>
                        <p class="text-blue-100 text-xs sm:text-sm leading-relaxed">Add and edit class sections with comprehensive management tools</p>
                    </div>
                </a>

                <a href="{{ route('students.index') }}" class="group relative overflow-hidden bg-gradient-to-r from-green-600 to-green-700 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 text-white shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-user-graduate text-lg sm:text-xl"></i>
                            </div>
                            <i class="fas fa-arrow-right text-white/70 group-hover:text-white transition-colors text-sm sm:text-base"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold mb-2">Student Management</h3>
                        <p class="text-green-100 text-xs sm:text-sm leading-relaxed">Import bulk data and manage student records efficiently</p>
                    </div>
                </a>

                <a href="#" onclick="generateReport()" class="group relative overflow-hidden bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 text-white shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 cursor-pointer sm:col-span-2 lg:col-span-1">
                    <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-chart-line text-lg sm:text-xl"></i>
                            </div>
                            <i class="fas fa-arrow-right text-white/70 group-hover:text-white transition-colors text-sm sm:text-base"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold mb-2">Analytics & Reports</h3>
                        <p class="text-purple-100 text-xs sm:text-sm leading-relaxed">Generate comprehensive PDF reports and analytics</p>
                    </div>
                </a>
            </div>
        </div>
        @endif

        <!-- Key Metrics Cards -->
        <div class="mb-6 sm:mb-8 lg:mb-10">
            <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white mb-4 sm:mb-6">Analytics Overview</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6">
                <!-- Students Card -->
                <div class="group relative overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-blue-200/50 dark:border-blue-700/50 shadow-sm hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('students.index') }}'">
                    <div class="absolute top-0 right-0 w-24 h-24 sm:w-28 sm:h-28 lg:w-32 lg:h-32 bg-blue-500/10 rounded-full -mr-12 -mt-12 sm:-mr-14 sm:-mt-14 lg:-mr-16 lg:-mt-16"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-user-graduate text-white text-lg sm:text-xl"></i>
                            </div>
                            <div class="flex items-center space-x-1 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-full">
                                <i class="fas fa-arrow-up text-green-600 text-xs"></i>
                                <span class="text-xs text-green-600 font-medium">+12%</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-blue-600 dark:text-blue-400 mb-1 uppercase tracking-wider">Total Students</p>
                            <p class="text-2xl sm:text-3xl font-bold text-blue-900 dark:text-blue-100 mb-2 sm:mb-3">{{ $stats['students']['total'] }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-1 sm:space-x-2">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    <span class="text-xs text-gray-600 dark:text-gray-300">{{ $stats['students']['active_percentage'] }}% Active</span>
                                </div>
                                <span class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 font-medium transition-colors">
                                    View Details →
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Exams Card -->
                <div class="group relative overflow-hidden bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-purple-200/50 dark:border-purple-700/50 shadow-sm hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('exams.index') }}'">
                    <div class="absolute top-0 right-0 w-24 h-24 sm:w-28 sm:h-28 lg:w-32 lg:h-32 bg-purple-500/10 rounded-full -mr-12 -mt-12 sm:-mr-14 sm:-mt-14 lg:-mr-16 lg:-mt-16"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-clipboard-list text-white text-lg sm:text-xl"></i>
                            </div>
                            <div class="flex items-center space-x-1 bg-orange-100 dark:bg-orange-900/30 px-2 py-1 rounded-full">
                                <i class="fas fa-clock text-orange-600 text-xs"></i>
                                <span class="text-xs text-orange-600 font-medium">{{ $stats['exams']['upcoming'] }} upcoming</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-purple-600 dark:text-purple-400 mb-1 uppercase tracking-wider">Admit Cards Generated</p>
                            <p class="text-2xl sm:text-3xl font-bold text-purple-900 dark:text-purple-100 mb-2 sm:mb-3">{{ $stats['exams']['total_admit_cards'] }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-1 sm:space-x-2">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                    <span class="text-xs text-gray-600 dark:text-gray-300">{{ $stats['exams']['active'] }} Active Exams</span>
                                </div>
                                <span class="text-xs text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-200 font-medium transition-colors">
                                    Manage Exams →
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fees Card -->
                <div class="group relative overflow-hidden bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-green-200/50 dark:border-green-700/50 shadow-sm hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('fees.index') }}'">
                    <div class="absolute top-0 right-0 w-24 h-24 sm:w-28 sm:h-28 lg:w-32 lg:h-32 bg-green-500/10 rounded-full -mr-12 -mt-12 sm:-mr-14 sm:-mt-14 lg:-mr-16 lg:-mt-16"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-coins text-white text-lg sm:text-xl"></i>
                            </div>
                            <div class="flex items-center space-x-1 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-full">
                                <i class="fas fa-chart-line text-green-600 text-xs"></i>
                                <span class="text-xs text-green-600 font-medium">{{ $stats['fees']['paid_percentage'] }}% collected</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-green-600 dark:text-green-400 mb-1 uppercase tracking-wider">Fees This Month</p>
                            <p class="text-2xl sm:text-3xl font-bold text-green-900 dark:text-green-100 mb-2 sm:mb-3">৳{{ number_format($stats['fees']['paid_amount']) }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-1 sm:space-x-2">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-xs text-gray-600 dark:text-gray-300">{{ $stats['fees']['paid'] }}/{{ $stats['fees']['total'] }} collected</span>
                                </div>
                                <span class="text-xs text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200 font-medium transition-colors">
                                    View Details →
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payments Card -->
                <div class="group relative overflow-hidden bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-yellow-200/50 dark:border-yellow-700/50 shadow-sm hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('payments.index') }}'">
                    <div class="absolute top-0 right-0 w-24 h-24 sm:w-28 sm:h-28 lg:w-32 lg:h-32 bg-yellow-500/10 rounded-full -mr-12 -mt-12 sm:-mr-14 sm:-mt-14 lg:-mr-16 lg:-mt-16"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-credit-card text-white text-lg sm:text-xl"></i>
                            </div>
                            <div class="flex items-center space-x-1 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-full">
                                <i class="fas fa-trending-up text-green-600 text-xs"></i>
                                <span class="text-xs text-green-600 font-medium">Growing</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-yellow-600 dark:text-yellow-400 mb-1 uppercase tracking-wider">Total Payments</p>
                            <p class="text-2xl sm:text-3xl font-bold text-yellow-900 dark:text-yellow-100 mb-2 sm:mb-3">{{ $stats['payments']['total'] }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-1 sm:space-x-2">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                    <span class="text-xs text-gray-600 dark:text-gray-300">Avg: ৳{{ number_format($stats['payments']['avg_payment'], 0) }}</span>
                                </div>
                                <span class="text-xs text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-200 font-medium transition-colors">
                                    View Payments →
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Data Insights Section -->
    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">📊 Data Insights</h2>
                <p class="text-gray-600 dark:text-gray-400">Visual analysis of your school performance metrics</p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="flex items-center space-x-2 bg-white dark:bg-gray-800 rounded-lg p-2 border border-gray-200 dark:border-gray-700">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Live Data</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Students per Class (Bar Chart) -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">👨‍🎓 Students per Class</h3>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Live</span>
                    </div>
                </div>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="classDistributionChart"></canvas>
                </div>
                <div class="mt-4 flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Total classes: 8</span>
                    <span class="text-gray-500 dark:text-gray-400">Avg: {{ $stats['students']['total'] > 0 ? round($stats['students']['total'] / 8) : 0 }} students</span>
                </div>
            </div>

            <!-- Attendance Rate (Line Chart) -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">📊 Attendance Rate</h3>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-sm text-green-600 dark:text-green-400">Excellent</span>
                    </div>
                </div>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="attendanceChart"></canvas>
                </div>
                <div class="mt-4 flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">This month: 95%</span>
                    <span class="text-green-600 dark:text-green-400">↗️ Up 2%</span>
                </div>
            </div>

            <!-- Fee Collection Trend (Area Chart) -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-200 xl:col-span-1">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">💰 Fee Collection Trend</h3>
                    <select id="feeChartPeriod" class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors duration-200">
                        <option value="6">6 Months</option>
                        <option value="12">12 Months</option>
                        <option value="24">2 Years</option>
                    </select>
                </div>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="feeCollectionChart"></canvas>
                </div>
                <div class="mt-4 flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Collected: {{ $stats['fees']['paid_percentage'] }}%</span>
                    <span class="text-gray-500 dark:text-gray-400">Target: 100%</span>
                </div>
            </div>
        </div>

    <!-- Recent Activity & Pending Items -->
    <!-- Recent Updates Panel & Announcements -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">📢 Recent Updates & Announcements</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Stay updated with the latest news and happenings</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Live</span>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <!-- Upcoming Exams Notice -->
                <div class="bg-gradient-to-r from-blue-50 dark:from-blue-900/20 to-indigo-50 dark:to-indigo-900/20 rounded-xl p-4 border-l-4 border-blue-500">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">📝 Upcoming Exams - Schedule Released</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Final examinations for Classes 10-12 will begin from March 15th. Hall tickets will be available for download from March 1st.</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">Priority: High</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">March 1st onwards</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- New Student Registration -->
                <div class="bg-gradient-to-r from-green-50 dark:from-green-900/20 to-emerald-50 dark:to-emerald-900/20 rounded-xl p-4 border-l-4 border-green-500">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">👨‍🎓 New Student Registrations</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Admission open for Nursery to Class 11. Limited seats available. Apply now to secure your child's future.</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium">Status: Open</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Batch: 2025-2026</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fee Payment Reminder -->
                <div class="bg-gradient-to-r from-yellow-50 dark:from-yellow-900/20 to-amber-50 dark:to-amber-900/20 rounded-xl p-4 border-l-4 border-yellow-500">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-money-bill-wave text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">💳 Fee Payment Deadline</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">First quarter fees must be paid by November 30th. Online payment portal available 24/7.</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-yellow-600 dark:text-yellow-400 font-medium">Deadline: Nov 30</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">3 days left</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Maintenance Notice -->
                <div class="bg-gradient-to-r from-purple-50 dark:from-purple-900/20 to-violet-50 dark:to-violet-900/20 rounded-xl p-4 border-l-4 border-purple-500">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-tools text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">🔧 System Maintenance</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Scheduled maintenance on Sunday, 2:00 AM - 4:00 AM. Portal will be temporarily unavailable.</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-purple-600 dark:text-purple-400 font-medium">Scheduled</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">This Sunday</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent System Updates -->
                @forelse($recentActivities->take(2) as $activity)
                <div class="bg-gradient-to-r from-gray-50 dark:from-gray-800/50 to-white dark:to-gray-700 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-gray-500 to-gray-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-sm">{{ $activity['icon'] }}</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-1">{{ $activity['title'] }}</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">{{ $activity['description'] }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}</span>
                                <span class="text-xs text-gray-400 dark:text-gray-500">Activity</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-bell-slash text-gray-400 dark:text-gray-600"></i>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No system activities yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

        <!-- Pending Items -->
        <div class="space-y-6">
            <!-- Pending Admit Cards -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">⏰ Pending Admit Cards</h3>
                        <span class="px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 rounded-full text-sm font-medium">
                            {{ count($pendingAdmitCards) }} pending
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Admit cards waiting for seat allocation</p>
                </div>
                <div class="p-4 space-y-3 max-h-72 overflow-y-auto scrollbar-thin">
                    @forelse($pendingAdmitCards as $card)
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 dark:from-yellow-900/20 to-amber-50 dark:to-yellow-900/10 rounded-lg border border-yellow-200 dark:border-yellow-700 hover:border-yellow-300 dark:hover:border-yellow-600 transition-all duration-200">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/50 rounded-xl flex items-center justify-center shadow-sm">
                                <i class="fas fa-id-card text-yellow-600 dark:text-yellow-400"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $card->student->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $card->exam->exam_name }}</p>
                                <div class="flex items-center mt-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200">
                                        Class {{ $card->student->schoolClass->class_name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors duration-200" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors duration-200" title="Assign Seat">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium mb-2">All Caught Up! 🎉</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500">No pending admit cards to process</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Fee Collection Overview -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">📊 Fee Collection by Class</h3>
                        <a href="{{ route('fees.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                            View All →
                        </a>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Track payment status across all classes</p>
                </div>
                <div class="p-4 space-y-4">
                    @forelse($feeCollectionStats as $stat)
                    <div class="group cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 p-3 rounded-lg transition-colors duration-200" onclick="window.location.href='{{ route('fees.index') }}'">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $stat['class'] }}</p>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $stat['paid_fees'] }}/{{ $stat['total_fees'] }} paid</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                <div class="bg-gradient-to-r {{ $stat['collection_rate'] >= 80 ? 'from-green-400 to-green-500' : ($stat['collection_rate'] >= 50 ? 'from-yellow-400 to-yellow-500' : 'from-red-400 to-red-500') }} h-3 rounded-full transition-all duration-300 ease-out shadow-sm" style="width: {{ $stat['collection_rate'] }}%"></div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-bold {{ $stat['collection_rate'] >= 80 ? 'text-green-600 dark:text-green-400' : ($stat['collection_rate'] >= 50 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">{{ $stat['collection_rate'] }}%</span>
                                @if($stat['collection_rate'] >= 80)
                                    <i class="fas fa-trending-up text-green-500"></i>
                                @elseif($stat['collection_rate'] >= 50)
                                    <i class="fas fa-minus text-yellow-500"></i>
                                @else
                                    <i class="fas fa-trending-down text-red-500"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-money-bill-wave text-gray-400 dark:text-gray-600"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No fee data available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick System Status Summary -->
    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-gray-200 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
            <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">System Status: Healthy</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-clock text-gray-400 text-sm"></i>
                    <span class="text-xs sm:text-xs text-gray-500 dark:text-gray-400">Last updated: {{ now()->format('H:i:s') }}</span>
                </div>
            </div>
            <button onclick="refreshAllData()" class="inline-flex items-center justify-center px-3 py-2 sm:py-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 w-full sm:w-auto">
                <i class="fas fa-sync-alt mr-2"></i>
                Refresh
            </button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Charts
    initializeCharts();

    // Refresh data every 60 seconds
    setInterval(refreshStats, 60000);
});

async function initializeCharts() {
    try {
        // Initialize Fee Collection Chart (Area Chart)
        const feeCanvas = document.getElementById('feeCollectionChart');
        if (!feeCanvas) {
            console.warn('feeCollectionChart canvas not found, skipping fee chart initialization');
        } else {
            const feeCtx = feeCanvas.getContext('2d');
            window.feeCollectionChart = new Chart(feeCtx, {
                type: 'line',
                data: await fetchChartData('monthly-fees'),
                options: {
                responsive: true,
                maintainAspectRatio: false,
                fill: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                        },
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280',
                            callback: function(value) {
                                return '৳' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                        },
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                        }
                    }
                }
            }
        });

        // Initialize Class Distribution Chart (Bar Chart)
        const classCanvas = document.getElementById('classDistributionChart');
        if (!classCanvas) {
            console.warn('classDistributionChart canvas not found, skipping class chart initialization');
        } else {
            const classCtx = classCanvas.getContext('2d');
            window.classDistributionChart = new Chart(classCtx, {
                type: 'bar',
                data: await fetchChartData('class-distribution'),
                options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                        },
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                        }
                    },
                    x: {
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                        },
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                        }
                    }
                }
            }
        });

        // Initialize Attendance Chart (Line Chart)
        const attendanceCanvas = document.getElementById('attendanceChart');
        if (!attendanceCanvas) {
            console.warn('attendanceChart canvas not found, skipping attendance chart initialization');
        } else {
            const attendanceCtx = attendanceCanvas.getContext('2d');
            window.attendanceChart = new Chart(attendanceCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Attendance Rate (%)',
                        data: [92, 89, 95, 91, 97, 95],
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 3,
                        pointBackgroundColor: 'rgba(34, 197, 94, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        fill: false,
                        tension: 0.4
                    }]
                },
                options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 80,
                        max: 100,
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                        },
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280',
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                        },
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                        }
                    }
                }
            }
        });

        // Handle fee chart period changes
        document.getElementById('feeChartPeriod')?.addEventListener('change', async function(e) {
            const period = e.target.value;
            const newData = await fetchChartData('monthly-fees', { period: period });
            window.feeCollectionChart.data = newData;
            window.feeCollectionChart.update();
        });

    } catch (error) {
        console.error('Error initializing charts:', error);
        // Gracefully handle missing notifyError function
        if (typeof notifyError === 'function') {
            notifyError('Error loading dashboard charts');
        } else {
            console.warn('notifyError not available, chart initialization failed silently');
        }
    }
}

async function fetchChartData(type, params = {}) {
    try {
        const queryParams = new URLSearchParams({ type, ...params });
        const response = await fetch(`/dashboard/chart-data?${queryParams}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching chart data:', error);
        return { labels: [], datasets: [] };
    }
}

async function refreshStats() {
    try {
        const response = await fetch('/dashboard/stats-api', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        if (response.ok) {
            const data = await response.json();
            // Update stats cards with new data
            updateStatsCards(data.data);
        }
    } catch (error) {
        console.error('Error refreshing stats:', error);
        // Gracefully handle missing notification functions
        if (typeof notifyError === 'function') {
            notifyError('Failed to refresh stats. Check console for details.');
        }
    }
}

function updateStatsCards(stats) {
    // Update student count
    updateStatCard('total-students', stats.students.total);
    updateStatCard('active-students-percent', `${stats.students.active_percentage}%`);

    // Update exam stats
    updateStatCard('total-admit-cards', stats.exams.total_admit_cards);
    updateStatCard('upcoming-exams', stats.exams.upcoming);

    // Update fee stats
    updateStatCard('fees-collected', `৳${stats.fees.paid_amount.toLocaleString()}`);
    updateStatCard('fee-collection-rate', `${stats.fees.paid_percentage}%`);

    // Update payment stats
    updateStatCard('total-payments', stats.payments.total);
    updateStatCard('avg-payment', `৳${stats.payments.avg_payment.toLocaleString()}`);
}

function updateStatCard(selector, value) {
    // This is a placeholder for updating specific card values
    // In a real application, you'd have specific IDs for each stat
    console.log(`Updating ${selector} with value: ${value}`);
}

// Update active navigation items
function updateActiveNav() {
    // Add active class to current dashboard nav item
    const currentPath = window.location.pathname;
    const navItems = document.querySelectorAll('.nav-item');

    navItems.forEach(item => {
        const link = item.querySelector('a');
        if (link && link.getAttribute('href') === currentPath) {
            item.classList.add('bg-blue-100', 'dark:bg-blue-900', 'text-blue-700', 'dark:text-blue-300');
        }
    });
}

// Dashboard-specific initialization
document.addEventListener('DOMContentLoaded', function() {
    updateActiveNav();

    // Load saved theme for compatibility
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.classList.toggle('dark', savedTheme === 'dark');

    // Demo notification to test the system (remove in production)
    setTimeout(() => {
        notifySuccess('Welcome to EduPortal Dashboard!');
    }, 1000);

    // Add mobile responsiveness handling
    window.addEventListener('resize', () => {
        initNotificationSystem();
    });
});

// Generate report function
async function generateReport() {
    try {
        // Show loading toast or notification
        notifyInfo('Generating report...');

        const response = await fetch('/dashboard/export-report', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `dashboard_report_${new Date().toISOString().split('T')[0]}.pdf`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);

        notifySuccess('Report generated successfully!');

    } catch (error) {
        console.error('Error generating report:', error);
        notifyError('Failed to generate report. Please try again.');
    }
}

// Refresh all dashboard data
async function refreshAllData() {
    try {
        // Refresh stats
        await refreshStats();

        // Refresh chart data
        const newChartData = await fetchChartData('monthly-fees');
        if (window.feeCollectionChart) {
            window.feeCollectionChart.data = newChartData;
            window.feeCollectionChart.update();
        }

        const newPieData = await fetchChartData('class-distribution');
        if (window.classDistributionChart) {
            window.classDistributionChart.data = newPieData;
            window.classDistributionChart.update();
        }

        notifySuccess('Dashboard data refreshed!');

    } catch (error) {
        console.error('Error refreshing data:', error);
        // Gracefully handle missing notification functions
        if (typeof notifyError === 'function') {
            notifyError('Failed to refresh data.');
        }
    }
}

// Enhanced notification system with better positioning and mobile support
let notificationContainer = null;
let notificationCounter = 0;

function initNotificationSystem() {
    // Create notification container if it doesn't exist
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        notificationContainer.className = 'fixed top-4 right-4 z-[100] space-y-2 max-w-sm sm:max-w-md w-full';
        notificationContainer.style.cssText = 'right: 1rem; top: 1rem; width: auto; max-width: 28rem; z-index: 100;';

        // Debug: Log notification container creation with high z-index
        if (window.__POPUP_DEBUG_ENABLED__) {
            console.log('Notification Container Created:', {
                zIndex: '100 (highest priority)',
                position: 'fixed',
                id: 'notification-container',
                location: 'top-right',
                couldOverlap: 'Yes - higher than dropdown z-50',
                currentTime: new Date().toISOString()
            });
        }

        document.body.appendChild(notificationContainer);

        // Mobile responsive positioning
        if (window.innerWidth < 640) { // sm breakpoint
            notificationContainer.className = 'fixed top-4 left-4 right-4 z-[100] space-y-2 w-auto';
            notificationContainer.style.cssText = 'left: 1rem; right: 1rem; top: 1rem; width: auto; z-index: 100;';
        }
    }
}

function showNotification(message, type = 'info', duration = 5000) {
    // Initialize system if needed
    initNotificationSystem();

    // Increment counter for unique IDs
    notificationCounter++;

    // Debug: Log notification creation with z-index
    if (window.__POPUP_DEBUG_ENABLED__) {
        console.log('Creating notification:', {
            id: notificationCounter,
            type: type,
            message: message,
            zIndex: '100 (notification container)', // The container has z-[100]
            currentTime: new Date().toISOString()
        });
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.id = `notification-${notificationCounter}`;
    notification.className = `transform translate-x-full opacity-0 transition-all duration-300 ease-out p-4 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 backdrop-blur-sm bg-white/95 dark:bg-gray-800/95`;

    // Set colors and icons based on type
    const styles = {
        success: {
            bg: 'bg-green-500 dark:bg-green-600',
            border: 'border-green-300 dark:border-green-700',
            icon: 'fas fa-check-circle',
            text: 'text-green-800 dark:text-green-100',
            bgLight: 'bg-green-50 dark:bg-green-900/20'
        },
        error: {
            bg: 'bg-red-500 dark:bg-red-600',
            border: 'border-red-300 dark:border-red-700',
            icon: 'fas fa-exclamation-circle',
            text: 'text-red-800 dark:text-red-100',
            bgLight: 'bg-red-50 dark:bg-red-900/20'
        },
        warning: {
            bg: 'bg-yellow-500 dark:bg-amber-600',
            border: 'border-yellow-300 dark:border-amber-700',
            icon: 'fas fa-exclamation-triangle',
            text: 'text-yellow-800 dark:text-amber-100',
            bgLight: 'bg-yellow-50 dark:bg-amber-900/20'
        },
        info: {
            bg: 'bg-blue-500 dark:bg-blue-600',
            border: 'border-blue-300 dark:border-blue-700',
            icon: 'fas fa-info-circle',
            text: 'text-blue-800 dark:text-blue-100',
            bgLight: 'bg-blue-50 dark:bg-blue-900/20'
        }
    };

    const style = styles[type] || styles.info;

    notification.innerHTML = `
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0 w-6 h-6 rounded-full ${style.bg} flex items-center justify-center mt-0.5">
                <i class="${style.icon} text-white text-xs"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium ${style.text} leading-5">${message}</p>
            </div>
            <div class="flex-shrink-0">
                <button onclick="dismissNotification(${notificationCounter})"
                        class="inline-flex rounded-md ${style.text} hover:${style.bgLight} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-${type === 'success' ? 'green' : type === 'error' ? 'red' : type === 'warning' ? 'yellow' : 'blue'}-500 p-1 transition-colors duration-200"
                        aria-label="Dismiss">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </div>

        <!-- Progress bar -->
        <div class="mt-3 bg-gray-200 dark:bg-gray-700 rounded-full h-1">
            <div class="bg-${type === 'success' ? 'green' : type === 'error' ? 'red' : type === 'warning' ? 'yellow' : 'blue'}-500 h-1 rounded-full transition-all duration-100 ease-linear"
                 style="width: 100%; animation: shrink ${duration}ms linear forwards;"></div>
        </div>
    `;

    // Add CSS animation for progress bar
    const styleElement = document.createElement('style');
    styleElement.textContent = `
        @keyframes shrink {
            from { width: 100%; }
            to { width: 0%; }
        }
    `;
    document.head.appendChild(styleElement);

    // Append to container
    notificationContainer.appendChild(notification);

    // Animate in
    requestAnimationFrame(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
        notification.classList.add('translate-x-0', 'opacity-100');
    });

    // Auto dismiss
    if (duration > 0) {
        setTimeout(() => {
            dismissNotification(notificationCounter);
        }, duration);
    }

    return notificationCounter;
}

function dismissNotification(id) {
    const notification = document.getElementById(`notification-${id}`);
    if (notification) {
        notification.classList.add('translate-x-full', 'opacity-0');

        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
                // Clean up empty container
                if (notificationContainer && notificationContainer.children.length === 0) {
                    cleanupNotificationSystem();
                }
            }
        }, 300);
    }
}

function cleanupNotificationSystem() {
    if (notificationContainer && notificationContainer.children.length === 0) {
        // Clean up any remaining styles
        const styles = document.querySelectorAll('style');
        styles.forEach(style => {
            if (style.textContent.includes('@keyframes shrink')) {
                style.remove();
            }
        });
    }
}

// Utility functions for easy notification usage
function notifySuccess(message, duration = 5000) {
    return showNotification(message, 'success', duration);
}

function notifyError(message, duration = 7000) {
    return showNotification(message, 'error', duration);
}

function notifyWarning(message, duration = 6000) {
    return showNotification(message, 'warning', duration);
}

function notifyInfo(message, duration = 5000) {
    return showNotification(message, 'info', duration);
}

// Clear all notifications
function clearAllNotifications() {
    if (notificationContainer) {
        Array.from(notificationContainer.children).forEach(notification => {
            notification.remove();
        });
        cleanupNotificationSystem();
    }
}

// Initialize notification system first
initNotificationSystem();

// Utility functions for easy notification usage
function notifySuccess(message, duration = 5000) {
    return showNotification(message, 'success', duration);
}

function notifyError(message, duration = 7000) {
    return showNotification(message, 'error', duration);
}

function notifyWarning(message, duration = 6000) {
    return showNotification(message, 'warning', duration);
}

function notifyInfo(message, duration = 5000) {
    return showNotification(message, 'info', duration);
}

// Gracefully handle missing functions
if (typeof min !== 'function') {
    window.min = Math.min;
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initNotificationSystem();
});
</script>
@endpush