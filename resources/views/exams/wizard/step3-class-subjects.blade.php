@extends('layouts.dashboard')

@section('title', 'Create Exam - Step 3 of 4 - EduPortal')

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
                    <div class="flex items-center justify-center w-10 h-10 bg-green-600 text-white rounded-full font-bold">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ session('exam_creation.exam_name') }}</span>
                </div>
                <div class="w-8 h-0.5 bg-green-600"></div>
            </div>
            <div class="flex items-center">
                <div class="ml-4 flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold">3</div>
                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Subjects</span>
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
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-4">Step 3 of 4: Select class and subjects for the exam</p>
    </div>

    <!-- Class and Subject Selection Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Choose Class and Subjects</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Select the class and subjects that will be included in this exam.</p>
        </div>

        <form method="POST" action="{{ route('exams.wizard.step3.post') }}" class="p-6 space-y-6">
            @csrf

            <!-- Class Selection -->
            <div>
                <label for="class_id" class="block text-sm font-medium text-gray-900 dark:text-white mb-4">
                    Select Class *
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($classes as $class)
                    <label class="relative">
                        <input type="radio"
                               name="class_id"
                               value="{{ $class->id }}"
                               class="sr-only peer"
                               {{ old('class_id') == $class->id ? 'checked' : '' }}
                               required>
                        <div class="w-full p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-school text-blue-600 dark:text-blue-400 text-lg"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $class->class_name }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Academic Year {{ $class->academic_year }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $class->students()->count() }} students</div>
                                </div>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>

                @error('class_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subjects Selection Area -->
            <div id="subjects-selection" class="hidden space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Select Subjects for the Exam *
                    </label>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Search for subjects by name or code, then click the add button to include them in this exam.</p>

                    <!-- Search Bar -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text"
                                   id="subject-search"
                                   class="w-full px-4 py-2 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                                   placeholder="Search subjects by name or code...">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-search text-gray-400 dark:text-gray-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Subjects Counter -->
                    <div class="mb-4 flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Selected: <span id="selected-count" class="font-medium">0</span> subjects
                        </span>
                        <button type="button" id="clear-all-btn" class="text-xs text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 hidden">
                            Clear All
                        </button>
                    </div>

                    <!-- Subjects Container -->
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700/50 max-h-80 overflow-y-auto">
                        <div id="subjects-container" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            {{-- Subjects will be loaded via AJAX --}}
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                <i class="fas fa-spinner fa-spin text-lg mb-2"></i>
                                <p class="text-sm">Loading subjects...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Subjects Display -->
                    <div id="selected-subjects" class="mt-4 hidden">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Selected Subjects:</h4>
                        <div id="selected-subjects-list" class="flex flex-wrap gap-2">
                            {{-- Selected subjects will be added here --}}
                        </div>
                        </div>
                    </div>

                    @error('subject_ids')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-600 mt-6">
                <a href="{{ route('exams.wizard.step2') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>

                <button type="submit" id="next-btn" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Next: Set Dates
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Store selected subjects and all subjects data
    let selectedSubjects = [];
    let allSubjects = [];

    // Class selection handler
    document.addEventListener('DOMContentLoaded', function() {
        const classInputs = document.querySelectorAll('input[name="class_id"]');
        classInputs.forEach(input => {
            input.addEventListener('change', function() {
                const classId = this.value;
                loadSubjectsForClass(classId);
                resetSubjectSelection();
            });
        });

        // Search functionality
        const searchInput = document.getElementById('subject-search');
        searchInput.addEventListener('input', filterSubjects);

        // Clear all button
        document.getElementById('clear-all-btn').addEventListener('click', clearAllSubjects);

        updateActiveNav();
    });

    function loadSubjectsForClass(classId) {
        const container = document.getElementById('subjects-container');
        const selectionArea = document.getElementById('subjects-selection');

        container.innerHTML = `
            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                <i class="fas fa-spinner fa-spin text-lg mb-2"></i>
                <p class="text-sm">Loading subjects...</p>
            </div>
        `;

        fetch(`/exams/class/${classId}/subjects`)
            .then(response => response.json())
            .then(data => {
                allSubjects = data;
                displaySubjects(data);
                selectionArea.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error loading subjects:', error);
                container.innerHTML = '<p class="text-sm text-red-600 dark:text-red-400">Error loading subjects. Please try again.</p>';
                selectionArea.classList.remove('hidden');
            });
    }

    function displaySubjects(subjects) {
        const container = document.getElementById('subjects-container');

        if (subjects.length > 0) {
            container.className = 'grid grid-cols-1 md:grid-cols-2 gap-3';
            container.innerHTML = '';

            subjects.forEach(subject => {
                const isSelected = selectedSubjects.includes(subject.id.toString());
                const subjectItem = `
                    <div class="subject-card bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-3 hover:shadow-md transition-all duration-200 ${isSelected ? 'ring-2 ring-blue-500' : ''}" data-subject-id="${subject.id}">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">${subject.subject_name}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">${subject.subject_code || 'N/A'}</div>
                            </div>
                            <button type="button"
                                    data-subject-id="${subject.id}"
                                    data-subject-name="${subject.subject_name}"
                                    data-subject-code="${subject.subject_code}"
                                    class="subject-add-btn flex-shrink-0 ml-2 px-3 py-1 text-xs font-medium rounded-full transition-colors duration-200 ${isSelected ? 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200 hover:bg-red-200' : 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 hover:bg-blue-200'}">
                                <i class="fas fa-${isSelected ? 'minus' : 'plus'} mr-1"></i>
                                ${isSelected ? 'Remove' : 'Add'}
                            </button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', subjectItem);
            });

            // Add event listeners to the new buttons
            addSubjectButtonListeners();

        } else {
            container.className = 'flex items-center justify-center';
            container.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400">No subjects found for this class.</p>';
        }
    }

    function addSubjectButtonListeners() {
        document.querySelectorAll('.subject-add-btn').forEach(button => {
            button.addEventListener('click', function() {
                const subjectId = this.dataset.subjectId;
                const subjectName = this.dataset.subjectName;
                const subjectCode = this.dataset.subjectCode;

                if (selectedSubjects.includes(subjectId)) {
                    removeSubject(subjectId);
                } else {
                    addSubject(subjectId, subjectName, subjectCode);
                }
            });
        });
    }

    function addSubject(subjectId, subjectName, subjectCode) {
        if (!selectedSubjects.includes(subjectId)) {
            selectedSubjects.push(subjectId);

            // Create hidden input for form submission
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'subject_ids[]';
            hiddenInput.value = subjectId;
            hiddenInput.id = `subject-${subjectId}`;
            document.querySelector('form').appendChild(hiddenInput);

            // Update UI
            updateSubjectCard(subjectId, true);
            addToSelectedList(subjectId, subjectName, subjectCode);
            updateSelectedCount();
        }
    }

    function removeSubject(subjectId) {
        const index = selectedSubjects.indexOf(subjectId);
        if (index > -1) {
            selectedSubjects.splice(index, 1);

            // Remove hidden input
            const hiddenInput = document.getElementById(`subject-${subjectId}`);
            if (hiddenInput) hiddenInput.remove();

            // Update UI
            updateSubjectCard(subjectId, false);
            removeFromSelectedList(subjectId);
            updateSelectedCount();
        }
    }

    function updateSubjectCard(subjectId, isSelected) {
        const card = document.querySelector(`[data-subject-id="${subjectId}"]`);
        if (card) {
            const button = card.querySelector('.subject-add-btn');

            if (isSelected) {
                card.classList.add('ring-2', 'ring-blue-500');
                button.innerHTML = '<i class="fas fa-minus mr-1"></i>Remove';
                button.className = 'subject-add-btn flex-shrink-0 ml-2 px-3 py-1 text-xs font-medium rounded-full transition-colors duration-200 bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200 hover:bg-red-200';
            } else {
                card.classList.remove('ring-2', 'ring-blue-500');
                button.innerHTML = '<i class="fas fa-plus mr-1"></i>Add';
                button.className = 'subject-add-btn flex-shrink-0 ml-2 px-3 py-1 text-xs font-medium rounded-full transition-colors duration-200 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 hover:bg-blue-200';
            }
        }
    }

    function addToSelectedList(subjectId, subjectName, subjectCode) {
        const selectedList = document.getElementById('selected-subjects-list');
        const selectedContainer = document.getElementById('selected-subjects');

        const selectedItem = `
            <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200" id="selected-${subjectId}">
                ${subjectName}(${subjectCode || 'N/A'})
                <button type="button" class="ml-1 text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-100" onclick="removeSubject('${subjectId}')">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </span>
        `;

        selectedList.insertAdjacentHTML('beforeend', selectedItem);
        selectedContainer.classList.remove('hidden');
    }

    function removeFromSelectedList(subjectId) {
        const selectedItem = document.getElementById(`selected-${subjectId}`);
        if (selectedItem) selectedItem.remove();

        const selectedList = document.getElementById('selected-subjects-list');
        if (selectedList.children.length === 0) {
            document.getElementById('selected-subjects').classList.add('hidden');
        }
    }

    function updateSelectedCount() {
        const count = selectedSubjects.length;
        document.getElementById('selected-count').textContent = count;

        const clearBtn = document.getElementById('clear-all-btn');
        if (count > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        updateNextButton();
    }

    function clearAllSubjects() {
        selectedSubjects.forEach(subjectId => {
            removeSubject(subjectId);
        });
    }

    function filterSubjects() {
        const searchTerm = document.getElementById('subject-search').value.toLowerCase();

        const filteredSubjects = allSubjects.filter(subject =>
            subject.subject_name.toLowerCase().includes(searchTerm) ||
            (subject.subject_code && subject.subject_code.toLowerCase().includes(searchTerm))
        );

        displaySubjects(filteredSubjects);
    }

    function resetSubjectSelection() {
        selectedSubjects = [];
        allSubjects = [];
        document.getElementById('selected-subjects').classList.add('hidden');
        document.getElementById('selected-subjects-list').innerHTML = '';

        // Clear form inputs
        document.querySelectorAll('input[name="subject_ids[]"]').forEach(input => input.remove());
    }

    function updateNextButton() {
        const nextBtn = document.getElementById('next-btn');
        const classSelected = document.querySelector('input[name="class_id"]:checked') !== null;
        const subjectsSelected = selectedSubjects.length > 0;

        nextBtn.disabled = !classSelected || !subjectsSelected;
        nextBtn.textContent = subjectsSelected
            ? `Next: Set Dates (${selectedSubjects.length} subjects)`
            : 'Select Subjects';
    }

    function updateActiveNav() {
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            const link = item.querySelector('a');
            if (link && link.href.includes('exams')) {
                item.classList.add('bg-blue-100', 'dark:bg-blue-900', 'text-blue-700', 'dark:text-blue-300');
            }
        });
    }
</script>
@endpush