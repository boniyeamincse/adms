@extends('layouts.dashboard')

@section('title', 'Exam Details - EduPortal')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $exam->exam_name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $exam->schoolClass->class_name }} - {{ ucfirst($exam->exam_type) }} Examination</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('exams.edit', $exam) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit Exam
                </a>
                <a href="{{ route('exams.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Exams
                </a>
            </div>
        </div>
    </div>

    <!-- Exam Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Exam Period</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $exam->start_date->format('M d') }} - {{ $exam->end_date->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Students</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $exam->admitCards->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Subjects</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $exam->subjects->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chair text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Exam Hall</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        @if($exam->admitCards->whereNotNull('exam_seat_id')->count() > 0)
                            Assigned
                        @else
                            Not Set
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Exam Details -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Exam Details</h3>
            </div>
            <div class="p-6 space-y-4">
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Exam Name</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $exam->exam_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Class</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $exam->schoolClass->class_name }} ({{ $exam->schoolClass->academic_year }})</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Exam Type</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                {{ ucfirst($exam->exam_type) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Duration</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">
                            {{ $exam->start_date->format('M d, Y') }} to {{ $exam->end_date->format('M d, Y') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Subjects -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Subjects ({{ $exam->subjects->count() }})</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($exam->subjects as $subject)
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $subject->subject_name }}</span>
                            <span class="px-2 py-1 rounded-full text-xs bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                Subject ID: {{ $subject->id }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">No subjects assigned to this exam.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Actions</h3>
        </div>
        <div class="p-6">
            <div class="flex flex-wrap gap-4">
                @if($exam->admitCards->count() == 0)
                    <a href="{{ route('exams.generate-admit-cards', $exam) }}"
                       onclick="return confirm('Generate admit cards for all students in {{ $exam->schoolClass->class_name }}?')"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-id-card mr-2"></i>Generate Admit Cards
                    </a>
                @endif

                @if($exam->admitCards->count() > 0)
                    <a href="{{ route('exam.seating.create', $exam) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-chair mr-2"></i>Create Seating Plan
                    </a>
                    <a href="{{ route('exam.seating.show', $exam) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-eye mr-2"></i>View Seating Plan
                    </a>
                @endif

                <button class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-file-pdf mr-2"></i>Export Report
                </button>
            </div>
        </div>
    </div>

    <!-- Admit Cards List -->
    @if($exam->admitCards->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Admit Cards ({{ $exam->admitCards->count() }})</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Roll No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Section</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Seat No</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($exam->admitCards as $admitCard)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $admitCard->student->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $admitCard->student->roll_no ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $admitCard->student->section->section_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $admitCard->seat_number ?: 'Not assigned' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
                                <a href="{{ route('admit-cards.show', $admitCard) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">View</a>
                                <a href="{{ route('admit-cards.download', $admitCard) }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 transition-colors duration-200">Download</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection