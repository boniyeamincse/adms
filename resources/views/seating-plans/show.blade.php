<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Seating Plan: :exam', ['exam' => $exam->exam_name]) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <!-- Action Bar -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ $exam->exam_name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $exam->schoolClass->class_name }} • {{ $exam->start_date->format('M d, Y g:i A') }} -
                                {{ $exam->end_date->format('g:i A') }}
                            </p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('exam.seating.regenerate', $exam) }}" class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-sync mr-2"></i>
                                Regenerate
                            </a>
                            <a href="{{ route('exam.seating.export', $exam) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-download mr-2"></i>
                                Export PDF
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Analytics Cards -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-school text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Halls</p>
                                    <p class="text-lg font-bold text-blue-900 dark:text-blue-100">{{ count($hallSeatings) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-chair text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-green-700 dark:text-green-300">Assigned Seats</p>
                                    <p class="text-lg font-bold text-green-900 dark:text-green-100">
                                        {{ collect($hallSeatings)->sum(function($hall, $halls) { return array_reduce(array_slice(array_slice($hall, 0, 1)[array_key_first($hall)], 0, 1)[array_key_first($hall)] ?? [], fn($count, $row) => $count + collect($row)->filter()->count(), 0); }) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-purple-700 dark:text-purple-300">Students</p>
                                    <p class="text-lg font-bold text-purple-900 dark:text-purple-100">{{ $exam->admitCards->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-yellow-700 dark:text-yellow-300">Algorithm</p>
                                    <p class="text-sm font-bold text-yellow-900 dark:text-yellow-100">Mixed Distribution</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seating Visualization -->
                    @if(count($hallSeatings) > 0)
                        @foreach($hallSeatings as $hallName => $layout)
                            <div class="mb-8">
                                <div class="text-center mb-4">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $hallName }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Seating Arrangement (Row × Column)</p>
                                </div>

                                <div class="overflow-x-auto">
                                    <div class="inline-block min-w-full align-middle">
                                        <table class="seating-table min-w-full">
                                            <tbody>
                                                @for($row = 1; $row <= count($layout); $row++)
                                                    <tr>
                                                        <td class="px-2 py-1 text-sm font-medium text-gray-500 dark:text-gray-400">{{ $row }}</td>
                                                        @for($col = 1; $col <= count($layout[$row]); $col++)
                                                            @php $student = $layout[$row][$col] ?? null @endphp
                                                            <td class="seat-cell {{ $student ? 'occupied' : 'empty' }}">
                                                                @if($student)
                                                                    <div class="student-info">
                                                                        <div class="font-medium text-xs">{{ Str::limit($student['student_name'], 15) }}</div>
                                                                        <div class="text-xs opacity-80">{{ $student['roll_no'] }}</div>
                                                                    </div>
                                                                @else
                                                                    <div class="empty-seat">Empty</div>
                                                                @endif
                                                            </td>
                                                        @endfor
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    @else
                        <!-- No Seating Plan Generated -->
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-chair text-gray-400 dark:text-gray-600 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Seating Plan Generated</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">Create a seating plan for this exam first.</p>
                            <a href="{{ route('exam.seating.create', $exam) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Generate Seating Plan
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <style>
        .seating-table {
            border-collapse: collapse;
            background: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
        }

        .seat-cell {
            width: 90px;
            height: 65px;
            border: 1px solid #dee2e6;
            text-align: center;
            vertical-align: top;
            padding: 4px;
            background: white;
        }

        .seat-cell.occupied {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .seat-cell.empty {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #6c757d;
        }

        .student-info {
            font-size: 11px;
            line-height: 1.2;
        }

        .empty-seat {
            font-size: 10px;
            color: #adb5bd;
            padding-top: 20px;
        }
    </style>
</x-app-layout>