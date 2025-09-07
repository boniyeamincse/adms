<?php

namespace App\Http\Controllers;

use App\Models\AdmitCard;
use App\Models\Exam;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class AdmitCardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:superadmin,teacher,accountant'])->except(['show', 'download']);
        $this->middleware(['auth', 'role:superadmin,teacher'])->only(['destroy']);
    }

    /**
     * Display a listing of the admit cards.
     */
    public function index(Request $request): View
    {
        $query = AdmitCard::with(['student.schoolClass', 'student.section', 'exam.schoolClass', 'exam.subjects']);

        // Filter by exam if provided
        if ($request->has('exam_id') && $request->exam_id) {
            $query->where('exam_id', $request->exam_id);
        }

        // Filter by class if provided
        if ($request->has('class_id') && $request->class_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        $admitCards = $query->orderBy('generated_at', 'desc')->paginate(15);

        $exams = Exam::with('schoolClass')->orderBy('start_date', 'desc')->get();
        $classes = \App\Models\SchoolClass::withCount('students')->get();

        return view('admit-cards.index', compact('admitCards', 'exams', 'classes'));
    }

    /**
     * Display the specified admit card.
     */
    public function show(AdmitCard $admitCard): View
    {
        $admitCard->load(['student.schoolClass', 'student.section', 'exam.schoolClass', 'exam.subjects']);

        return view('admit-cards.show', compact('admitCard'));
    }

    /**
     * Show the form for creating a new admit card.
     */
    public function create(): View
    {
        $exams = Exam::where('end_date', '>', now())
            ->with(['schoolClass', 'subjects'])
            ->get();

        $students = Student::active()
            ->with(['schoolClass', 'section'])
            ->orderBy('name')
            ->get();

        return view('admit-cards.create', compact('exams', 'students'));
    }

    /**
     * Generate a new admit card for a student-exam combination.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_id' => 'required|exists:exams,id',
        ]);

        $admitCard = AdmitCard::firstOrCreate($validated);

        // Generate seat number if not already generated
        $admitCard->generateSeatNumber();

        return redirect()->route('admit-cards.show', $admitCard)
            ->with('success', 'Admit card generated successfully.');
    }

    /**
     * Download the admit card as PDF.
     */
    public function download(AdmitCard $admitCard): Response
    {
        // Check if admit card is valid
        if (!$admitCard->isValid()) {
            abort(403, 'This admit card is no longer valid.');
        }

        $data = [
            'admit_card' => $admitCard->load(['student.schoolClass', 'student.section', 'exam.subjects']),
            'exam_subjects' => $admitCard->exam->subjects,
            'school_info' => [
                'name' => env('SCHOOL_NAME', 'ABC School'),
                'address' => env('SCHOOL_ADDRESS', 'School Address'),
                'phone' => env('SCHOOL_PHONE', 'School Phone'),
                'logo' => public_path('images/school-logo.png'),
                'website' => env('SCHOOL_WEBSITE', url('/'))
            ]
        ];

        $pdf = Pdf::loadView('admit-cards.pdf', $data);

        $filename = 'admit_card_' . $admitCard->student->name . '_' . $admitCard->exam->exam_name . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate admit cards for all eligible students in an exam.
     */
    public function bulkGenerate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
        ]);

        $exam = Exam::find($validated['exam_id']);
        $students = $exam->schoolClass->students()
            ->where('status', 'active')
            ->get();

        $generated = 0;
        foreach ($students as $student) {
            $admitCard = AdmitCard::firstOrCreate([
                'student_id' => $student->id,
                'exam_id' => $exam->id,
            ]);

            $admitCard->generateSeatNumber();
            $generated++;
        }

        return redirect()->back()
            ->with('success', "$generated admit cards generated successfully.");
    }

    /**
     * Remove the specified admit card from storage.
     */
    public function destroy(AdmitCard $admitCard): RedirectResponse
    {
        $admitCard->delete();

        return redirect()->route('admit-cards.index')
            ->with('success', 'Admit card deleted successfully.');
    }

    /**
     * Get students for a specific exam (AJAX endpoint).
     */
    public function getStudents(Request $request)
    {
        $examId = $request->exam_id;

        $students = Student::whereHas('schoolClass', function ($query) use ($examId) {
            $query->whereHas('exams', function ($q) use ($examId) {
                $q->where('exams.id', $examId);
            });
        })->active()
          ->with(['schoolClass', 'section'])
          ->get();

        return response()->json($students);
    }

    /**
     * Print multiple admit cards.
     */
    public function bulkPrint(Request $request): Response
    {
        $validated = $request->validate([
            'admit_card_ids' => 'required|array',
            'admit_card_ids.*' => 'exists:admit_cards,id',
        ]);

        $admitCards = AdmitCard::whereIn('id', $validated['admit_card_ids'])
            ->with(['student.schoolClass', 'student.section', 'exam.subjects'])
            ->get();

        $data = [
            'admit_cards' => $admitCards,
            'school_info' => [
                'name' => env('SCHOOL_NAME', 'ABC School'),
                'address' => env('SCHOOL_ADDRESS', 'School Address'),
                'phone' => env('SCHOOL_PHONE', 'School Phone'),
                'logo' => public_path('images/school-logo.png'),
                'website' => env('SCHOOL_WEBSITE', url('/'))
            ]
        ];

        $pdf = Pdf::loadView('admit-cards.bulk-pdf', $data)->setPaper('a4', 'portrait');

        return $pdf->download('bulk_admit_cards.pdf');
    }
}