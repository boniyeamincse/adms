@extends('layouts.dashboard')

@section('title', 'Admit Cards - EduPortal')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-id-card text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Admit Card Management</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Generate, manage and distribute exam admit cards</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span>{{ $admitCards->total() }} total admit cards</span>
                </div>
                <a href="{{ route('admit-cards.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Generate New
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="exam_id" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Filter by Exam
                </label>
                <select name="exam_id" id="exam_id" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                    <option value="">All Exams</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                            {{ $exam->exam_name }} - {{ $exam->schoolClass->class_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="class_id" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Filter by Class
                </label>
                <select name="class_id" id="class_id" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->class_name }} ({{ $class->students_count }} students)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-filter mr-2"></i>Apply Filters
                </button>
                @if(request()->hasAny(['exam_id', 'class_id']))
                    <a href="{{ route('admit-cards.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <form id="bulkActionsForm" method="POST" action="{{ route('admit-cards.bulk-print') }}">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Select All</span>
                        </label>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            <span id="selectedCount">0</span> selected
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200" id="bulkPrintBtn" disabled>
                            <i class="fas fa-print mr-2"></i>Print Selected
                        </button>
                    </div>
                </div>
            </div>

            <!-- Admit Cards Grid -->
            <div class="p-6">
                @if($admitCards->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($admitCards as $admitCard)
                        <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm hover:shadow-lg transition-all duration-200">
                            <div class="p-6">
                                <!-- Selection Checkbox -->
                                <div class="flex items-start justify-between mb-4">
                                    <input type="checkbox" name="admit_card_ids[]" value="{{ $admitCard->id }}" class="bulk-checkbox rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            {{ $admitCard->exam->start_date > now() ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200' :
                                               ($admitCard->exam->end_date >= now() ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200' :
                                               'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200') }}">
                                            {{ $admitCard->exam->start_date > now() ? 'Upcoming' : ($admitCard->exam->end_date >= now() ? 'Active' : 'Completed') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Student Info -->
                                <div class="text-center mb-4">
                                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span class="text-white text-lg font-bold">{{ substr($admitCard->student->name, 0, 1) }}</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $admitCard->student->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Roll: {{ $admitCard->student->roll_no ?: 'N/A' }}
                                        @if($admitCard->student->section)
                                            • {{ $admitCard->student->section->section_name }}
                                        @endif
                                    </p>
                                </div>

                                <!-- Exam Info -->
                                <div class="bg-gray-50 dark:bg-gray-600/50 rounded-lg p-3 mb-4">
                                    <div class="text-sm">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $admitCard->exam->exam_name }}</p>
                                        <p class="text-gray-600 dark:text-gray-400">{{ $admitCard->exam->schoolClass->class_name }} • {{ $admitCard->exam->schoolClass->academic_year }}</p>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ $admitCard->exam->start_date->format('M j, Y') }} - {{ $admitCard->exam->end_date->format('M j, Y') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Seat & Card Number -->
                                <div class="grid grid-cols-2 gap-2 mb-4">
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $admitCard->seat_number ?: 'Not Assigned' }}</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Seat Number</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-purple-600 dark:text-purple-400">#{{ $admitCard->card_number }}</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Card Number</div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-center space-x-3 text-sm">
                                    <a href="{{ route('admit-cards.show', $admitCard) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                    <a href="{{ route('admit-cards.download', $admitCard) }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-medium transition-colors duration-200">
                                        <i class="fas fa-download mr-1"></i>Download
                                    </a>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 rounded-b-xl border-t border-gray-100 dark:border-gray-600">
                                <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                                    <span>Generated: {{ $admitCard->generated_at->format('M j, Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-id-card text-gray-400 dark:text-gray-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Admit Cards Found</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            @if(request('exam_id') || request('class_id'))
                                No admit cards found matching your filters.
                            @else
                                No admit cards have been generated yet.
                            @endif
                        </p>
                        <div class="flex items-center justify-center space-x-4">
                            @if(request('exam_id') || request('class_id'))
                                <a href="{{ route('admit-cards.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                    <i class="fas fa-times mr-2"></i>Clear Filters
                                </a>
                            @endif
                            <a href="{{ route('admit-cards.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>Generate First Card
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($admitCards->hasPages())
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Showing {{ $admitCards->firstItem() }} to {{ $admitCards->lastItem() }} of {{ $admitCards->total() }} results
            </div>
            <div class="flex items-center space-x-2">
                {{ $admitCards->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const bulkCheckboxes = document.querySelectorAll('.bulk-checkbox');
    const selectedCountElement = document.getElementById('selectedCount');
    const bulkPrintBtn = document.getElementById('bulkPrintBtn');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        bulkCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Individual checkbox functionality
    bulkCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();

            // Uncheck select all if any individual checkbox is unchecked
            if (!this.checked) {
                selectAllCheckbox.checked = false;
            }

            // Check select all if all individual checkboxes are checked
            const allChecked = Array.from(bulkCheckboxes).every(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
        });
    });

    function updateSelectedCount() {
        const selectedCount = Array.from(bulkCheckboxes).filter(cb => cb.checked).length;
        selectedCountElement.textContent = selectedCount;
        bulkPrintBtn.disabled = selectedCount === 0;
    }
});
</script>
@endsection