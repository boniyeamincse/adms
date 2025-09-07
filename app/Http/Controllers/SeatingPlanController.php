<?php

namespace App\Http\Controllers;

use App\Models\AdmitCard;
use App\Models\Exam;
use App\Models\ExamHall;
use App\Models\ExamSeat;
use App\Models\ExamSeatAssignment;
use App\Services\SeatingGenerator;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SeatingPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:superadmin,teacher']);
    }

    /**
     * Display a listing of seating plans.
     */
    public function index(): View
    {
        $exams = Exam::withCount(['admitCards', 'seats'])->orderBy('start_date')->get();
        $halls = ExamHall::withCount('seats')->get();

        return view('seating-plans.index', compact('exams', 'halls'));
    }

    /**
     * Show the form for creating a new seating plan.
     */
    public function create(Exam $exam): View
    {
        $halls = ExamHall::orderBy('hall_name')->get();
        $studentCount = $exam->admitCards()->count();

        return view('seating-plans.create', compact('exam', 'halls', 'studentCount'));
    }

    /**
     * Generate seating plan for an exam.
     */
    public function generate(Request $request, Exam $exam): RedirectResponse
    {
        $request->validate([
            'hall_ids' => 'required|array|min:1',
            'hall_ids.*' => 'exists:exam_halls,id',
            'algorithm' => 'required|in:random,section_wise,mixed',
            'min_distance' => 'nullable|integer|min:1|max:5',
            'section_separation' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $halls = ExamHall::whereIn('id', $request->hall_ids)->get();

            // Clear existing assignments
            $this->clearExistingAssignments($exam);

            // Generate new assignments
            switch ($request->algorithm) {
                case 'random':
                    $assignments = SeatingGenerator::generateRandomSeating($exam, $halls, $request->only(['min_distance']));
                    break;
                case 'section_wise':
                    $assignments = SeatingGenerator::generateSectionWiseSeating($exam, $halls, $request->only(['min_distance', 'section_separation']));
                    break;
                case 'mixed':
                    $assignments = SeatingGenerator::generateMixedSeating($exam, $halls, $request->only(['min_distance', 'section_separation']));
                    break;
            }

            // Create seats and assignments
            $this->createSeatsAndAssignments($exam, $halls, $assignments);

            DB::commit();

            return redirect()->route('seating-plans.show', $exam)
                           ->with('success', 'Seating plan generated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Seating plan generation failed', ['error' => $e->getMessage()]);

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to generate seating plan: ' . $e->getMessage());
        }
    }

    /**
     * Display the seating plan for an exam.
     */
    public function show(Exam $exam): View
    {
        $hallSeatings = $this->getSeatingPlanForExam($exam);

        return view('seating-plans.show', compact('exam', 'hallSeatings'));
    }

    /**
     * Swap students between seats.
     */
    public function swapStudents(Request $request, Exam $exam): JsonResponse
    {
        $request->validate([
            'admit_card_1' => 'required|exists:admit_cards,id',
            'admit_card_2' => 'required|exists:admit_cards,id',
        ]);

        $assignment1 = ExamSeatAssignment::where('admit_card_id', $request->admit_card_1)
                                         ->whereHas('examSeat', function ($query) use ($exam) {
                                             $query->where('exam_id', $exam->id);
                                         })->first();

        $assignment2 = ExamSeatAssignment::where('admit_card_id', $request->admit_card_2)
                                         ->whereHas('examSeat', function ($query) use ($exam) {
                                             $query->where('exam_id', $exam->id);
                                         })->first();

        if (!$assignment1 || !$assignment2) {
            return response()->json(['success' => false, 'message' => 'One or both assignments not found']);
        }

        // Swap assignments
        $tempSeatId = $assignment1->exam_seat_id;
        $assignment1->exam_seat_id = $assignment2->exam_seat_id;
        $assignment2->exam_seat_id = $tempSeatId;

        $assignment1->save();
        $assignment2->save();

        return response()->json(['success' => true]);
    }

    /**
     * Regenerate seating plan.
     */
    public function regenerate(Request $request, Exam $exam): RedirectResponse
    {
        // Get current hall selection and settings to regenerate
        $lastSeats = ExamSeat::where('exam_id', $exam->id)->with('examHall')->get();
        $hallIds = $lastSeats->pluck('exam_hall_id')->unique()->toArray();

        return redirect()->route('seating-plans.create', $exam)
                         ->with('hall_ids', $hallIds)
                         ->with('regenerate', true);
    }

    /**
     * Export seating plan to PDF.
     */
    public function exportToPdf(Request $request, Exam $exam): \Illuminate\Http\Response
    {
        $hallSeatings = $this->getSeatingPlanForExam($exam);

        $data = [
            'exam' => $exam,
            'hall_seatings' => $hallSeatings,
            'school_info' => [
                'name' => env('SCHOOL_NAME', 'ABC School'),
                'address' => env('SCHOOL_ADDRESS', 'School Address'),
                'phone' => env('SCHOOL_PHONE', 'School Phone'),
                'logo' => public_path('images/school-logo.png')
            ],
            'generated_at' => now()->format('d/m/Y H:i:s'),
        ];

        $pdf = Pdf::loadView('seating-plans.pdf', $data)->setPaper('a4', 'landscape');

        $filename = 'seating_plan_' . $exam->exam_name . '_' . now()->format('Y_m_d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Get seating plan analytics.
     */
    public function getAnalytics(Request $request, Exam $exam): JsonResponse
    {
        $assignments = ExamSeatAssignment::whereHas('examSeat', function ($query) use ($exam) {
            $query->where('exam_id', $exam->id);
        })->with('admitCard.student.schoolClass', 'examSeat.examHall')->get();

        $analytics = [
            'total_assigned' => $assignments->count(),
            'hall_distribution' => $this->calculateHallDistribution($assignments),
            'class_distribution' => $this->calculateClassDistribution($assignments),
            'section_distribution' => $this->calculateSectionDistribution($assignments),
            'utilization_stats' => $this->calculateUtilizationStats($exam),
        ];

        return response()->json($analytics);
    }

    /**
     * Clear existing assignments for exam.
     */
    private function clearExistingAssignments(Exam $exam): void
    {
        ExamSeatAssignment::whereHas('examSeat', function ($query) use ($exam) {
            $query->where('exam_id', $exam->id);
        })->delete();

        ExamSeat::where('exam_id', $exam->id)->delete();
    }

    /**
     * Create seats and assignments from generated plan.
     */
    private function createSeatsAndAssignments(Exam $exam, Collection $halls, array $assignments): void
    {
        $seatsData = [];

        foreach ($assignments as $assignment) {
            $seat = ExamSeat::where('exam_id', $exam->id)
                           ->where('exam_hall_id', $assignment['hall'] ? $halls->where('hall_name', $assignment['hall'])->first()->id : $halls->first()->id)
                           ->where('row_no', $assignment['row'])
                           ->where('col_no', $assignment['col'])
                           ->first();

            if (!$seat) {
                $hall = $assignment['hall'] ? $halls->where('hall_name', $assignment['hall'])->first() : $halls->first();
                $seat = ExamSeat::create([
                    'exam_id' => $exam->id,
                    'exam_hall_id' => $hall->id,
                    'seat_no' => $this->generateSeatNo($assignment['row'], $assignment['col']),
                    'row_no' => $assignment['row'],
                    'col_no' => $assignment['col'],
                    'is_blocked' => false,
                ]);
            }

            ExamSeatAssignment::create([
                'exam_seat_id' => $seat->id,
                'admit_card_id' => $assignment['admit_card_id'],
                'assigned_at' => now(),
            ]);

            // Update admit card with seating info
            AdmitCard::find($assignment['admit_card_id'])->update([
                'assigned_seat_row' => $assignment['row'],
                'assigned_seat_col' => $assignment['col'],
                'assigned_hall_name' => $assignment['hall'],
            ]);
        }
    }

    /**
     * Get seating plan data for display.
     */
    private function getSeatingPlanForExam(Exam $exam): array
    {
        $hallSeatings = [];

        $halls = ExamHall::whereHas('seats', function ($query) use ($exam) {
            $query->where('exam_id', $exam->id);
        })->with(['seats' => function ($query) use ($exam) {
            $query->where('exam_id', $exam->id)->with('assignment.admitCard.student.schoolClass');
        }])->get();

        foreach ($halls as $hall) {
            $grid = $this->initializeGridMatrix($hall);
            $this->populateGridWithAssignments($grid, $hall->seats);
            $hallSeatings[$hall->hall_name] = $grid;
        }

        return $hallSeatings;
    }

    private function initializeGridMatrix(ExamHall $hall): array
    {
        $grid = [];
        for ($row = 1; $row <= $hall->layout_rows; $row++) {
            $grid[$row] = [];
            for ($col = 1; $col <= $hall->layout_cols; $col++) {
                $grid[$row][$col] = null;
            }
        }
        return $grid;
    }

    private function populateGridWithAssignments(array &$grid, Collection $seats): void
    {
        foreach ($seats as $seat) {
            if ($seat->assignment && $seat->admitCard) {
                $student = $seat->assignment->admitCard->student;
                $grid[$seat->row_no][$seat->col_no] = [
                    'student_name' => $student->name,
                    'roll_no' => $student->roll_no,
                    'class_name' => $student->schoolClass->class_name ?? '',
                    'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
                    'seat_no' => $seat->seat_no,
                ];
            }
        }
    }

    private function calculateHallDistribution(Collection $assignments): array
    {
        return $assignments->groupBy('examSeat.examHall.hall_name')
                          ->map(function ($group) {
                              return $group->count();
                          })->toArray();
    }

    private function calculateClassDistribution(Collection $assignments): array
    {
        return $assignments->groupBy('admitCard.student.schoolClass.class_name')
                          ->map(function ($group) {
                              return $group->count();
                          })->toArray();
    }

    private function calculateSectionDistribution(Collection $assignments): array
    {
        return $assignments->groupBy('admitCard.student.section.section_name')
                          ->map(function ($group) {
                              return $group->count();
                          })->toArray();
    }

    private function calculateUtilizationStats(Exam $exam): array
    {
        $totalSeats = ExamSeat::where('exam_id', $exam->id)->where('is_blocked', false)->count();
        $assignedSeats = ExamSeatAssignment::whereHas('examSeat', function ($query) use ($exam) {
            $query->where('exam_id', $exam->id);
        })->count();

        return [
            'total_seats' => $totalSeats,
            'assigned_seats' => $assignedSeats,
            'utilization_percentage' => $totalSeats > 0 ? round(($assignedSeats / $totalSeats) * 100, 1) : 0,
        ];
    }

    private function generateSeatNo(int $row, int $col): string
    {
        return chr(64 + $row) . str_pad($col, 2, '0', STR_PAD_LEFT);
    }
}