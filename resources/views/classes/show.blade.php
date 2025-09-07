@extends('layouts.dashboard')

@section('title', $class->class_name . ' - Class Details - EduPortal')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $class->class_name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Academic Year: {{ $class->academic_year }}-{{ $class->academic_year + 1 }}</p>
            </div>
            <div class="flex items-center space-x-4">
                @can('update', $class)
                <a href="{{ route('classes.edit', $class) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit Class
                </a>
                @endcan
                <a href="{{ route('classes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Classes
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Students</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $class->students->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-graduate text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sections</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $class->sections->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-layer-group text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Subjects</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $class->subjects->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Exams</p>
                    <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $class->exams->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Students Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Students</h3>
                    <a href="{{ route('students.index', ['class_id' => $class->id]) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                        View All →
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($class->students->count() > 0)
                    <div class="space-y-4">
                        @foreach($class->students->take(5) as $student)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs font-medium">{{ substr($student->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Roll: {{ $student->roll_no ?: 'Not assigned' }}
                                        {{ $student->section ? '• ' . $student->section->section_name : '' }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('students.show', $student) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                        @endforeach
                        @if($class->students->count() > 5)
                        <p class="text-sm text-gray-600 dark:text-gray-400 text-center mt-4">
                            And {{ $class->students->count() - 5 }} more students...
                        </p>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user-graduate text-gray-400 dark:text-gray-600"></i>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">No students enrolled in this class yet.</p>
                        <a href="{{ route('students.create') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mt-2 block">
                            Add first student →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sections Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sections</h3>
                    <a href="{{ route('sections.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                        Manage →
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($class->sections->count() > 0)
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($class->sections as $section)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">{{ $section->section_name }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $section->getFullNameAttribute() }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $section->students->count() }} students</p>
                                </div>
                            </div>
                            <a href="{{ route('sections.show', $section) }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-layer-group text-gray-400 dark:text-gray-600"></i>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">No sections created for this class.</p>
                        <a href="{{ route('sections.create') }}" class="text-sm text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 mt-2 block">
                            Create first section →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Subjects Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Subjects</h3>
                    <a href="{{ route('subjects.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                        Manage →
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($class->subjects->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($class->subjects as $subject)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200">
                            {{ $subject->subject_name }}
                        </span>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book text-gray-400 dark:text-gray-600"></i>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">No subjects assigned to this class.</p>
                        <a href="{{ route('subjects.create') }}" class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 mt-2 block">
                            Add subjects →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Exams Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Exams</h3>
                    <a href="{{ route('exams.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                        Manage →
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($class->exams->count() > 0)
                    <div class="space-y-3">
                        @foreach($class->exams->take(3) as $exam)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $exam->exam_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $exam->start_date->format('M j, Y') }} - {{ $exam->end_date->format('M j, Y') }}
                                </p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $exam->start_date > now() ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200' :
                                   ($exam->end_date >= now() ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200' :
                                   'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200') }}">
                                {{ $exam->start_date > now() ? 'Upcoming' : ($exam->end_date >= now() ? 'Active' : 'Completed') }}
                            </span>
                        </div>
                        @endforeach
                        @if($class->exams->count() > 3)
                        <p class="text-sm text-gray-600 dark:text-gray-400 text-center mt-4">
                            And {{ $class->exams->count() - 3 }} more exams...
                        </p>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-clipboard-list text-gray-400 dark:text-gray-600"></i>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">No exams scheduled for this class.</p>
                        <a href="{{ route('exams.create') }}" class="text-sm text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 mt-2 block">
                            Schedule exam →
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Latest updates and changes for this class</p>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-info-circle text-white text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-900 dark:text-white">Class created successfully</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $class->created_at->format('M j, Y \a\t H:i') }}</p>
                    </div>
                </div>

                @if($class->updated_at != $class->created_at)
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-edit text-white text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-900 dark:text-white">Class information updated</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $class->updated_at->format('M j, Y \a\t H:i') }}</p>
                    </div>
                </div>
                @endif

                @if($class->students->count() === 0 && $class->sections->count() === 0)
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-900 dark:text-white">Setup required: Please add sections and enroll students</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">To get started with this class</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection