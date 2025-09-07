<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Exam;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\AdmitCard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        \Log::info('DashboardController@index - Method called', [
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name ?? 'no user',
        ]);

        $user = auth()->user();
        $stats = $this->getDashboardStats();

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        // Get upcoming exams
        $upcomingExams = Exam::with(['schoolClass', 'subjects'])
            ->where('start_date', '>', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        // Get low fee collection classes
        $feeCollectionStats = $this->getFeeCollectionStats();

        // Get pending admit cards
        $pendingAdmitCards = AdmitCard::with(['student.schoolClass', 'exam'])
            ->whereNull('seat_no')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'recentActivities',
            'upcomingExams',
            'feeCollectionStats',
            'pendingAdmitCards',
            'user'
        ));
    }

    /**
     * Get comprehensive dashboard statistics.
     */
    private function getDashboardStats(): array
    {
        // Student statistics
        $totalStudents = Student::count();
        $activeStudents = Student::active()->count();
        $inactiveStudents = Student::where('status', 'inactive')->count();

        // Class statistics
        $totalClasses = SchoolClass::count();
        $totalSections = DB::table('sections')->count();

        // Exam statistics
        $upcomingExams = Exam::where('start_date', '>', now())->count();
        $activeExams = Exam::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->count();
        $totalAdmitCards = AdmitCard::count();

        // Fee collection statistics
        $thisMonth = Carbon::now();
        $feeStats = Fee::thisMonth()->get();
        $totalFees = $feeStats->count();
        $paidFees = $feeStats->where('status', 'paid')->count();
        $paidAmount = $feeStats->where('status', 'paid')->sum('amount');
        $totalFeeAmount = $feeStats->sum('amount');

        // Payment statistics
        $monthlyPayments = Payment::thisMonth()->get();
        $totalPayments = $monthlyPayments->count();
        $totalPaymentAmount = $monthlyPayments->sum('amount');

        // User statistics
        $totalUsers = User::count();
        $activeUsers = User::where('created_at', '>', now()->subDays(30))->count();

        return [
            'students' => [
                'total' => $totalStudents,
                'active' => $activeStudents,
                'inactive' => $inactiveStudents,
                'active_percentage' => $totalStudents > 0 ? round(($activeStudents / $totalStudents) * 100, 1) : 0,
            ],
            'classes' => [
                'total_classes' => $totalClasses,
                'total_sections' => $totalSections,
            ],
            'exams' => [
                'upcoming' => $upcomingExams,
                'active' => $activeExams,
                'total_admit_cards' => $totalAdmitCards,
            ],
            'fees' => [
                'total' => $totalFees,
                'paid' => $paidFees,
                'paid_percentage' => $totalFees > 0 ? round(($paidFees / $totalFees) * 100, 1) : 0,
                'paid_amount' => $paidAmount,
                'total_amount' => $totalFeeAmount,
                'pending_amount' => $totalFeeAmount - $paidAmount,
            ],
            'payments' => [
                'total' => $totalPayments,
                'total_amount' => $totalPaymentAmount,
                'avg_payment' => $totalPayments > 0 ? round($totalPaymentAmount / $totalPayments, 2) : 0,
            ],
            'users' => [
                'total' => $totalUsers,
                'recent' => $activeUsers,
            ],
        ];
    }

    /**
     * Get recent system activities.
     */
    private function getRecentActivities(): \Illuminate\Support\Collection
    {
        $activities = [];

        // Recent student registrations
        $recentStudents = Student::orderBy('created_at', 'desc')->limit(5)->get();
        foreach ($recentStudents as $student) {
            $activities[] = [
                'type' => 'student_created',
                'title' => 'New Student Registered',
                'description' => "{$student->name} ({$student->schoolClass->class_name})",
                'time' => $student->created_at,
                'icon' => '👨‍🎓',
            ];
        }

        // Recent payments
        $recentPayments = Payment::with('student')->orderBy('payment_date', 'desc')->limit(5)->get();
        foreach ($recentPayments as $payment) {
            $activities[] = [
                'type' => 'payment_received',
                'title' => 'Payment Received',
                'description' => "৳{$payment->amount} from {$payment->student->name}",
                'time' => $payment->payment_date,
                'icon' => '💰',
            ];
        }

        // Recent admit card generations
        $recentAdmitCards = AdmitCard::with(['student', 'exam'])->orderBy('generated_at', 'desc')->limit(5)->get();
        foreach ($recentAdmitCards as $card) {
            $activities[] = [
                'type' => 'admit_card_generated',
                'title' => 'Admit Card Generated',
                'description' => "{$card->student->name} - {$card->exam->exam_name}",
                'time' => $card->generated_at,
                'icon' => '📄',
            ];
        }

        // Recent exams created
        $recentExams = Exam::with('schoolClass')->orderBy('created_at', 'desc')->limit(3)->get();
        foreach ($recentExams as $exam) {
            $activities[] = [
                'type' => 'exam_created',
                'title' => 'New Exam Created',
                'description' => "{$exam->exam_name} ({$exam->schoolClass->class_name})",
                'time' => $exam->created_at,
                'icon' => '📝',
            ];
        }

        // Sort activities by time (most recent first) and limit to 10
        return collect($activities)
            ->sortByDesc('time')
            ->take(10)
            ->values();
    }

    /**
     * Get fee collection statistics by class.
     */
    private function getFeeCollectionStats(): array
    {
        return SchoolClass::withCount([
            'fees as total_fees',
            'fees as paid_fees' => function ($query) {
                $query->where('status', 'paid');
            }
        ])
        ->with([
            'fees' => function ($query) {
                $query->selectRaw('class_id, SUM(amount) as total_amount')
                    ->groupBy('class_id');
            }
        ])
        ->get()
        ->map(function ($class) {
            $paidFees = Fee::where('class_id', $class->id)->where('status', 'paid')->get();
            $totalFees = Fee::where('class_id', $class->id)->get();
            $paidAmount = $paidFees->sum('amount');
            $totalAmount = $totalFees->sum('amount');

            return [
                'class' => $class->class_name . ' (' . $class->academic_year . ')',
                'total_students' => $class->students_count,
                'total_fees' => $totalFees->count(),
                'paid_fees' => $paidFees->count(),
                'collection_rate' => $totalFees->count() > 0 ?
                    round(($paidFees->count() / $totalFees->count()) * 100, 1) : 0,
                'paid_amount' => $paidAmount,
                'total_amount' => $totalAmount,
                'pending_amount' => $totalAmount - $paidAmount,
            ];
        })
        ->sortByDesc('collection_rate')
        ->take(8)
        ->toArray();
    }

    /**
     * Get chart data for dashboard.
     */
    public function getChartData(Request $request): JsonResponse
    {
        $type = $request->get('type', 'monthly-fees');
        $data = [];

        switch ($type) {
            case 'student-registrations':
                $data = $this->getStudentRegistrationData($request);
                break;
            case 'fee-collection':
                $data = $this->getFeeCollectionData($request);
                break;
            case 'payment-methods':
                $data = $this->getPaymentMethodData($request);
                break;
            case 'class-distribution':
                $data = $this->getClassDistributionData();
                break;
            default:
                $data = $this->getMonthlyFeesData($request);
        }

        return response()->json($data);
    }

    /**
     * Get monthly fees data for charts.
     */
    private function getMonthlyFeesData(Request $request): array
    {
        $months = collect();
        $year = $request->get('year', date('Y'));
        $monthsRange = 6;

        for ($i = $monthsRange - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;

            $fees = Fee::ForMonth($month, $year)->get();
            $paidFees = $fees->where('status', 'paid');
            $paidAmount = $paidFees->sum('amount');

            $months->push([
                'month' => $date->format('M Y'),
                'paid_amount' => $paidAmount,
                'paid_count' => $paidFees->count(),
                'total_count' => $fees->count(),
            ]);
        }

        return [
            'labels' => $months->pluck('month')->toArray(),
            'datasets' => [
                [
                    'label' => 'Paid Amount (৳)',
                    'data' => $months->pluck('paid_amount')->toArray(),
                    'backgroundColor' => 'rgba(40, 167, 69, 0.2)',
                    'borderColor' => 'rgba(40, 167, 69, 1)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Paid Fee Count',
                    'data' => $months->pluck('paid_count')->toArray(),
                    'backgroundColor' => 'rgba(23, 162, 184, 0.2)',
                    'borderColor' => 'rgba(23, 162, 184, 1)',
                    'borderWidth' => 2,
                    'yAxisID' => 'y1',
                ],
            ],
        ];
    }

    /**
     * Get student registration data.
     */
    private function getStudentRegistrationData(Request $request): array
    {
        $months = collect();
        $monthsRange = 12;

        for ($i = $monthsRange - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;

            $registrations = Student::ForMonth($month, $year)->count();

            $months->push([
                'month' => $date->format('M'),
                'registrations' => $registrations,
            ]);
        }

        return [
            'labels' => $months->pluck('month')->toArray(),
            'datasets' => [
                [
                    'label' => 'Student Registrations',
                    'data' => $months->pluck('registrations')->toArray(),
                    'backgroundColor' => 'rgba(255, 193, 7, 0.2)',
                    'borderColor' => 'rgba(255, 193, 7, 1)',
                    'borderWidth' => 2,
                ],
            ],
        ];
    }

    /**
     * Get fee collection data by class.
     */
    private function getFeeCollectionData(Request $request): array
    {
        $classes = SchoolClass::with([
            'fees' => function ($query) {
                $query->where('status', 'paid');
            }
        ])->get();

        return [
            'labels' => $classes->pluck('class_name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Paid Fees',
                    'data' => $classes->map(function ($class) {
                        return $class->fees->where('status', 'paid')->count();
                    })->toArray(),
                    'backgroundColor' => 'rgba(40, 167, 69, 0.3)',
                    'borderColor' => 'rgba(40, 167, 69, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    /**
     * Get payment method distribution.
     */
    private function getPaymentMethodData(Request $request): array
    {
        $methods = Payment::thisMonth()
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        $methodLabels = [
            'cash' => 'Cash 💵',
            'bank_transfer' => 'Bank Transfer 🏦',
            'credit_card' => 'Credit Card 💳',
            'check' => 'Check 📝',
        ];

        return [
            'labels' => $methods->map(function ($method) use ($methodLabels) {
                return $methodLabels[$method->payment_method] ?? ucfirst(str_replace('_', ' ', $method->payment_method));
            })->toArray(),
            'datasets' => [
                [
                    'label' => 'Payment Methods',
                    'data' => $methods->pluck('total')->toArray(),
                    'backgroundColor' => [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(23, 162, 184, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)',
                    ],
                ],
            ],
        ];
    }

    /**
     * Get class distribution data.
     */
    private function getClassDistributionData(): array
    {
        $classes = SchoolClass::withCount('students')->get();

        return [
            'labels' => $classes->map(function ($class) {
                return $class->class_name . ' (' . $class->academic_year . ')';
            })->toArray(),
            'datasets' => [
                [
                    'label' => 'Student Count',
                    'data' => $classes->pluck('students_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)',
                    ],
                ],
            ],
        ];
    }

    /**
     * Export dashboard data to PDF report.
     */
    public function exportReport(Request $request): Response
    {
        $stats = $this->getDashboardStats();
        $feeCollectionStats = $this->getFeeCollectionStats();

        $data = [
            'stats' => $stats,
            'fee_collection' => $feeCollectionStats,
            'school_info' => [
                'name' => env('SCHOOL_NAME', 'ABC School'),
                'address' => env('SCHOOL_ADDRESS', 'School Address'),
                'phone' => env('SCHOOL_PHONE', 'School Phone'),
            ],
            'generated_at' => now(),
            'period' => Carbon::now()->format('F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dashboard.report-pdf', $data)
            ->setPaper('a4', 'landscape');

        $filename = 'dashboard_report_' . Carbon::now()->format('Y_m_d_H_i_s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Quick statistics API for AJAX updates.
     */
    public function getStatsApi(): JsonResponse
    {
        $stats = $this->getDashboardStats();

        return response()->json([
            'success' => true,
            'data' => $stats,
            'timestamp' => now()->timestamp,
        ]);
    }
}