<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\SectionModel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClassController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:superadmin'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the classes.
     */
    public function index(): View
    {
        $classes = SchoolClass::withCount(['sections', 'students', 'subjects'])
            ->orderBy('academic_year', 'desc')
            ->orderBy('class_name')
            ->paginate(15);

        return view('classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new class.
     */
    public function create(): View
    {
        return view('classes.create');
    }

    /**
     * Store a newly created class in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_name' => 'required|string|max:255',
            'academic_year' => 'required|integer|min:2000|max:' . (date('Y') + 10),
        ]);

        SchoolClass::create($validated);

        return redirect()->route('classes.index')
            ->with('success', 'Class created successfully.');
    }

    /**
     * Display the specified class.
     */
    public function show(SchoolClass $class): View
    {
        $class->load(['sections.students', 'subjects', 'students', 'exams']);

        return view('classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit(SchoolClass $class): View
    {
        return view('classes.edit', compact('class'));
    }

    /**
     * Update the specified class in storage.
     */
    public function update(Request $request, SchoolClass $class): RedirectResponse
    {
        $validated = $request->validate([
            'class_name' => 'required|string|max:255',
            'academic_year' => 'required|integer|min:2000|max:' . (date('Y') + 10),
        ]);

        $class->update($validated);

        return redirect()->route('classes.index')
            ->with('success', 'Class updated successfully.');
    }

    /**
     * Remove the specified class from storage.
     */
    public function destroy(SchoolClass $class): RedirectResponse
    {
        // Check if class has dependencies
        if ($class->students()->count() > 0) {
            return redirect()->route('classes.index')
                ->with('error', 'Cannot delete class that has students assigned.');
        }

        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Class deleted successfully.');
    }
}