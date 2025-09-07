@extends('layouts.dashboard')

@section('title', 'Create Exam - Step 2 of 4 - EduPortal')

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
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Academic Year: {{ session('exam_creation.academic_year') }}</span>
            </div>
            <div class="flex items-center">
                <div class="w-8 h-0.5 bg-green-600"></div>
                <div class="ml-4 flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold">2</div>
                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Exam Details</span>
                </div>
                <div class="w-8 h-0.5 bg-gray-300 dark:bg-gray-600"></div>
            </div>
            <div class="flex items-center">
                <div class="ml-4 flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-full font-bold">3</div>
                    <span class="ml-3 text-sm font-medium text-gray-500 dark:text-gray-400">Subjects</span>
                </div>
                <div class="w-8 h-0.5 bg-gray-300 dark:bg-gray-600"></div>
            </div>
            <div class="flex items-center">
                <div class="ml-4 flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-full font-bold">4</div>
                    <span class="ml-3 text-sm font-medium text-gray-500 dark:text-gray-400">Dates</span>
                </div>
            </div>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-4">Step 2 of 4: Enter exam name and select type</p>
    </div>

    <!-- Exam Details Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Exam Information</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Provide a clear name for the exam and select its type.</p>
        </div>

        <form method="POST" action="{{ route('exams.wizard.step2.post') }}" class="p-6 space-y-6">
            @csrf

            <!-- Exam Name -->
            <div>
                <label for="exam_name" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Exam Name *
                </label>
                <input type="text"
                       name="exam_name"
                       id="exam_name"
                       value="{{ old('exam_name') }}"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 text-lg"
                       placeholder="e.g., First Terminal Exam, Mid-Term Assessment, Final Examination"
                       required>
                @error('exam_name')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Use a descriptive name that clearly identifies the exam purpose and scope.</p>
            </div>

            <!-- Exam Type Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-4">
                    Exam Type *
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- 1st Term -->
                    <label class="relative">
                        <input type="radio"
                               name="exam_type"
                               value="1st"
                               class="sr-only peer"
                               {{ old('exam_type') == '1st' ? 'checked' : '' }}
                               required>
                        <div class="w-full p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-book-open text-green-600 dark:text-green-400"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white">1st Term</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">First terminal examination</div>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- 2nd Term -->
                    <label class="relative">
                        <input type="radio"
                               name="exam_type"
                               value="2nd"
                               class="sr-only peer"
                               {{ old('exam_type') == '2nd' ? 'checked' : '' }}>
                        <div class="w-full p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-graduation-cap text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white">2nd Term</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Second terminal examination</div>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- 3rd Term -->
                    <label class="relative">
                        <input type="radio"
                               name="exam_type"
                               value="3rd"
                               class="sr-only peer"
                               {{ old('exam_type') == '3rd' ? 'checked' : '' }}>
                        <div class="w-full p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-certificate text-purple-600 dark:text-purple-400"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white">3rd Term</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Third terminal examination</div>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Custom -->
                    <label class="relative">
                        <input type="radio"
                               name="exam_type"
                               value="custom"
                               class="sr-only peer"
                               {{ old('exam_type') == 'custom' ? 'checked' : '' }}>
                        <div class="w-full p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/50 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-wrench text-orange-600 dark:text-orange-400"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white">Custom</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Special or additional examination</div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>

                @error('exam_type')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Navigation Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-600 mt-6">
                <a href="{{ route('exams.wizard.step1') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>

                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    Next: Select Subjects
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
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

    // Auto-focus on exam name input
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('exam_name').focus();
    });
</script>
@endpush