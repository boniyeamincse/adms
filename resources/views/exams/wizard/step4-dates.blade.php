@extends('layouts.dashboard')

@section('title', 'Create Exam - Step 4 of 4 - EduPortal')

@section('content')
<div class="space-y-6">

    <!-- Progress Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Exam</h1>
            <a href="{{ route('exams.wizard.cancel') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <i class="fas fa-times mr-1"></i>Cancel
            </a>
        </div>

        <!-- Progress Steps -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-green-600 text-white rounded-full font-bold">
                    <i class="fas fa-check text-sm"></i>
                </div>
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Academic Year: {{ $examData['academic_year'] }}</span>
            </div>
            <div class="flex items-center">
                <div class="w-8 h-0.5 bg-green-600"></div>
                <div class="ml-4 flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-600 text-white rounded-full font-bold">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $examData['exam_name'] }}</span>
                </div>
                <div class="w-8 h-0.5 bg-green-600"></div>
            </div>
            <div class="flex items-center">
                <div class="ml-4 flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-600 text-white rounded-full font-bold">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $selectedClass->class_name }}</span>
                </div>
                <div class="w-8 h-0.5 bg-green-600"></div>
            </div>
            <div class="flex items-center">
                <div class="ml-4 flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold">4</div>
                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Review & Dates</span>
                </div>
            </div>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-4">Step 4 of 4: Review exam details and set dates</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Exam Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Exam Summary</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Review the exam configuration before finalizing</p>
            </div>

            <div class="p-6 space-y-6">
                <!-- Exam Basic Info -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Exam Name</dt>
                        <dd class="text-sm text-gray-900 dark:text-white font-medium">{{ $examData['exam_name'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Exam Type</dt>
                        <dd class="text-sm text-gray-900 dark:text-white font-medium">
                            <span class="px-2 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                {{ ucfirst($examData['exam_type']) }}
                            </span>
                        </dd>
                    </div>
                </div>

                <!-- Academic Year -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Academic Year</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ $examData['academic_year'] }}</dd>
                </div>

                <!-- Class Info -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Class</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">
                        {{ $selectedClass->class_name }} ({{ $selectedClass->academic_year }})
                        <span class="text-xs text-gray-500 dark:text-gray-400"> - {{ $selectedClass->students()->count() }} students</span>
                    </dd>
                </div>

                <!-- Selected Subjects -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Selected Subjects ({{ $selectedSubjects->count() }})</dt>
                    <div class="flex flex-wrap gap-2">
                        @forelse($selectedSubjects as $subject)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">
                            <i class="fas fa-book mr-1"></i>{{ $subject->subject_name }}
                        </span>
                        @empty
                        <span class="text-xs text-gray-500 dark:text-gray-400">No subjects selected</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Selection Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Set Exam Dates</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Schedule when the exam will take place</p>
            </div>

            <form method="POST" action="{{ route('exams.wizard.complete') }}" class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- Exam Duration Presets -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white mb-4">
                            Quick Duration Setup *
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                            <label class="relative">
                                <input type="radio"
                                       name="duration_preset"
                                       value="60"
                                       class="sr-only peer"
                                       checked>
                                <div class="w-full p-3 text-center border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 hover:border-green-400 dark:hover:border-green-500 transition-all duration-200">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">1 Hour</div>
                                </div>
                            </label>

                            <label class="relative">
                                <input type="radio"
                                       name="duration_preset"
                                       value="120"
                                       class="sr-only peer">
                                <div class="w-full p-3 text-center border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 hover:border-green-400 dark:hover:border-green-500 transition-all duration-200">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">2 Hours</div>
                                </div>
                            </label>

                            <label class="relative">
                                <input type="radio"
                                       name="duration_preset"
                                       value="180"
                                       class="sr-only peer">
                                <div class="w-full p-3 text-center border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 hover:border-green-400 dark:hover:border-green-500 transition-all duration-200">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">3 Hours</div>
                                </div>
                            </label>

                            <label class="relative">
                                <input type="radio"
                                       name="duration_preset"
                                       value="custom"
                                       class="sr-only peer">
                                <div class="w-full p-3 text-center border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 hover:border-green-400 dark:hover:border-green-500 transition-all duration-200">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Custom</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Start Date & Time *
                        </label>
                        <input type="datetime-local"
                               name="start_date"
                               id="start_date"
                               value="{{ old('start_date') }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 text-lg"
                               min="{{ date('Y-m-d\TH:i') }}"
                               required>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Set the exact date and time when the exam begins</p>
                        @error('start_date')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            End Date & Time *
                        </label>
                        <div class="relative">
                            <input type="datetime-local"
                                   name="end_date"
                                   id="end_date"
                                   value="{{ old('end_date') }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 text-lg"
                                   min="{{ date('Y-m-d\TH:i') }}"
                                   required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <span id="duration-display" class="text-sm text-gray-500 dark:text-gray-400"></span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Exam ends at this time - includes exam duration</p>
                        @error('end_date')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mr-3"></i>
                            <div class="text-sm text-blue-800 dark:text-blue-200">
                                <p class="font-medium mb-1">Important Notes:</p>
                                <ul class="space-y-1 text-xs">
                                    <li>• Admit cards will be generated automatically after completion</li>
                                    <li>• Students cannot make changes once the exam is created</li>
                                    <li>• You can still edit details from the exam management page</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Final Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-600">
                        <a href="{{ route('exams.wizard.step3') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Back
                        </a>

                        <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            <i class="fas fa-check mr-2"></i>Create Exam
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    let selectedDuration = 60; // Default 1 hour

    // Initialize all interactive features
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        // Set up duration preset handlers
        setupDurationPresets();

        // Handle start date changes to auto-calculate end date
        startDateInput.addEventListener('change', function() {
            if (this.value) {
                autoCalculateEndDate();
                updateDurationDisplay();
            }
        });

        // Handle end date changes to update duration display
        endDateInput.addEventListener('change', function() {
            updateDurationDisplay();
        });

        // If both dates are empty, set a reasonable default
        if (!startDateInput.value) {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(9, 30, 0, 0); // 9:30 AM tomorrow (avoid exact hour)
            startDateInput.value = tomorrow.toISOString().slice(0, 16);
            autoCalculateEndDate();
            updateDurationDisplay();
        }

        updateActiveNav();
    });

    function setupDurationPresets() {
        const presetInputs = document.querySelectorAll('input[name="duration_preset"]');

        presetInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.value === 'custom') {
                    // Custom duration - don't auto-calculate
                    selectedDuration = null;
                } else {
                    selectedDuration = parseInt(this.value);
                    autoCalculateEndDate();
                    updateDurationDisplay();
                }
            });
        });
    }

    function autoCalculateEndDate() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        if (startDateInput.value && selectedDuration) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(startDate.getTime() + (selectedDuration * 60 * 1000)); // Convert minutes to milliseconds

            endDateInput.min = startDateInput.value;
            endDateInput.value = endDate.toISOString().slice(0, 16);
        }
    }

    function updateDurationDisplay() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const displayElement = document.getElementById('duration-display');

        if (startDateInput.value && endDateInput.value) {
            const start = new Date(startDateInput.value);
            const end = new Date(endDateInput.value);

            if (end > start) {
                const diffMinutes = Math.round((end - start) / (1000 * 60));
                const hours = Math.floor(diffMinutes / 60);
                const minutes = diffMinutes % 60;

                let durationText = '';
                if (hours > 0) {
                    durationText += `${hours}h `;
                }
                if (minutes > 0) {
                    durationText += `${minutes}m`;
                }

                displayElement.textContent = `Duration: ${durationText}`;
            } else {
                displayElement.textContent = 'Invalid duration';
            }
        } else {
            displayElement.textContent = '';
        }
    }

    function addMinutesToDate(dateString, minutes) {
        const date = new Date(dateString);
        return new Date(date.getTime() + (minutes * 60 * 1000));
    }

    function formatDateForInput(date) {
        return date.toISOString().slice(0, 16);
    }

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