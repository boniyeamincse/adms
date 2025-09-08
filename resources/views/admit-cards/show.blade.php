@extends('layouts.dashboard')

@section('title', 'Admit Card Details - EduPortal')

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
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Admit Card Details</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Card #{{ $admitCard->card_number }} • {{ $admitCard->student->name }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="px-3 py-1 text-sm rounded-full
                    {{ $admitCard->exam->start_date > now() ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200' :
                       ($admitCard->exam->end_date >= now() ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200' :
                       'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200') }}">
                    {{ $admitCard->exam->start_date > now() ? 'Upcoming' : ($admitCard->exam->end_date >= now() ? 'Active' : 'Completed') }}
                </span>
                <a href="{{ route('admit-cards.download', $admitCard) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-download mr-2"></i>Download PDF
                </a>
                <a href="{{ route('admit-cards.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Cards
                </a>
            </div>
        </div>
    </div>

    <!-- Admit Card Preview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Card Preview -->
        <div class="lg:col-span-2">
            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 rounded-xl border-2 border-gray-200 dark:border-gray-600 shadow-lg">
                <div class="p-8">
                    <!-- Header Section -->
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-graduation-cap text-white text-2xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Admit Card</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400">{{ env('SCHOOL_NAME', 'ABC School') }}</p>
                        <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                            <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">#{{ $admitCard->card_number }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Registration Number</div>
                        </div>
                    </div>

                    <!-- Student Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-6 border border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Student Details</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Name:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $admitCard->student->name }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Roll Number:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $admitCard->student->roll_no ?: 'N/A' }}</span>
                                </div>
                                @if($admitCard->student->section)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Section:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $admitCard->student->section->section_name }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Class:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $admitCard->student->schoolClass->class_name }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-700 rounded-lg p-6 border border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Exam Information</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Exam Name:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $admitCard->exam->exam_name }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Class:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $admitCard->exam->schoolClass->class_name }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Start Date:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $admitCard->exam->start_date->format('M j, Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">End Date:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $admitCard->exam->end_date->format('M j, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seat Information -->
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 rounded-lg p-6 mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $admitCard->seat_number ?: 'TBA' }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Seat Number</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $admitCard->exam->start_date->format('H:i') }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Start Time</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $admitCard->exam->duration ?? 'TBA' }}min</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Duration</div>
                            </div>
                        </div>
                    </div>

                    <!-- Subjects List -->
                    @if($admitCard->exam->subjects->count() > 0)
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-6 border border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Exam Subjects</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($admitCard->exam->subjects as $subject)
                            <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-600 rounded-lg p-3">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $subject->subject_name }}</span>
                                @if($subject->subject_code)
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $subject->subject_code }}</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Instructions -->
                    <div class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                        <p class="mb-2"><strong>Important Instructions:</strong></p>
                        <ul class="text-left max-w-2xl mx-auto space-y-1">
                            <li>• Bring this admit card and a valid photo ID to the examination hall</li>
                            <li>• Reach the examination center 30 minutes before the scheduled time</li>
                            <li>• Electronic devices are not allowed in the examination hall</li>
                            <li>• Follow all instructions given by the invigilator</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Card Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $admitCard->exam->start_date > now() ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200' :
                               ($admitCard->exam->end_date >= now() ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200' :
                               'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200') }}">
                            {{ $admitCard->exam->start_date > now() ? 'Upcoming' : ($admitCard->exam->end_date >= now() ? 'Active' : 'Completed') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Generated:</span>
                        <span class="text-sm text-gray-900 dark:text-white">{{ $admitCard->generated_at->format('M j, Y H:i') }}</span>
                    </div>
                    @if($admitCard->downloaded_at)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Last Downloaded:</span>
                        <span class="text-sm text-gray-900 dark:text-white">{{ $admitCard->downloaded_at->format('M j, Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admit-cards.download', $admitCard) }}" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-download mr-2"></i>Download PDF
                    </a>
                    <button onclick="window.print()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-print mr-2"></i>Print Card
                    </button>
                </div>
            </div>

            <!-- Student Details -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Student Information</h3>
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-white text-xl font-bold">{{ substr($admitCard->student->name, 0, 1) }}</span>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $admitCard->student->name }}</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Email:</span>
                        <span class="text-gray-900 dark:text-white">{{ $admitCard->student->email ?: 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Phone:</span>
                        <span class="text-gray-900 dark:text-white">{{ $admitCard->student->phone ?: 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Exam Details -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Exam Overview</h3>
                <div class="space-y-3">
                    <div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $admitCard->exam->exam_name }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $admitCard->exam->schoolClass->class_name }}</div>
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ $admitCard->exam->start_date->format('M j, Y') }} - {{ $admitCard->exam->end_date->format('M j, Y') }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <i class="fas fa-clock mr-1"></i>
                        {{ $admitCard->exam->subjects->count() }} subjects
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
@media print {
    .bg-white { background-color: white !important; }
    .bg-gray-800 { background-color: white !important; }
    .text-gray-900 { color: black !important; }
    .text-gray-600 { color: black !important; }
    .border-gray-200 { border-color: black !important; }
    .shadow-sm, .shadow-lg { box-shadow: none !important; }
}
</style>
@endsection