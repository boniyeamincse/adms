@extends('layouts.dashboard')

@section('title', 'Create New Class - EduPortal')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Class</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Add a new class to your school management system</p>
            </div>
            <a href="{{ route('classes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to Classes
            </a>
        </div>
    </div>

    <!-- Create Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Class Information</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Please fill in all required fields to create a new class.</p>
        </div>

        <form method="POST" action="{{ route('classes.store') }}" class="p-6 space-y-6">
            @csrf

            <!-- Class Name -->
            <div>
                <label for="class_name" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Class Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="class_name"
                       id="class_name"
                       value="{{ old('class_name') }}"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                       placeholder="e.g., Class 10, Grade 8, Year 12"
                       required>
                @error('class_name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter the full name of the class (e.g., Class 10A, Grade 8, Year 12)</p>
            </div>

            <!-- Academic Year -->
            <div>
                <label for="academic_year" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Academic Year <span class="text-red-500">*</span>
                </label>
                <select name="academic_year"
                        id="academic_year"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                        required>
                    <option value="">Select Academic Year</option>
                    @for($year = date('Y') - 2; $year <= date('Y') + 10; $year++)
                        <option value="{{ $year }}" {{ old('academic_year', date('Y')) == $year ? 'selected' : '' }}>
                            {{ $year }}-{{ $year + 1 }}
                        </option>
                    @endfor
                </select>
                @error('academic_year')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Select the academic year for this class (e.g., 2025-2026)</p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-600">
                <a href="{{ route('classes.index') }}"
                   class="mr-4 px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Create Class
                </button>
            </div>
        </form>
    </div>

    <!-- Help Information -->
    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-700">
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-1">What happens next?</h3>
                <div class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                    <p>• After creating this class, you'll be able to add sections to it</p>
                    <p>• You can then assign students to specific sections</p>
                    <p>• Subjects can be assigned to classes for exam management</p>
                    <p>• All class data will be used for reports and analytics</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection