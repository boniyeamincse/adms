<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\AdmitCard;
use App\Models\SectionModel;
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
     * Show the multi-step exam creation wizard - Step 1: Academic Year Selection
     */
    public function createWizard(): View
    {
        $academicYears = range(date('Y'), date('Y') - 10);
        return view('exams.wizard.step1-academic-year', compact('academicYears'));
    }

    /**
     * Process Step 1: Store academic year and proceed to step 2
     */
    public function wizardStep1(Request $request): RedirectResponse
    {
        $request->validate([
            'academic_year' => 'required|integer|min:2000|max:' . (date('Y') + 1)
        ]);

        session(['exam_creation' => [
            'academic_year' => $request->academic_year
        ]]);

        return redirect()->route('exams.wizard.step2');
    }

    /**
     * Show Step 2: Exam Details (Name and Type)
     */
    public function wizardStep2(): View
    {
        if (!session('exam_creation.academic_year')) {
            return redirect()->route('exams.wizard.step1');
        }

        return view('exams.wizard.step2-exam-details');
    }

    /**
     * Process Step 2: Store exam details and proceed to step 3
     */
    public function wizardStep3(Request $request): RedirectResponse
    {
        $request->validate([
            'exam_name' => 'required|string|max:255',
            'exam_type' => 'required|in:1st,2nd,3rd,custom'
        ]);

        $examData = session('exam_creation', []);
        $examData['exam_name'] = $request->exam_name;
        $examData['exam_type'] = $request->exam_type;

        session(['exam_creation' => $examData]);

        return redirect()->route('exams.wizard.step3');
    }

    /**
     * Show Step 3: Class and Subject Selection
     */
    public function wizardStep3View(): View
    {
        if (!session('exam_creation.exam_name')) {
            return redirect()->route('exams.wizard.step1');
        }

        $classes = SchoolClass::where('academic_year', session('exam_creation.academic_year'))
            ->orderBy('class_name')
            ->get();

        return view('exams.wizard.step3-class-subjects', compact('classes'));
    }

    /**
     * Process Step 3: Store class and subjects, proceed to final step
     */
    public function wizardStep4(Request $request): RedirectResponse
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_ids' => 'required|array|min:1',
            'subject_ids.*' => 'exists:subjects,id'
        ]);

        $examData = session('exam_creation', []);
        $examData['class_id'] = $request->class_id;
        $examData['subject_ids'] = $request->subject_ids;

        session(['exam_creation' => $examData]);

        return redirect()->route('exams.wizard.step4');
    }

    /**
     * Show Step 4: Date Selection and Review
     */
    public function wizardStep4View(): View
    {
        if (!session('exam_creation.class_id')) {
            return redirect()->route('exams.wizard.step1');
        }

        $examData = session('exam_creation');
        $selectedClass = SchoolClass::find($examData['class_id']);
        $selectedSubjects = Subject::whereIn('id', $examData['subject_ids'])->get();

        return view('exams.wizard.step4-dates', compact('examData', 'selectedClass', 'selectedSubjects'));
    }

    /**
     * Process final step: Create the exam
     */
    public function wizardComplete(Request $request): RedirectResponse
    {
        $request->validate([
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date'
        ]);

        $examData = session('exam_creation', []);

        // Create the exam
        $exam = Exam::create([
            'exam_name' => $examData['exam_name'],
            'exam_type' => $examData['exam_type'],
            'class_id' => $examData['class_id'],
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // Attach subjects
        $exam->subjects()->attach($examData['subject_ids']);

        // Clear session data
        session()->forget('exam_creation');

        return redirect()->route('exams.show', $exam)
            ->with('success', 'Exam created successfully!');
    }

    /**
     * Cancel wizard and clear session data
     */
    public function wizardCancel(): RedirectResponse
    {
        session()->forget('exam_creation');
        return redirect()->route('exams.index');
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