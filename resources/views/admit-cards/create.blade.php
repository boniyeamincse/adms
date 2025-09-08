@extends('layouts.dashboard')

@section('title', 'Generate Admit Card - EduPortal')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-plus-circle text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Generate Admit Card</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Create admit cards for individual students or bulk generation</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span>{{ $exams->count() }} upcoming exams</span>
                </div>
                <a href="{{ route('admit-cards.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Cards
                </a>
            </div>
        </div>
    </div>

    <!-- Generation Options Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex">
                <button type="button" id="singleTab" class="tab-button active px-6 py-4 text-gray-900 dark:text-white border-b-2 border-blue-500 font-medium">
                    Single Generation
                </button>
                <button type="button" id="bulkTab" class="tab-button px-6 py-4 text-gray-600 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-900 dark:hover:text-white font-medium">
                    Bulk Generation
                </button>
            </nav>
        </div>

        <!-- Single Generation Tab Content -->
        <div id="singleTabContent" class="tab-content p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Generation Form -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Generate Single Admit Card</h3>
                    <form method="POST" action="{{ route('admit-cards.store') }}" class="space-y-6">
                        @csrf

                        <!-- Exam Selection -->
                        <div>
                            <label for="exam_id" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Select Exam <span class="text-red-500">*</span>
                            </label>
                            <select name="exam_id" id="exam_id" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200" required>
                                <option value="">Choose an exam...</option>
                                @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ old('exam_id') == $exam->id ? 'selected' : '' }}>
                                    {{ $exam->exam_name }} - {{ $exam->schoolClass->class_name }} ({{ $exam->start_date->format('M j, Y') }})
                                </option>
                                @endforeach
                            </select>
                            @error('exam_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Select the exam for which you want to generate the admit card</p>
                        </div>

                        <!-- Student Selection -->
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Select Student <span class="text-red-500">*</span>
                            </label>
                            <select name="student_id" id="student_id" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200" required disabled>
                                <option value="">Select an exam first...</option>
                            </select>
                            @error('student_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Choose the student who will receive this admit card</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" id="generateBtn" disabled>
                                <i class="fas fa-id-card mr-2"></i>Generate Admit Card
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Preview Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Preview</h3>
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-lg p-6 border-2 border-dashed border-gray-300 dark:border-gray-500">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-id-card text-white text-2xl"></i>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                Select an exam to see available students
                            </p>
                            <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                <p>• Admit card preview will appear here</p>
                                <p>• Seat number will be auto-assigned</p>
                                <p>• PDF download will be available immediately</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Generation Tab Content -->
        <div id="bulkTabContent" class="tab-content p-6 hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Bulk Generation Form -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Bulk Admit Card Generation</h3>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 text-xs"></i>
                            </div>
                            <div class="text-sm text-yellow-800 dark:text-yellow-200">
                                <p class="font-semibold mb-1">About Bulk Generation</p>
                                <ul class="space-y-1 text-xs">
                                    <li>• Generates admit cards for all eligible students in selected exam</li>
                                    <li>• Only students with active status will receive cards</li>
                                    <li>• Seat numbers will be auto-assigned based on class and roll number</li>
                                    <li>• Existing admit cards will not be overwritten</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admit-cards.bulk-generate') }}" class="space-y-6">
                        @csrf

                        <!-- Exam Selection for Bulk -->
                        <div>
                            <label for="bulk_exam_id" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Select Exam for Bulk Generation <span class="text-red-500">*</span>
                            </label>
                            <select name="exam_id" id="bulk_exam_id" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200" required>
                                <option value="">Choose an exam...</option>
                                @foreach($exams as $exam)
                                <option value="{{ $exam->id }}">
                                    {{ $exam->exam_name }} - {{ $exam->schoolClass->class_name }}
                                    <span class="text-gray-500">(Starts {{ $exam->start_date->format('M j, Y') }})</span>
                                </option>
                                @endforeach
                            </select>
                            @error('exam_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button for Bulk -->
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                                <i class="fas fa-layer-group mr-2"></i>Bulk Generate Admit Cards
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Bulk Preview Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Bulk Generation Info</h3>
                    <div class="space-y-4">
                        <!-- Exam Statistics Card -->
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Generation Summary</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Selected Class:</span>
                                    <span class="text-gray-900 dark:text-white font-medium" id="bulkClassName">--</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Total Students:</span>
                                    <span class="text-gray-900 dark:text-white font-medium" id="bulkTotalStudents">--</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Exam Date:</span>
                                    <span class="text-gray-900 dark:text-white font-medium" id="bulkExamDate">--</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Subjects:</span>
                                    <span class="text-gray-900 dark:text-white font-medium" id="bulkSubjectCount">--</span>
                                </div>
                            </div>
                        </div>

                        <!-- Process Steps -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                            <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-3">Generation Process</h4>
                            <div class="space-y-2 text-sm text-blue-800 dark:text-blue-200">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span>Validate student eligibility</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-clock text-yellow-500"></i>
                                    <span>Assign seat numbers automatically</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-file-pdf text-blue-500"></i>
                                    <span>Generate admit cards</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-envelope text-purple-500"></i>
                                    <span>Ready for distribution</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Generations -->
    @if($exams->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Quick Generation History</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($exams->take(6) as $exam)
            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $exam->exam_name }}</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $exam->schoolClass->class_name }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full
                        {{ $exam->start_date > now() ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200' :
                           ($exam->end_date >= now() ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200' :
                           'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200') }}">
                        {{ $exam->start_date > now() ? 'Upcoming' : ($exam->end_date >= now() ? 'Active' : 'Completed') }}
                    </span>
                </div>
                <div class="text-xs text-gray-600 dark:text-gray-400 mb-3">
                    <div>{{ $exam->start_date->format('M j, Y') }}</div>
                    <div>{{ $exam->subjects->count() }} subjects • {{ $exam->schoolClass->students->count() }} students</div>
                </div>
                <button onclick="selectExam({{ $exam->id }})" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors duration-200">
                    Generate for this exam
                </button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const singleTab = document.getElementById('singleTab');
    const bulkTab = document.getElementById('bulkTab');
    const singleTabContent = document.getElementById('singleTabContent');
    const bulkTabContent = document.getElementById('bulkTabContent');
    const tabButtons = document.querySelectorAll('.tab-button');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all tabs
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'text-gray-900', 'dark:text-white', 'border-blue-500');
                btn.classList.add('text-gray-600', 'dark:text-gray-400', 'border-transparent');
            });

            // Hide all tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Add active class to clicked tab
            this.classList.add('active', 'text-gray-900', 'dark:text-white', 'border-blue-500');
            this.classList.remove('text-gray-600', 'dark:text-gray-400', 'border-transparent');

            // Show corresponding content
            const contentId = this.id.replace('Tab', 'TabContent');
            document.getElementById(contentId).classList.remove('hidden');
        });
    });

    // AJAX loading for students based on exam selection
    document.getElementById('exam_id').addEventListener('change', function() {
        const examId = this.value;
        const studentSelect = document.getElementById('student_id');
        const generateBtn = document.getElementById('generateBtn');

        if (examId) {
            fetch(`/admit-cards/get-students?exam_id=${examId}`)
                .then(response => response.json())
                .then(data => {
                    studentSelect.innerHTML = '<option value="">Select a student...</option>';
                    data.forEach(student => {
                        const option = document.createElement('option');
                        option.value = student.id;
                        option.textContent = `${student.name} - Roll: ${student.roll_no || 'N/A'} - ${student.section ? 'Sec: ' + student.section.section_name : ''}`;
                        studentSelect.appendChild(option);
                    });
                    studentSelect.disabled = false;
                    generateBtn.disabled = false;
                })
                .catch(error => {
                    console.error('Error loading students:', error);
                });
        } else {
            studentSelect.innerHTML = '<option value="">Select an exam first...</option>';
            studentSelect.disabled = true;
            generateBtn.disabled = true;
        }
    });

    // Bulk generation exam selection
    document.getElementById('bulk_exam_id').addEventListener('change', function() {
        const examId = this.value;
        const className = document.getElementById('bulkClassName');
        const totalStudents = document.getElementById('bulkTotalStudents');
        const examDate = document.getElementById('bulkExamDate');
        const subjectCount = document.getElementById('bulkSubjectCount');

        if (examId) {
            const selectedOption = this.options[this.selectedIndex];
            // In a real implementation, you might want to fetch this data via AJAX
            className.textContent = selectedOption.textContent.split(' - ')[1] || '--';
            totalStudents.textContent = 'To be calculated';
            examDate.textContent = selectedOption.textContent.match(/\(([^)]+)\)/)?.[1] || '--';
            subjectCount.textContent = 'To be calculated';
        } else {
            className.textContent = '--';
            totalStudents.textContent = '--';
            examDate.textContent = '--';
            subjectCount.textContent = '--';
        }
    });
});

function selectExam(examId) {
    document.getElementById('singleTab').click();
    document.getElementById('exam_id').value = examId;
    document.getElementById('exam_id').dispatchEvent(new Event('change'));
}
</script>
@endsection