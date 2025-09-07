@extends('layouts.dashboard')

@section('title', 'Create New Subject - EduPortal')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Subject</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Add a new subject to your academic curriculum</p>
            </div>
            <a href="{{ route('subjects.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to Subjects
            </a>
        </div>
    </div>

    <!-- Create Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Subject Information</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Please fill in all required fields to create a new subject.</p>
        </div>

        <form method="POST" action="{{ route('subjects.store') }}" class="p-6 space-y-6">
            @csrf

            <!-- Subject Name -->
            <div>
                <label for="subject_name" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Subject Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="subject_name"
                       id="subject_name"
                       value="{{ old('subject_name') }}"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                       placeholder="e.g., Mathematics, English Literature, Physics"
                       required>
                @error('subject_name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter the full name of the subject (e.g., Advanced Mathematics, English Literature)</p>
            </div>

            <!-- Subject Code -->
            <div>
                <label for="subject_code" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Subject Code
                </label>
                <input type="text"
                       name="subject_code"
                       id="subject_code"
                       value="{{ old('subject_code') }}"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                       placeholder="e.g., MATH101, ENG201, PHYS301 (optional)">
                @error('subject_code')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Optional subject code for easy referencing (e.g., MATH101, ENG201)</p>
            </div>

            <!-- Class Selection -->
            <div>
                <label for="class_id" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Associated Class <span class="text-red-500">*</span>
                </label>
                <select name="class_id"
                        id="class_id"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                        required>
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->class_name }} - {{ $class->academic_year }}
                        </option>
                    @endforeach
                </select>
                @error('class_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Select the class this subject will be associated with</p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-600">
                <a href="{{ route('subjects.index') }}"
                   class="mr-4 px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Create Subject
                </button>
            </div>
        </form>
    </div>

    <!-- Help Information -->
    <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6 border border-green-200 dark:border-green-700">
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-info-circle text-green-600 dark:text-green-400"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-green-900 dark:text-green-100 mb-1">What happens next?</h3>
                <div class="text-sm text-green-800 dark:text-green-200 space-y-1">
                    <p>• After creating this subject, it will be available for exam creation</p>
                    <p>• Students in the associated class can be enrolled in this subject</p>
                    <p>• The subject will appear in exam management and seating plans</p>
                    <p>• Subject data will be used for generating subject-wise reports</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection