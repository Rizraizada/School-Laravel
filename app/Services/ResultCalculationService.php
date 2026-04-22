<?php

namespace App\Services;

use App\Models\ExamSubject;
use Illuminate\Support\Collection;

class ResultCalculationService
{
    /**
     * @param  Collection<int, array{exam_subject_id:int, obtained_mark:float|int|string|null}>  $markRows
     * @param  Collection<int, ExamSubject>  $examSubjects
     * @return array{
     *     items: array<int, array<string, mixed>>,
     *     total_marks: float,
     *     gpa: float,
     *     grade: string,
     *     result_status: string,
     *     meta: array<string, mixed>,
     *     raw_marks: array<string, float>
     * }
     */
    public function calculate(Collection $markRows, Collection $examSubjects): array
    {
        $subjectMap = $examSubjects->keyBy('id');
        $items = [];
        $rawMarks = [];
        $totalMarks = 0.0;
        $gpaTotal = 0.0;
        $gpaCount = 0;
        $optionalBonus = 0.0;
        $hasFail = false;

        foreach ($markRows as $row) {
            $subjectId = (int) ($row['exam_subject_id'] ?? 0);
            $subject = $subjectMap->get($subjectId);

            if (! $subject) {
                continue;
            }

            $obtained = (float) ($row['obtained_mark'] ?? 0);
            $obtained = max(0, min($obtained, (float) $subject->full_mark));

            $gpaPoint = $this->gpaPointFromMark($obtained, (int) $subject->full_mark);
            $gradeLetter = $this->gradeLetterFromPoint($gpaPoint);
            $isPassed = $obtained >= (float) $subject->pass_mark;

            if (! $subject->is_optional && ! $isPassed) {
                $hasFail = true;
            }

            if ($subject->include_in_total_score) {
                $totalMarks += $obtained;
            }

            if ($subject->include_in_gpa) {
                if ($subject->is_optional) {
                    if ($gpaPoint > 2.0) {
                        $optionalBonus += ($gpaPoint - 2.0);
                    }
                } else {
                    $gpaTotal += $gpaPoint;
                    $gpaCount++;
                }
            }

            $subjectKey = $subject->subject_code ?: (string) $subject->id;
            $rawMarks[$subjectKey] = round($obtained, 2);

            $items[] = [
                'exam_subject_id' => $subject->id,
                'subject_id' => $subject->subject_id,
                'subject_name' => $subject->subject_name,
                'subject_code' => $subject->subject_code,
                'obtained_mark' => round($obtained, 2),
                'full_mark' => $subject->full_mark,
                'pass_mark' => $subject->pass_mark,
                'is_optional' => $subject->is_optional,
                'include_in_gpa' => $subject->include_in_gpa,
                'include_in_total_score' => $subject->include_in_total_score,
                'gpa_point' => $gpaPoint,
                'grade_letter' => $gradeLetter,
                'is_passed' => $isPassed,
                'sort_order' => $subject->sort_order,
            ];
        }

        $baseGpa = $gpaCount > 0 ? ($gpaTotal / $gpaCount) : 0.0;
        $calculatedGpa = min(5.0, $baseGpa + $optionalBonus);
        $resultStatus = $hasFail ? 'fail' : 'pass';
        $finalGpa = $hasFail ? 0.0 : $calculatedGpa;
        $grade = $hasFail ? 'F' : $this->gradeLetterFromPoint($finalGpa);

        return [
            'items' => $items,
            'total_marks' => round($totalMarks, 2),
            'gpa' => round($finalGpa, 2),
            'grade' => $grade,
            'result_status' => $resultStatus,
            'meta' => [
                'gpa_subject_count' => $gpaCount,
                'optional_bonus' => round($optionalBonus, 2),
                'base_gpa' => round($baseGpa, 2),
                'has_fail' => $hasFail,
            ],
            'raw_marks' => $rawMarks,
        ];
    }

    public function gpaPointFromMark(float $obtained, int $fullMark): float
    {
        if ($fullMark <= 0) {
            return 0.0;
        }

        $percentage = ($obtained / $fullMark) * 100;

        return match (true) {
            $percentage >= 80 => 5.0,
            $percentage >= 70 => 4.0,
            $percentage >= 60 => 3.5,
            $percentage >= 50 => 3.0,
            $percentage >= 40 => 2.0,
            $percentage >= 33 => 1.0,
            default => 0.0,
        };
    }

    public function gradeLetterFromPoint(float $point): string
    {
        return match (true) {
            $point >= 5.0 => 'A+',
            $point >= 4.0 => 'A',
            $point >= 3.5 => 'A-',
            $point >= 3.0 => 'B',
            $point >= 2.0 => 'C',
            $point >= 1.0 => 'D',
            default => 'F',
        };
    }
}
