@extends('layouts.dashboard')

@section('title', 'Create Exam - Step 1 of 4 - EduPortal')

@section('content')
<div class="space-y-6">

    <!-- Progress Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Exam</h1>
            <a href="{{ route('exams.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <i class="fas fa-times mr-1"></i>Cancel
            </a>
        </div>

        <!-- Progress Steps -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold">1</div>
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Academic Year</span>
            </div>
            <div class="flex items-center">
                <div class="w-8 h-0.5 bg-gray-300 dark:bg-gray-600"></div>
                <div class="ml-4 flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-full font-bold">2</div>
                    <span class="ml-3 text-sm font-medium text-gray-500 dark:text-gray-400">Exam Details</span>
                </div>
                <div class="w-8 h-0.5 bg-gray-300 dark:bg-gray-600"></div>
            </div>
            <div class="flex items-center">
                <div class="ml-4 flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-full font-bold">3</div>
                    <span class="ml-3 text-sm font-medium text-gray-500 dark:text-gray-400">Subjects</span>
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
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-4">Step 1 of 4: Choose the academic year for this exam</p>
    </div>

    <!-- Academic Year Selection Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Select Academic Year</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Choose the academic year this exam will be conducted in. This will filter the available classes and subjects.</p>
        </div>

        <form method="POST" action="{{ route('exams.wizard.step1.post') }}" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($academicYears as $year)
                <label class="relative">
                    <input type="radio"
                           name="academic_year"
                           value="{{ $year }}"
                           class="sr-only peer"
                           {{ $year == date('Y') ? 'checked' : '' }}
                           required>

                    <div class="w-full p-6 text-center border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-200">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $year }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            @if($year == date('Y'))
                                (Current Year)
                            @elseif($year < date('Y'))
                                (Previous)
                            @else
                                (Upcoming)
                            @endif
                        </div>
                    </div>
                </label>
                @endforeach
            </div>

            @error('academic_year')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror

            <!-- Navigation Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-600 mt-6">
                <form method="POST" action="{{ route('exams.wizard.cancel') }}">
                    @csrf
                    <button type="submit" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                </form>

                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    Next: Exam Details
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Update active nav on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateActiveNav();
    });

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