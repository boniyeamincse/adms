<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class FeeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:superadmin,accountant'])->except(['show']);
    }

    /**
     * Display a listing of the fees.
     */
    public function index(Request $request): View
    {
        $query = Fee::with(['student.schoolClass', 'student.section']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by class
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        // Filter by month
        if ($request->has('month') && $request->month) {
            $query->ForMonth($request->month, $request->year);
        }

        $fees = $query->orderBy('created_at', 'desc')->paginate(15);

        $classes = SchoolClass::orderBy('academic_year', 'desc')->get();

        return view('fees.index', compact('fees', 'classes'));
    }

    /**
     * Show the form for creating a new fee.
     */
    public function create(): View
    {
        $classes = SchoolClass::withCount('students')->get();
        $students = Student::active()->with('schoolClass')->get();

        return view('fees.create', compact('classes', 'students'));
    }

    /**
     * Store a newly created fee in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'amount' => 'required|numeric|min:0|max:999999.99',
            'status' => 'required|in:paid,pending,overdue',
        ]);

        $fee = Fee::create($validated);

        return redirect()->route('fees.index')
            ->with('success', 'Fee created successfully.');
    }

    /**
     * Display the specified fee.
     */
    public function show(Fee $fee): View
    {
        $fee->load(['student.schoolClass', 'student.section', 'student.payments']);

        return view('fees.show', compact('fee'));
    }

    /**
     * Show the form for editing the specified fee.
     */
    public function edit(Fee $fee): View
    {
        $classes = SchoolClass::get();
        $students = Student::active()->with('schoolClass')->get();

        return view('fees.edit', compact('fee', 'classes', 'students'));
    }

    /**
     * Update the specified fee in storage.
     */
    public function update(Request $request, Fee $fee): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'amount' => 'required|numeric|min:0|max:999999.99',
            'status' => 'required|in:paid,pending,overdue',
        ]);

        $fee->update($validated);

        return redirect()->route('fees.index')
            ->with('success', 'Fee updated successfully.');
    }

    /**
     * Remove the specified fee from storage.
     */
    public function destroy(Fee $fee): RedirectResponse
    {
        $fee->delete();

        return redirect()->route('fees.index')
            ->with('success', 'Fee deleted successfully.');
    }

    /**
     * Mark fee as paid.
     */
    public function markAsPaid(Fee $fee): RedirectResponse
    {
        $fee->markAsPaid();

        return redirect()->back()
            ->with('success', 'Fee marked as paid.');
    }

    /**
     * Mark fee as overdue.
     */
    public function markAsOverdue(Fee $fee): RedirectResponse
    {
        $fee->markAsOverdue();

        return redirect()->back()
            ->with('success', 'Fee marked as overdue.');
    }

    /**
     * Generate fee receipt PDF.
     */
    public function generateReceipt(Fee $fee): Response
    {
        $fee->load(['student.schoolClass', 'student.section']);

        $data = [
            'fee' => $fee,
            'school_info' => [
                'name' => env('SCHOOL_NAME', 'ABC School'),
                'address' => env('SCHOOL_ADDRESS', 'School Address'),
                'phone' => env('SCHOOL_PHONE', 'School Phone'),
                'receipt_no' => 'F' . $fee->id . '-' . $fee->created_at->format('Ymd')
            ]
        ];

        $pdf = Pdf::loadView('fees.receipt-pdf', $data);

        $filename = 'fee_receipt_' . $fee->student->name . '_' . $fee->id . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate create fees for entire class.
     */
    public function createForClass(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'amount' => 'required|numeric|min:0|max:999999.99',
            'fee_type' => 'required|string|max:255',
        ]);

        $class = SchoolClass::find($validated['class_id']);
        $students = $class->students()->active()->get();

        $created = 0;
        foreach ($students as $student) {
            Fee::create([
                'student_id' => $student->id,
                'class_id' => $validated['class_id'],
                'amount' => $validated['amount'],
                'status' => 'pending'
            ]);
            $created++;
        }

        return redirect()->route('fees.index')
            ->with('success', "$created fees created for {$class->class_name}.");
    }

    /**
     * Show monthly fee report.
     */
    public function monthlyReport(Request $request): View
    {
        $month = $request->month ?? Carbon::now()->month;
        $year = $request->year ?? Carbon::now()->year;

        $fees = Fee::with(['student.schoolClass', 'student.section'])
            ->forMonth($month, $year)
            ->orderBy('created_at', 'desc')
            ->get();

        $statistics = [
            'total_fees' => $fees->count(),
            'total_amount' => $fees->sum('amount'),
            'paid_fees' => $fees->where('status', 'paid')->count(),
            'paid_amount' => $fees->where('status', 'paid')->sum('amount'),
            'pending_fees' => $fees->where('status', 'pending')->count(),
            'pending_amount' => $fees->where('status', 'pending')->sum('amount'),
            'overdue_fees' => $fees->where('status', 'overdue')->count(),
            'overdue_amount' => $fees->where('status', 'overdue')->sum('amount'),
        ];

        $classes = SchoolClass::get();

        return view('fees.monthly-report', compact('fees', 'statistics', 'month', 'year', 'classes'));
    }

    /**
     * Export monthly report to PDF.
     */
    public function exportMonthlyReport(Request $request): Response
    {
        $month = $request->month ?? Carbon::now()->month;
        $year = $request->year ?? Carbon::now()->year;

        $fees = Fee::with(['student.schoolClass', 'student.section'])
            ->forMonth($month, $year)
            ->orderBy('created_at', 'desc')
            ->get();

        $statistics = [
            'total_fees' => $fees->count(),
            'total_amount' => $fees->sum('amount'),
            'paid_fees' => $fees->where('status', 'paid')->count(),
            'paid_amount' => $fees->where('status', 'paid')->sum('amount'),
            'pending_fees' => $fees->where('status', 'pending')->count(),
            'pending_amount' => $fees->where('status', 'pending')->sum('amount'),
            'overdue_fees' => $fees->where('status', 'overdue')->count(),
            'overdue_amount' => $fees->where('status', 'overdue')->sum('amount'),
        ];

        $data = [
            'fees' => $fees,
            'statistics' => $statistics,
            'month' => $month,
            'year' => $year,
            'school_info' => [
                'name' => env('SCHOOL_NAME', 'ABC School'),
                'address' => env('SCHOOL_ADDRESS', 'School Address'),
                'phone' => env('SCHOOL_PHONE', 'School Phone'),
            ]
        ];

        $pdf = Pdf::loadView('fees.monthly-report-pdf', $data)->setPaper('a4', 'landscape');

        $filename = 'monthly_fee_report_' . $year . '_' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Get students for a class (AJAX endpoint).
     */
    public function getStudents(Request $request)
    {
        $classId = $request->class_id;

        $students = Student::where('class_id', $classId)
            ->active()
            ->get(['id', 'name', 'roll_no']);

        return response()->json($students);
    }
}