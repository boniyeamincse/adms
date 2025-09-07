@extends('layouts.dashboard')

@section('title', 'Edit Exam - EduPortal')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Exam</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Modify examination details and settings</p>
            </div>
            <a href="{{ route('exams.show', $exam) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-eye mr-2"></i>View Exam
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-600 dark:text-gray-400">Update the exam information below. All fields marked with * are required.</p>
        </div>

        <form method="POST" action="{{ route('exams.update', $exam) }}" class="p-6 space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Exam Name -->
                <div class="md:col-span-2">
                    <label for="exam_name" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Exam Name *</label>
                    <input type="text" name="exam_name" id="exam_name" value="{{ old('exam_name', $exam->exam_name) }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                           placeholder="e.g., Mid-Term Examination 2025"
                           required>
                    @error('exam_name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Exam Type -->
                <div>
                    <label for="exam_type" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Exam Type *</label>
                    <select name="exam_type" id="exam_type"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                            required>
                        <option value="">Select Exam Type</option>
                        <option value="1st" {{ old('exam_type', $exam->exam_type) == '1st' ? 'selected' : '' }}>1st Term</option>
                        <option value="2nd" {{ old('exam_type', $exam->exam_type) == '2nd' ? 'selected' : '' }}>2nd Term</option>
                        <option value="3rd" {{ old('exam_type', $exam->exam_type) == '3rd' ? 'selected' : '' }}>3rd Term</option>
                        <option value="custom" {{ old('exam_type', $exam->exam_type) == 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                    @error('exam_type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Class Selection -->
                <div>
                    <label for="class_id" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Class *</label>
                    <select name="class_id" id="class_id"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                            required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id', $exam->class_id) == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }} ({{ $class->academic_year }})
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subjects Selection -->
                <div class="md:col-span-2">
                    <label for="subject_ids" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Subjects *</label>
                    <select name="subject_ids[]" id="subject_ids" multiple
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                            required>
                        @if($exam->class_id)
                            @php
                                $selectedSubjects = old('subject_ids', $exam->subjects->pluck('id')->toArray());
                            @endphp
                            @foreach($exam->subjects as $subject)
                                <option value="{{ $subject->id }}" selected>
                                    {{ $subject->subject_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hold Ctrl (or Cmd) to select multiple subjects</p>
                    @error('subject_ids')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Start Date *</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $exam->start_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                           min="{{ date('Y-m-d') }}" required>
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">End Date *</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $exam->end_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                           min="{{ date('Y-m-d') }}" required>
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-600">
                <a href="{{ route('exams.show', $exam) }}" class="mr-4 px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Update Exam
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Initially load subjects for current class
    document.addEventListener('DOMContentLoaded', function() {
        const classId = document.getElementById('class_id').value;
        if (classId) {
            loadSubjects(classId);
        }
    });

    // Class change handler to load subjects dynamically
    document.getElementById('class_id').addEventListener('change', function() {
        const classId = this.value;
        const subjectSelect = document.getElementById('subject_ids');

        if (!classId) {
            subjectSelect.innerHTML = '';
            return;
        }

        loadSubjects(classId);
    });

    function loadSubjects(classId) {
        const subjectSelect = document.getElementById('subject_ids');

        // Fetch subjects for selected class
        fetch(`/exams/class/${classId}/subjects`)
            .then(response => response.json())
            .then(data => {
                subjectSelect.innerHTML = '';
                data.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.subject_name;
                    subjectSelect.appendChild(option);
                });

                // If editing and class changed, we need to re-select previously selected subjects
                // This would require additional logic, but for now subjects will be cleared
            })
            .catch(error => {
                console.error('Error loading subjects:', error);
                subjectSelect.innerHTML = '<option value="">Error loading subjects</option>';
            });
    }

    // Update end date min when start date changes
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = this.value;
        const endDateInput = document.getElementById('end_date');
        endDateInput.min = startDate;
        if (endDateInput.value && endDateInput.value < startDate) {
            endDateInput.value = startDate;
        }
    });

    // Update active nav on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateActiveNav();
    });

    function updateActiveNav() {
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            const link = item.querySelector('a');
            if (link && link.href.includes('exams')) {
                item.classList.add('bg-blue-100', 'dark:bg-blue-900', 'text-blue-700', 'dark:text-blue-300');
            }
        });
    }
</script>
@endpush