@extends('layouts.dashboard')

@section('title', 'Create New Student - EduPortal')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Student</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Add a new student to the system</p>
            </div>
            <a href="{{ route('students.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to Students
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
       <div class="p-6 border-b border-gray-200 dark:border-gray-700">
           <p class="text-sm text-gray-600 dark:text-gray-400">Fill in the student's information below. All fields marked with * are required.</p>
       </div>

       <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
           @csrf

           <!-- Photo Upload -->
           <div>
               <label for="photo" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Student Photo</label>
               <div class="flex items-center space-x-4">
                   <div class="flex-shrink-0">
                       <div id="photo-preview" class="h-20 w-20 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                           <svg class="h-8 w-8 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                           </svg>
                       </div>
                   </div>
                   <div class="flex-1">
                       <input type="file" name="photo" id="photo"
                              class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-500 file:text-white hover:file:bg-blue-600 transition-colors duration-200"
                              accept="image/*">
                       @error('photo')
                           <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                       @enderror
                       <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload a clear photo of the student (JPEG, PNG, max 2MB)</p>
                   </div>
               </div>
           </div>

           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Roll Number -->
                        <div>
                            <label for="roll_no" class="block text-sm font-medium text-gray-700">Roll Number</label>
                            <input type="text" name="roll_no" id="roll_no" value="{{ old('roll_no') }}"
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
                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
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
                                <option value="">First select a class</option>
                            </select>
                            @error('section_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <input type="date" name="dob" id="dob" value="{{ old('dob') }}"
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
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="transferred" {{ old('status') == 'transferred' ? 'selected' : '' }}>Transferred</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-600">
                        <a href="{{ route('students.index') }}" class="mr-4 px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>Create Student
                        </button>
                    </div>
                </form>
    </div>

</div>
@endsection

@push('scripts')
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
                sectionSelect.innerHTML = '<option value="">Select Section</option>';
                data.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.section_name;
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

    // Update active nav on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateActiveNav();
    });

    function updateActiveNav() {
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            const link = item.querySelector('a');
            if (link && link.getAttribute('href') === '{{ route("students.create") }}') {
                item.classList.add('bg-blue-100', 'dark:bg-blue-900', 'text-blue-700', 'dark:text-blue-300');
            }
        });
    }
</script>
@endpush