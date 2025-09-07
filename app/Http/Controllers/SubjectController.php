<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SubjectController extends Controller
{

    /**
     * Display a listing of the subjects.
     */
    public function index(): View
    {
        $subjects = Subject::with('schoolClass')
            ->orderBy('subject_name')
            ->paginate(15);

        $classes = SchoolClass::orderBy('academic_year', 'desc')
            ->orderBy('class_name')
            ->get();

        return view('subjects.index', compact('subjects', 'classes'));
    }

    /**
     * Show the form for creating a new subject.
     */
    public function create(): View
    {
        $classes = SchoolClass::orderBy('academic_year', 'desc')
            ->orderBy('class_name')
            ->get();

        return view('subjects.create', compact('classes'));
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
        ]);

        Subject::create($validated);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    /**
     * Display the specified subject.
      */
     public function show(Subject $subject): View
     {
         $subject->load(['schoolClass', 'exams']);

         return view('subjects.show', compact('subject'));
     }

    /**
     * Show the form for editing the specified subject.
     */
    public function edit(Subject $subject): View
    {
        $classes = SchoolClass::orderBy('academic_year', 'desc')
            ->orderBy('class_name')
            ->get();

        return view('subjects.edit', compact('subject', 'classes'));
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
        ]);

        $subject->update($validated);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy(Subject $subject): RedirectResponse
    {
        // Could add checks for subjects being used in exams

        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}