<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Seating Plan for: :exam', ['exam' => $exam->exam_name]) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <!-- Exam Information Card -->
                <div class="p-6 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <h3 class="text-lg font-semibold mb-2">{{ $exam->exam_name }}</h3>
                            <p class="text-sm opacity-90">{{ $exam->schoolClass->class_name }}</p>
                        </div>
                        <div class="text-center">
                            <h4 class="text-lg font-semibold mb-2">Exam Date</h4>
                            <p class="text-sm opacity-90">{{ $exam->start_date->format('d/m/Y') }}</p>
                            <p class="text-sm opacity-90">{{ $exam->start_date->format('h:i A') }} - {{ $exam->end_date->format('h:i A') }}</p>
                        </div>
                        <div class="text-center">
                            <h4 class="text-lg font-semibold mb-2">Students</h4>
                            <p class="text-2xl font-bold">{{ $studentCount }}</p>
                        </div>
                    </div>
                </div>

                <!-- Seating Plan Creation Form -->
                <div class="p-6">
                    <form method="POST" action="{{ route('exam.seating.generate', $exam) }}" class="space-y-6">
                        @csrf

                        <!-- Hall Selection -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800">Step 1: Select Halls</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($halls as $hall)
                                    <label class="flex items-center space-x-3 p-4 bg-white border rounded-lg hover:shadow-md transition-shadow">
                                        <input type="checkbox" name="hall_ids[]" value="{{ $hall->id }}"
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 hall-checkbox">
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-900">{{ $hall->hall_name }}</div>
                                            <div class="text-sm text-gray-500">Capacity: {{ $hall->capacity }}</div>
                                            <div class="text-sm text-gray-500">Layout: {{ $hall->layout_rows }}×{{ $hall->layout_cols }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <button type="button" id="selectAllHalls" class="text-sm text-blue-600 hover:text-blue-800">
                                    Select All Halls
                                </button>
                            </div>
                        </div>

                        <!-- Seating Algorithm Selection -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800">Step 2: Choose Algorithm</h3>

                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input id="random" name="algorithm" type="radio" value="random" checked class="text-blue-600 focus:ring-blue-500">
                                    <label for="random" class="ml-3 text-sm font-medium text-gray-700">
                                        <div class="font-semibold">Random Seating</div>
                                        <div class="text-gray-500">Distribute students randomly across available seats</div>
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input id="section_wise" name="algorithm" type="radio" value="section_wise" class="text-blue-600 focus:ring-blue-500">
                                    <label for="section_wise" class="ml-3 text-sm font-medium text-gray-700">
                                        <div class="font-semibold">Section-wise Seating</div>
                                        <div class="text-gray-500">Group students from the same section together</div>
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input id="mixed" name="algorithm" type="radio" value="mixed" class="text-blue-600 focus:ring-blue-500">
                                    <label for="mixed" class="ml-3 text-sm font-medium text-gray-700">
                                        <div class="font-semibold">Mixed Distribution</div>
                                        <div class="text-gray-500">Balanced distribution considering classes and sections</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Constraint Settings -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800">Step 3: Seating Constraints</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Minimum Distance -->
                                <div class="space-y-2">
                                    <label for="min_distance" class="block text-sm font-medium text-gray-700">
                                        Minimum Distance Between Seats
                                    </label>
                                    <select id="min_distance" name="min_distance" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="1">1 seat (default)</option>
                                        <option value="2">2 seats apart</option>
                                        <option value="3">3 seats apart</option>
                                        <option value="4">4 seats apart</option>
                                        <option value="5">5 seats apart</option>
                                    </select>
                                    <p class="text-sm text-gray-500">Ensure students are not seated too close together</p>
                                </div>

                                <!-- Section Separation -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Section Separation
                                    </label>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="section_separation" value="1"
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm">Keep students from same section at least 2 seats apart</span>
                                        </label>
                                    </div>
                                    <p class="text-sm text-gray-500">Prevent cheating by separating students from same sections</p>
                                </div>
                            </div>

                            <!-- Additional Settings -->
                            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <h4 class="font-medium text-blue-800 mb-2">ℹ️ Additional Settings</h4>
                                <p class="text-sm text-blue-600">
                                    Handicap seats and emergency exits are automatically respected based on hall configuration.
                                    Students with special needs will be assigned appropriate seats when the system processes assignments.
                                </p>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4 pt-6 border-t">
                            <a href="{{ route('seating-plans.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Generate Seating Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Select all halls functionality
        document.getElementById('selectAllHalls').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.hall-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);

            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const selectedHalls = document.querySelectorAll('.hall-checkbox:checked');
            const selectedAlgorithm = document.querySelector('input[name="algorithm"]:checked');

            if (selectedHalls.length === 0) {
                e.preventDefault();
                alert('Please select at least one examination hall.');
                return;
            }

            if (!selectedAlgorithm) {
                e.preventDefault();
                alert('Please select a seating algorithm.');
                return;
            }

            // Show loading state
            const submitButton = document.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Generating... This may take a few moments.';
        });
    </script>
</x-app-layout>