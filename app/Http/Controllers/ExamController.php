<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:superadmin,teacher'])->except(['show']);
    }

    /**
     * Display a listing of the exams.
     */
    public function index(): View
    {
        $exams = Exam::with(['schoolClass', 'subjects', 'admitCards.student'])
            ->withCount('admitCards')
            ->orderBy('start_date', 'desc')
            ->paginate(15);

        $classes = SchoolClass::withCount('students')
            ->orderBy('academic_year', 'desc')
            ->orderBy('class_name')
            ->get();

        return view('exams.index', compact('exams', 'classes'));
    }

    /**
     * Show the form for creating a new exam.
     */
    public function create(): View
    {
        $classes = SchoolClass::orderBy('academic_year', 'desc')
            ->orderBy('class_name')
            ->get();

        return view('exams.create', compact('classes'));
    }

    /**
     * Store a newly created exam in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exam_name' => 'required|string|max:255',
            'exam_type' => 'required|in:1st,2nd,3rd,custom',
            'class_id' => 'required|exists:classes,id',
            'subject_ids' => 'required|array|min:1',
            'subject_ids.*' => 'exists:subjects,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
        ]);

        $exam = Exam::create([
            'exam_name' => $validated['exam_name'],
            'exam_type' => $validated['exam_type'],
            'class_id' => $validated['class_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        // Attach subjects to exam
        $exam->subjects()->attach($validated['subject_ids']);

        return redirect()->route('exams.index')
            ->with('success', 'Exam created successfully.');
    }

    /**
     * Display the specified exam.
     */
    public function show(Exam $exam): View
    {
        $exam->load(['schoolClass', 'subjects', 'admitCards.student.section']);

        return view('exams.show', compact('exam'));
    }

    /**
     * Show the form for editing the specified exam.
     */
    public function edit(Exam $exam): View
    {
        $classes = SchoolClass::orderBy('academic_year', 'desc')
            ->orderBy('class_name')
            ->get();

        $subjects = Subject::where('class_id', $exam->class_id)
            ->orderBy('subject_name')
            ->get();

        return view('exams.edit', compact('exam', 'classes', 'subjects'));
    }

    /**
     * Update the specified exam in storage.
     */
    public function update(Request $request, Exam $exam): RedirectResponse
    {
        $validated = $request->validate([
            'exam_name' => 'required|string|max:255',
            'exam_type' => 'required|in:1st,2nd,3rd,custom',
            'class_id' => 'required|exists:classes,id',
            'subject_ids' => 'required|array|min:1',
            'subject_ids.*' => 'exists:subjects,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $exam->update([
            'exam_name' => $validated['exam_name'],
            'exam_type' => $validated['exam_type'],
            'class_id' => $validated['class_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        // Sync subjects with the exam
        $exam->subjects()->sync($validated['subject_ids']);

        return redirect()->route('exams.index')
            ->with('success', 'Exam updated successfully.');
    }

    /**
     * Remove the specified exam from storage.
     */
    public function destroy(Exam $exam): RedirectResponse
    {
        // Check if exam has admit cards
        if ($exam->admitCards()->count() > 0) {
            return redirect()->route('exams.index')
                ->with('error', 'Cannot delete exam that has admit cards generated.');
        }

        $exam->subjects()->detach();
        $exam->delete();

        return redirect()->route('exams.index')
            ->with('success', 'Exam deleted successfully.');
    }

    /**
     * Get subjects for a specific class (AJAX endpoint).
     */
    public function getSubjects(Request $request, $classId): JsonResponse
    {
        $subjects = Subject::where('class_id', $classId)
            ->orderBy('subject_name')
            ->get(['id', 'subject_name']);

        return response()->json($subjects);
    }

    /**
     * Generate admit cards for exam.
     */
    public function generateAdmitCards(Exam $exam): RedirectResponse
    {
        $students = $exam->schoolClass->students()->active()->get();

        foreach ($students as $student) {
            $admitCard = $exam->admitCards()->firstOrCreate([
                'student_id' => $student->id,
            ]);

            $admitCard->generateSeatNumber();
        }

        return redirect()->back()
            ->with('success', 'Admit cards generated successfully.');
    }
}