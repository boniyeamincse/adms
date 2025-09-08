@extends('layouts.dashboard')

@section('title', 'Admit Cards Management - EduPortal')

@section('content')
<div class="space-y-6">

    <!-- Main Dashboard Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="relative z-10">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-id-card text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold">Admit Cards Management</h1>
                    <p class="text-blue-100 text-lg">Complete management system for exam admit cards</p>
                </div>
            </div>
            <p class="text-lg text-blue-100 max-w-2xl">
                Generate, manage and distribute exam admit cards with powerful tools for bulk operations,
                real-time analytics, and comprehensive reporting.
            </p>
        </div>

        <!-- Floating decorative elements -->
        <div class="absolute -top-4 -right-4 w-32 h-32 bg-white bg-opacity-10 rounded-full blur-xl"></div>
        <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-white bg-opacity-10 rounded-full blur-xl"></div>
    </div>

    <!-- Quick Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Admit Cards -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Generated</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $totalAdmitCards ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-id-card text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">
                <span class="text-green-600 dark:text-green-400">+12%</span> from last month
            </div>
        </div>

        <!-- Upcoming Exams -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Upcoming Exams</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $upcomingExams ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">
                Next 30 days
            </div>
        </div>

        <!-- Pending Print -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Print</p>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingPrint ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-print text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">
                Ready for printing
            </div>
        </div>

        <!-- This Month -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">This Month</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $thisMonth ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">
                <span class="text-green-600 dark:text-green-400">+{{ $monthlyGrowth ?? 0 }}%</span> growth
            </div>
        </div>
    </div>

    <!-- Main Body Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column - Upcoming Exams & Quick Actions -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Upcoming Exams -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upcoming Exams</h3>
                        <a href="{{ route('exams.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                            View All →
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Mock upcoming exams - you would replace this with real data -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-graduation-cap text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">Final Examination 2025</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Class 10, 12 • Dec 15, 2025</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 text-sm rounded-full">
                                    {{ $examCardCount ?? 245 }}
                                </span>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $examStudentCount ?? 185 }} students</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-book text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">Mid Term Examination</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Classes 8-9 • Oct 25, 2025</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200 text-sm rounded-full">
                                    {{ $midTermCardCount ?? 156 }}
                                </span>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $midTermStudentCount ?? 124 }} students</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-test-paper text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">Quarterly Assessment</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">All Classes • Nov 05, 2025</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 text-sm rounded-full">
                                    {{ $assessmentCardCount ?? 89 }}
                                </span>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $assessmentStudentCount ?? 67 }} students</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Admit Card Batches -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Batches</h3>
                        <a href="{{ route('admit-cards.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                            View All →
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Recent activity mock data -->
                        <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-check-circle text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Math Final Exam Batch</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">85 cards generated • 5 minutes ago</p>
                                </div>
                            </div>
                            <a href="#" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 text-sm font-medium">
                                View →
                            </a>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-print text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Science Mid-Term Batch</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">62 cards printed • 12 minutes ago</p>
                                </div>
                            </div>
                            <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                                View →
                            </a>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">English Assessment Pending</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">45 cards waiting • 1 hour ago</p>
                                </div>
                            </div>
                            <a href="#" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300 text-sm font-medium">
                                Process →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column - Quick Actions & System Status -->
        <div class="space-y-6">

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admit-cards.create') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-plus-circle mr-3"></i>
                        Generate New Cards
                    </a>
                    <a href="#" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-print mr-3"></i>
                        Bulk Print (0 selected)
                        <span class="ml-auto bg-green-800 text-xs px-2 py-1 rounded-full">Soon</span>
                    </a>
                    <a href="#" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-envelope mr-3"></i>
                        Email Distribution
                        <span class="ml-auto bg-green-800 text-xs px-2 py-1 rounded-full">Soon</span>
                    </a>
                    <a href="#" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-chart-bar mr-3"></i>
                        View Reports
                        <span class="ml-auto bg-green-800 text-xs px-2 py-1 rounded-full">Soon</span>
                    </a>
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">System Status</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">PDF Generation</span>
                        </div>
                        <span class="text-sm font-medium text-green-600 dark:text-green-400">Online</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Print Queue</span>
                        </div>
                        <span class="text-sm font-medium text-green-600 dark:text-green-400">Active</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Email Service</span>
                        </div>
                        <span class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Pending</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Database</span>
                        </div>
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Operational</span>
                    </div>
                </div>
            </div>

            <!-- Recent Updates -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Updates</h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-plus text-white text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">New Admit Card Module</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Complete rewrite with enhanced performance</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-chart-bar text-white text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Analytics Dashboard</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Real-time statistics and reporting</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-cog text-white text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Bulk Operations</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Multi-card selection and printing</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection