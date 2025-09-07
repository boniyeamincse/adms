@extends('layouts.dashboard')

@section('title', 'Edit Class - EduPortal')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Class</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Update class information and settings</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('classes.show', $class) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-eye mr-2"></i>View Class
                </a>
                <a href="{{ route('classes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Classes
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Main Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Class Information</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update the class details below.</p>
            </div>

            <form method="POST" action="{{ route('classes.update', $class) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Class Name -->
                <div>
                    <label for="class_name" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Class Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="class_name"
                           id="class_name"
                           value="{{ old('class_name', $class->class_name) }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                           placeholder="e.g., Class 10, Grade 8, Year 12"
                           required>
                    @error('class_name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
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
                            <option value="{{ $year }}" {{ old('academic_year', $class->academic_year) == $year ? 'selected' : '' }}>
                                {{ $year }}-{{ $year + 1 }}
                            </option>
                        @endfor
                    </select>
                    @error('academic_year')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('classes.index') }}"
                       class="mr-4 px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>Update Class
                    </button>
                </div>
            </form>
        </div>

        <!-- Class Statistics Sidebar -->
        <div class="space-y-6">
            <!-- Current Statistics -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Current Statistics</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Class performance overview</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Students</span>
                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $class->students_count }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Sections</span>
                        <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $class->sections_count }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Subjects</span>
                        <span class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $class->subjects_count }}</span>
                    </div>
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                        <a href="{{ route('classes.show', $class) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                            View Detailed Statistics →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="#"
                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            <i class="fas fa-plus mr-3 text-gray-500"></i>Add Section
                        </a>
                        <a href="#"
                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            <i class="fas fa-users mr-3 text-gray-500"></i>Manage Students
                        </a>
                        <a href="#"
                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            <i class="fas fa-book mr-3 text-gray-500"></i>Assign Subjects
                        </a>

                        @can('delete', $class)
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                            <button onclick="confirmDelete(this)"
                                    data-url="{{ route('classes.destroy', $class) }}"
                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-200">
                                <i class="fas fa-trash mr-3"></i>Delete Class
                            </button>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Creation Info -->
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Class Information</h4>
                <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                    <p>Created: {{ $class->created_at->format('M j, Y \a\t H:i') }}</p>
                    <p>Last Updated: {{ $class->updated_at->format('M j, Y \a\t H:i') }}</p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
function confirmDelete(button) {
    const url = button.getAttribute('data-url');

    if (confirm('Are you sure you want to delete this class? This action cannot be undone!')) {
        // Create and submit the delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;

        // CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        }

        // Method field
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<style>
/* Form validation styles */
.was-validated .form-control:valid,
.form-control.is-valid {
    border-color: #28a745;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.was-validated .form-control:invalid,
.form-control.is-invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3e%3cpath fill='%23dc3545' d='M10.2426 3.34312C10.6331 2.95259 10.6331 2.31943 10.2426 1.9289C9.85208 1.53838 9.21892 1.53838 8.82839 1.9289L6 4.75731L3.17157 1.92888C2.78105 1.53836 2.14788 1.53836 1.75736 1.92888C1.36684 2.3194 1.36684 2.95257 1.75736 3.34308L4.58578 6.17151L1.75736 8.99993C1.36684 9.39045 1.36684 10.0236 1.75736 10.4141C2.14788 10.8047 2.78105 10.8047 3.17157 10.4141L6 7.5857L8.82843 10.4141C9.21895 10.8047 9.85212 10.8047 10.2426 10.4141C10.6332 10.0236 10.6332 9.39045 10.2426 8.99993L7.41422 6.17151L10.2426 3.34312Z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}
</style>
@endpush