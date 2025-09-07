@extends('layouts.dashboard')

@section('title', 'Subjects - EduPortal')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Subjects Management</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage academic subjects across class levels</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span>{{ $subjects->total() }} total subjects</span>
                </div>
                <a href="{{ route('subjects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Add New Subject
                </a>
            </div>
        </div>
    </div>

    <!-- Subjects Grid/List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($subjects as $subject)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-200">
            <div class="p-6">
                <!-- Subject Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $subject->subject_name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $subject->schoolClass->class_name }} - {{ $subject->schoolClass->academic_year }}</p>
                        </div>
                    </div>
                </div>

                @if($subject->subject_code)
                <!-- Subject Code -->
                <div class="mb-4">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-400">
                        <i class="fas fa-code mr-1"></i>
                        {{ $subject->subject_code }}
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex items-center justify-between text-sm">
                    <a href="{{ route('subjects.show', $subject) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium transition-colors duration-200">
                        <i class="fas fa-eye mr-1"></i>View Details
                    </a>
                    @can('update', $subject)
                    <a href="{{ route('subjects.edit', $subject) }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-medium transition-colors duration-200">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Additional Info Footer -->
            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 rounded-b-xl border-t border-gray-100 dark:border-gray-600">
                <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                    <span>{{ $subject->exams()->count() }} exams</span>
                    <span>Created {{ $subject->created_at->format('M j, Y') }}</span>
                </div>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="col-span-full">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-book text-gray-400 dark:text-gray-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Subjects Found</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Get started by creating your first subject.</p>
                    <a href="{{ route('subjects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Create First Subject
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($subjects->hasPages())
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Showing {{ $subjects->firstItem() }} to {{ $subjects->lastItem() }} of {{ $subjects->total() }} results
            </div>
            <div class="flex items-center space-x-2">
                {{ $subjects->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif

</div>
@endsection