<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:superadmin,accountant'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the payments.
     */
    public function index(Request $request): View
    {
        $query = Payment::with(['student.schoolClass', 'student.section']);

        // Filter by student
        if ($request->has('student_id') && $request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by month
        if ($request->has('month') && $request->month) {
            $query->forMonth($request->month, $request->year);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(15);

        $students = Student::active()->orderBy('name')->get();

        $paymentMethods = ['cash', 'bank_transfer', 'credit_card', 'check'];

        return view('payments.index', compact('payments', 'students', 'paymentMethods'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(Request $request): View
    {
        $students = Student::active()->with('schoolClass')->get();

        // Pre-select student if provided
        $selectedStudentId = $request->student_id;

        return view('payments.create', compact('students', 'selectedStudentId'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,check',
            'remarks' => 'nullable|string|max:500',
        ]);

        $payment = Payment::create($validated);

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment): View
    {
        $payment->load(['student.schoolClass', 'student.section']);

        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment): View
    {
        $students = Student::active()->with('schoolClass')->get();

        return view('payments.edit', compact('payment', 'students'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,check',
            'remarks' => 'nullable|string|max:500',
        ]);

        $payment->update($validated);

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    /**
     * Process payment and mark associated fees as paid.
     */
    public function processPayment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'fee_ids' => 'required|array',
            'fee_ids.*' => 'exists:fees,id',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,check',
            'remarks' => 'nullable|string|max:500',
        ]);

        // Create the payment record
        $payment = Payment::create([
            'student_id' => $validated['student_id'],
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
        ]);

        // Mark selected fees as paid
        $fees = Fee::whereIn('id', $validated['fee_ids'])
            ->where('student_id', $validated['student_id'])
            ->get();

        foreach ($fees as $fee) {
            $fee->markAsPaid();
        }

        return redirect()->route('payments.index')
            ->with('success', 'Payment processed and fees marked as paid.');
    }

    /**
     * Get student's pending fees for payment processing.
     */
    public function getStudentFees(Request $request): JsonResponse
    {
        $studentId = $request->student_id;

        $pendingFees = Fee::where('student_id', $studentId)
            ->where('status', 'pending')
            ->get(['id', 'amount', 'created_at']);

        $totalPending = $pendingFees->sum('amount');

        return response()->json([
            'fees' => $pendingFees,
            'total' => $totalPending
        ]);
    }

    /**
     * Generate payment statistics.
     */
    public function statistics(Request $request)
    {
        $month = $request->month ?? Carbon::now()->month;
        $year = $request->year ?? Carbon::now()->year;

        $payments = Payment::forMonth($month, $year)->get();

        $statistics = [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('amount'),
            'by_method' => $payments->groupBy('payment_method')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'amount' => $group->sum('amount')
                ];
            }),
            'top_students' => $payments->groupBy('student_id')->map(function ($group) {
                return [
                    'student' => $group->first()->student,
                    'total' => $group->sum('amount'),
                    'payments_count' => $group->count()
                ];
            })->sortByDesc('total')->take(10)->values()
        ];

        return response()->json($statistics);
    }
}