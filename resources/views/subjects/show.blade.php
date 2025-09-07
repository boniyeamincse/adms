@extends('layouts.dashboard')

@section('title', $subject->subject_name . ' - Subject Details - EduPortal')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-book text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $subject->subject_name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $subject->schoolClass->class_name }} - {{ $subject->schoolClass->academic_year }}</p>
                    @if($subject->subject_code)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Code: {{ $subject->subject_code }}</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center space-x-4">
                @can('update', $subject)
                <a href="{{ route('subjects.edit', $subject) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit Subject
                </a>
                @endcan
                <a href="{{ route('subjects.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Subjects
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Class</p>
                    <p class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $subject->schoolClass->class_name }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Related Exams</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $subject->exams()->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Academic Year</p>
                    <p class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $subject->schoolClass->academic_year }}-{{ $subject->schoolClass->academic_year + 1 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>

        @if($subject->subject_code)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Subject Code</p>
                    <p class="text-lg font-bold text-orange-600 dark:text-orange-400 font-mono">{{ $subject->subject_code }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hashtag text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Related Exams Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Related Exams</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Exams that include this subject</p>
        </div>
        <div class="p-6">
            @if($subject->exams()->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($subject->exams as $exam)
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $exam->exam_name }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
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
                        <a href="{{ route('exams.show', $exam) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                            View exam details →
                        </a>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clipboard-list text-gray-400 dark:text-gray-600"></i>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">No exams created with this subject yet.</p>
                    <a href="{{ route('exams.create') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mt-2 block">
                        Create first exam →
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Class Information -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Associated Class</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Information about the class this subject belongs to</p>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $subject->schoolClass->class_name }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Academic Year: {{ $subject->schoolClass->academic_year }}-{{ $subject->schoolClass->academic_year + 1 }}</p>
                    </div>
                </div>
                <a href="{{ route('classes.show', $subject->schoolClass) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                    <i class="fas fa-external-link-alt mr-1"></i>View Class
                </a>
            </div>
        </div>
    </div>

</div>
@endsection