<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Exam Seating Plans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <!-- Action Bar -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Seating Plan Management</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Create and manage automated seating arrangements for examinations</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('exams.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-clipboard-list mr-2"></i>
                                View Exams
                            </a>
                            <a href="{{ route('classes.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-school mr-2"></i>
                                Manage Halls
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Exams List -->
                @if($exams->count() > 0)
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($exams as $exam)
                            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden">
                                <!-- Exam Header -->
                                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 text-white">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-bold text-lg">{{ Str::limit($exam->exam_name, 20) }}</h4>
                                            <p class="text-sm opacity-90">{{ $exam->schoolClass->class_name }}</p>
                                        </div>
                                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-clipboard-list text-white text-xl"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Exam Details -->
                                <div class="p-4">
                                    <div class="space-y-3">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-calendar text-gray-600 dark:text-gray-400"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Exam Date</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $exam->start_date->format('M d, Y') }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $exam->start_date->format('g:i A') }} - {{ $exam->end_date->format('g:i A') }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-users text-gray-600 dark:text-gray-400"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Students</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $exam->admitCards->count() }} registered</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-6 flex flex-col space-y-2">
                                        <a href="{{ route('exam.seating.create', $exam) }}"
                                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                            <i class="fas fa-magic mr-2"></i>
                                            Generate Seating Plan
                                        </a>

                                        <a href="{{ route('exam.seating.show', $exam) }}"
                                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                            <i class="fas fa-eye mr-2"></i>
                                            View Current Plan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @else
                <!-- Empty State -->
                <div class="p-12">
                    <div class="text-center">
                        <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-chair text-gray-400 dark:text-gray-600 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Exams Available</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Create some exams first to generate seating plans.</p>
                        <a href="{{ route('exams.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Create Exam
                        </a>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>