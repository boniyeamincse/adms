<?php

namespace App\Services;

use App\Models\AdmitCard;
use App\Models\Exam;
use App\Models\ExamHall;
use App\Models\ExamSeat;
use App\Models\ExamSeatAssignment;
use Illuminate\Support\Collection;

class SeatingGenerator
{
    /**
     * Generate random seating arrangement.
     */
    public static function generateRandomSeating(Exam $exam, Collection $halls, array $rules = []): array
    {
        $students = self::getShuffledStudents($exam);
        $assignments = [];

        foreach ($halls as $hall) {
            $availableSeats = self::getAvailableSeatsForHall($hall, $exam);
            $seatCount = min(count($availableSeats), $students->count());

            for ($i = 0; $i < $seatCount; $i++) {
                $student = $students->shift();
                if (!$student) break;

                $assignments[] = self::createAssignmentRecord($student, $availableSeats[$i]);
            }

            if ($students->isEmpty()) break;
        }

        return $assignments;
    }

    /**
     * Generate section-wise seating arrangement.
     */
    public static function generateSectionWiseSeating(Exam $exam, Collection $halls, array $rules = []): array
    {
        $minDistance = $rules['min_distance'] ?? 1;
        $studentsBySection = self::getStudentsGroupedBySection($exam);
        $assignments = [];

        foreach ($studentsBySection as $sectionStudents) {
            $assignments = array_merge(
                $assignments,
                self::assignSectionWithConstraints($sectionStudents, $halls, $exam, $minDistance)
            );
        }

        return $assignments;
    }

    /**
     * Generate mixed/districted seating arrangement.
     */
    public static function generateMixedSeating(Exam $exam, Collection $halls, array $rules = []): array
    {
        $minDistance = $rules['min_distance'] ?? 1;
        $useSectionSeparation = $rules['section_separation'] ?? false;

        $students = self::getAllStudents($exam);
        $optimalDistribution = self::calculateOptimalDistribution($students, $halls);

        return self::distributeStudentsWithConstraints(
            $students,
            $halls,
            $exam,
            $optimalDistribution,
            $minDistance,
            $useSectionSeparation
        );
    }

    /**
     * Assign section with minimum distance constraints.
     */
    private static function assignSectionWithConstraints(Collection $sectionStudents, Collection $halls, Exam $exam, int $minDistance): array
    {
        $assignments = [];
        $usedPositions = [];
        $currentHall = 0;

        foreach ($sectionStudents as $student) {
            $hall = $halls[$currentHall % count($halls)];
            $seatFound = false;

            foreach (self::getAvailableSeatsForHall($hall, $exam) as $seat) {
                if (!isset($usedPositions[$hall->id]) || !self::isPositionTooClose($seat, $usedPositions[$hall->id], $minDistance)) {
                    $assignments[] = self::createAssignmentRecord($student, $seat);
                    $usedPositions[$hall->id][] = ['row' => $seat->row_no, 'col' => $seat->col_no];
                    $seatFound = true;
                    break;
                }
            }

            if (!$seatFound) {
                $currentHall++;
                if ($currentHall >= count($halls)) {
                    break; // No more halls available
                }
            }
        }

        return $assignments;
    }

    /**
     * Check if seat position is too close to existing assignments.
     */
    private static function isPositionTooClose(ExamSeat $seat, array $existingPositions, int $minDistance): bool
    {
        foreach ($existingPositions as $position) {
            $distance = self::calculateDistance(
                $seat->row_no, $seat->col_no,
                $position['row'], $position['col']
            );

            if ($distance <= $minDistance) {
                return true;
            }
        }
        return false;
    }

    /**
     * Calculate Manhattan distance between two positions.
     */
    private static function calculateDistance(int $row1, int $col1, int $row2, int $col2): float
    {
        return abs($row1 - $row2) + abs($col1 - $col2);
    }

    /**
     * Distribute students with various constraints.
     */
    private static function distributeStudentsWithConstraints(
        Collection $students,
        Collection $halls,
        Exam $exam,
        array $distribution,
        int $minDistance,
        bool $useSectionSeparation
    ): array {
        $assignments = [];
        $usedPositions = [];
        $studentSections = $useSectionSeparation ? self::getStudentSections($students) : [];

        foreach ($students as $student) {
            $hallIndex = self::selectOptimalHallForStudent($student, $halls, $assignments);
            $hall = $halls[$hallIndex];

            $assigned = false;
            foreach (self::getAvailableSeatsForHall($hall, $exam) as $seat) {
                if (self::canAssignSeatToStudent($seat, $student, $assignments, $minDistance, $useSectionSeparation, $studentSections)) {
                    $assignments[] = self::createAssignmentRecord($student, $seat);
                    if ($useSectionSeparation) {
                        $usedPositions[$hallIndex][] = [
                            'row' => $seat->row_no,
                            'col' => $seat->col_no,
                            'section' => $student->student->section_id ?? null
                        ];
                    }
                    $assigned = true;
                    break;
                }
            }

            if (!$assigned) break;
        }

        return $assignments;
    }

    /**
     * Check if seat can be assigned to student based on constraints.
     */
    private static function canAssignSeatToStudent(
        ExamSeat $seat,
        AdmitCard $admitCard,
        array $existingAssignments,
        int $minDistance,
        bool $useSectionSeparation,
        array $studentSections
    ): bool {
        // Check minimum distance
        if (self::violatesMinDistance($seat, $existingAssignments, $minDistance)) {
            return false;
        }

        // Check section separation
        if ($useSectionSeparation && self::violatesSectionSeparation($seat, $admitCard, $existingAssignments, $studentSections)) {
            return false;
        }

        // Check handicap accessibility
        if ($seat->is_handicap_seat && !$admitCard->student->needs_handicap) {
            return false;
        }

        return true;
    }

    /**
     * Check if assignment violates minimum distance rule.
     */
    private static function violatesMinDistance(ExamSeat $seat, array $assignments, int $minDistance): bool
    {
        foreach ($assignments as $assignment) {
            if ($assignment['seat']->examHall->id !== $seat->examHall->id) continue;

            $distance = self::calculateDistance(
                $seat->row_no, $seat->col_no,
                $assignment['seat']->row_no, $assignment['seat']->col_no
            );

            if ($distance <= $minDistance) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if assignment violates section separation rule.
     */
    private static function violatesSectionSeparation(
        ExamSeat $seat,
        AdmitCard $admitCard,
        array $assignments,
        array $studentSections
    ): bool {
        foreach ($assignments as $assignment) {
            if ($assignment['seat']->examHall->id !== $seat->examHall->id) continue;

            $studentSection = $admitCard->student->section_id ?? null;
            $assignmentSection = $assignment['admit_card']->student->section_id ?? null;

            if ($studentSection === $assignmentSection) {
                $distance = self::calculateDistance(
                    $seat->row_no, $seat->col_no,
                    $assignment['seat']->row_no, $assignment['seat']->col_no
                );

                if ($distance <= 2) { // Minimum 2 seats apart for same section
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Create assignment record data.
     */
    private static function createAssignmentRecord(AdmitCard $admitCard, ExamSeat $seat): array
    {
        return [
            'admit_card_id' => $admitCard->id,
            'seat_id' => $seat->id,
            'row' => $seat->row_no,
            'col' => $seat->col_no,
            'hall' => $seat->examHall->hall_name,
            'student_name' => $admitCard->student->name,
            'student_roll_no' => $admitCard->student->roll_no,
            'student_class' => $admitCard->student->schoolClass->class_name ?? '',
        ];
    }

    // Helper methods...
    private static function getShuffledStudents(Exam $exam): Collection
    {
        return $exam->admitCards()->with('student.schoolClass')->inRandomOrder()->get();
    }

    private static function getStudentsGroupedBySection(Exam $exam): Collection
    {
        return $exam->admitCards()->with('student.section')->get()->groupBy('student.section.section_name');
    }

    private static function getAllStudents(Exam $exam): Collection
    {
        return $exam->admitCards()->with('student.schoolClass', 'student.section')->get();
    }

    private static function getAvailableSeatsForHall(ExamHall $hall, Exam $exam): Collection
    {
        return ExamSeat::where('exam_hall_id', $hall->id)
                        ->where('exam_id', $exam->id)
                        ->where('is_blocked', false)
                        ->whereDoesntHave('assignment')
                        ->orderBy('row_no')
                        ->orderBy('col_no')
                        ->get();
    }

    private static function calculateOptimalDistribution(Collection $students, Collection $halls): array
    {
        $totalStudents = $students->count();
        $totalSeats = $halls->sum('capacity');
        return round($totalStudents / $totalSeats, 1);
    }

    private static function selectOptimalHallForStudent(AdmitCard $student, Collection $halls, array $assignments): int
    {
        // Simple round-robin for now - can be enhanced with better logic
        return count($assignments) % count($halls);
    }

    private static function getStudentSections(Collection $students): array
    {
        return $students->groupBy('student.section_id')->keys()->filter()->toArray();
    }
}