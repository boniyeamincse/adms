<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Student: ') . $student->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('students.show', $student) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    View Details
                </a>
                <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Students
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-gray-50 border-b">
                    <p class="text-sm text-gray-600">Make changes to the student's information below.</p>
                </div>

                <form method="POST" action="{{ route('students.update', $student) }}" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Current Photo Display -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Photo</label>
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @if($student->photo)
                                    <img class="h-20 w-20 rounded-lg object-cover" src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}">
                                @else
                                    <div class="h-20 w-20 rounded-lg bg-gray-300 flex items-center justify-center">
                                        <span class="text-gray-600 text-sm">{{ substr($student->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Update Photo</label>
                                <input type="file" name="photo" id="photo"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                       accept="image/*">
                                @error('photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Upload a new photo to replace the current one (JPEG, PNG, max 2MB)</p>

                                @if($student->photo)
                                    <div class="mt-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="remove_photo" value="1">
                                            <span class="ml-2 text-sm text-gray-600">Remove current photo</span>
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $student->name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Roll Number -->
                        <div>
                            <label for="roll_no" class="block text-sm font-medium text-gray-700">Roll Number</label>
                            <input type="text" name="roll_no" id="roll_no" value="{{ old('roll_no', $student->roll_no) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('roll_no')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Class Selection -->
                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700">Class *</label>
                            <select name="class_id" id="class_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->class_name }} ({{ $class->academic_year }})
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Section Selection -->
                        <div>
                            <label for="section_id" class="block text-sm font-medium text-gray-700">Section *</label>
                            <select name="section_id" id="section_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="">Select Section</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ old('section_id', $student->section_id) == $section->id ? 'selected' : '' }}>
                                        {{ $section->section_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <input type="date" name="dob" id="dob" value="{{ old('dob', $student->dob ? $student->dob->format('Y-m-d') : '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   max="{{ date('Y-m-d') }}">
                            @error('dob')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                            <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="transferred" {{ old('status', $student->status) == 'transferred' ? 'selected' : '' }}>Transferred</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end mt-6 pt-6 border-t">
                        <a href="{{ route('students.index') }}" class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Class change handler to load sections dynamically
        document.getElementById('class_id').addEventListener('change', function() {
            const classId = this.value;
            const sectionSelect = document.getElementById('section_id');

            if (!classId) {
                sectionSelect.innerHTML = '<option value="">First select a class</option>';
                return;
            }

            // Fetch sections for selected class
            fetch(`/students/class/${classId}/sections`)
                .then(response => response.json())
                .then(data => {
                    const oldValue = sectionSelect.value;
                    sectionSelect.innerHTML = '<option value="">Select Section</option>';
                    data.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.id;
                        option.textContent = section.section_name;
                        if (oldValue == section.id) option.selected = true;
                        sectionSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading sections:', error);
                });
        });

        // Photo preview
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photo-preview');
                    preview.innerHTML = `<img src="${e.target.result}" class="h-20 w-20 object-cover rounded-lg">`;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>