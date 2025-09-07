<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\SectionModel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:superadmin'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the sections.
     */
    public function index(): View
    {
        $sections = SectionModel::with(['schoolClass', 'students'])
            ->withCount('students')
            ->orderBy('section_name')
            ->paginate(15);

        $classes = SchoolClass::orderBy('academic_year', 'desc')
            ->orderBy('class_name')
            ->get();

        return view('sections.index', compact('sections', 'classes'));
    }

    /**
     * Show the form for creating a new section.
     */
    public function create(): View
    {
        $classes = SchoolClass::orderBy('academic_year', 'desc')
            ->orderBy('class_name')
            ->get();

        return view('sections.create', compact('classes'));
    }

    /**
     * Store a newly created section in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'section_name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
        ]);

        SectionModel::create($validated);

        return redirect()->route('sections.index')
            ->with('success', 'Section created successfully.');
    }

    /**
     * Display the specified section.
     */
    public function show(SectionModel $section): View
    {
        $section->load(['schoolClass', 'students.admitCards', 'students.fees']);

        return view('sections.show', compact('section'));
    }

    /**
     * Show the form for editing the specified section.
     */
    public function edit(SectionModel $section): View
    {
        $classes = SchoolClass::orderBy('academic_year', 'desc')
            ->orderBy('class_name')
            ->get();

        return view('sections.edit', compact('section', 'classes'));
    }

    /**
     * Update the specified section in storage.
     */
    public function update(Request $request, SectionModel $section): RedirectResponse
    {
        $validated = $request->validate([
            'section_name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
        ]);

        $section->update($validated);

        return redirect()->route('sections.index')
            ->with('success', 'Section updated successfully.');
    }

    /**
     * Remove the specified section from storage.
     */
    public function destroy(SectionModel $section): RedirectResponse
    {
        // Check if section has students
        if ($section->students()->count() > 0) {
            return redirect()->route('sections.index')
                ->with('error', 'Cannot delete section that has students assigned.');
        }

        $section->delete();

        return redirect()->route('sections.index')
            ->with('success', 'Section deleted successfully.');
    }
}