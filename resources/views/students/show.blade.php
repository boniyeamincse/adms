<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Student Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('students.edit', $student) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit Student
                </a>
                <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Students
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <!-- Student Header -->
                <div class="px-6 py-8 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center space-x-6">
                        <div class="flex-shrink-0">
                            @if($student->photo)
                                <img class="h-24 w-24 rounded-xl object-cover shadow-lg border-4 border-white"
                                     src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}">
                            @else
                                <div class="h-24 w-24 rounded-xl bg-gray-400 flex items-center justify-center shadow-lg border-4 border-white">
                                    <span class="text-white text-3xl font-bold">{{ substr($student->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $student->name }}</h1>
                            <div class="flex items-center space-x-4 mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $student->status === 'active' ? 'bg-green-100 text-green-800' :
                                       ($student->status === 'inactive' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                                <span class="text-lg text-gray-600">
                                    {{ $student->schoolClass->class_name }} {{ $student->section->section_name }} - {{ $student->schoolClass->academic_year }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Roll Number</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->roll_no ?: '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                    <dd class="text-sm text-gray-900">
                                        @if($student->dob)
                                            {{ $student->dob->format('F j, Y') }} ({{ $student->dob->age }} years old)
                                        @else
                                            -
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Enrollment Date</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->created_at->format('F j, Y') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Academic Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Academic Information</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Class</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->schoolClass->class_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Section</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->section->section_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Academic Year</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->schoolClass->academic_year }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Class Teacher</dt>
                                    <dd class="text-sm text-gray-900">TBA</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('students.edit', $student) }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Student
                        </a>

                        <button class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Generate Admit Card
                        </button>

                        <button class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l7-3 7 3z"></path>
                            </svg>
                            Print Profile
                        </button>
                    </div>
                </div>

                <!-- Recent Activity / Timeline -->
                <div class="bg-gray-50 px-6 py-8 mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Recent Activity</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm text-gray-900">Student record was created</p>
                                <p class="text-xs text-gray-500">{{ $student->created_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        @if($student->updated_at != $student->created_at)
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm text-gray-900">Student record was last updated</p>
                                <p class="text-xs text-gray-500">{{ $student->updated_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>