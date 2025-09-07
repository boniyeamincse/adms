@extends('layouts.dashboard')

@section('title', 'Classes - EduPortal')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Classes Management</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage school classes and academic years</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span>{{ $classes->total() }} total classes</span>
                </div>
                <a href="{{ route('classes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Add New Class
                </a>
            </div>
        </div>
    </div>

    <!-- Classes Grid/List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classes as $class)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-200">
            <div class="p-6">
                <!-- Class Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $class->class_name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $class->academic_year }} Academic Year</p>
                        </div>
                    </div>
                </div>

                <!-- Class Stats -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $class->students_count }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Students</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $class->sections_count }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Sections</div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between text-sm">
                    <a href="{{ route('classes.show', $class) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium transition-colors duration-200">
                        <i class="fas fa-eye mr-1"></i>View Details
                    </a>
                    @can('update', $class)
                    <a href="{{ route('classes.edit', $class) }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-medium transition-colors duration-200">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Additional Info Footer -->
            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 rounded-b-xl border-t border-gray-100 dark:border-gray-600">
                <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                    <span>{{ $class->subjects_count }} subjects</span>
                    <span>Created {{ $class->created_at->format('M j, Y') }}</span>
                </div>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="col-span-full">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-graduation-cap text-gray-400 dark:text-gray-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Classes Found</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Get started by creating your first class for this academic year.</p>
                    <a href="{{ route('classes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Create First Class
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($classes->hasPages())
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Showing {{ $classes->firstItem() }} to {{ $classes->lastItem() }} of {{ $classes->total() }} results
            </div>
            <div class="flex items-center space-x-2">
                {{ $classes->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif

</div>
@endsection