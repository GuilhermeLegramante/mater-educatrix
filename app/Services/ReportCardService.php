<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\DescriptiveQuestion;
use App\Models\DescriptiveRating;
use App\Models\Student;
use Illuminate\Http\Request;

class ReportCardService
{
    public function loadRelations(Student $student, Classroom $classroom, Request $request): void
    {
        $relationsToLoad = [];

        if ($request->has('include_grades')) {
            $relationsToLoad['grades.evaluation'] = fn($q) => $q->where('classroom_id', $classroom->id);
            $relationsToLoad['bimesterResults'] = fn($q) => $q->where('classroom_id', $classroom->id);
            $relationsToLoad['attendances'] = fn($q) => $q->where('is_absent', true)
                ->whereHas('schoolDay', fn($sq) => $sq->where('classroom_id', $classroom->id))
                ->with('schoolDay');
        }

        if ($request->has('include_preceptory')) {
            $relationsToLoad['preceptoryReports'] = fn($q) => $q->where('classroom_id', $classroom->id)->with('subject');
        }

        if ($request->has('include_occurrences')) {
            $relationsToLoad['occurrences'] = fn($q) => $q->with(['type', 'user'])->orderBy('date', 'desc');
        }

        if (!empty($relationsToLoad)) {
            $student->load($relationsToLoad);
        }
    }

    public function getPayload(Classroom $classroom, Student $student, Request $request, $settings, ?array $descriptiveData = null, ?string $date = null): array
    {
        $getAbsencesCount = function ($subjectId, $bimester) use ($student, $settings) {
            if (!$settings) return 0;
            $period = $settings->getBimesterPeriod($bimester);
            if (!$period['start'] || !$period['end']) return 0;

            return $student->attendances
                ->where('subject_id', $subjectId)
                ->filter(fn($attendance) => $attendance->schoolDay->date->between($period['start'], $period['end']))
                ->sum('quantity');
        };

        return [
            'classroom'                 => $classroom,
            'student'                   => $student,
            'subjects'                  => $classroom->subjects,
            'date'                      => $date ?? now()->format('d/m/Y'),
            'settings'                  => $settings,
            'getAbsencesCount'          => $getAbsencesCount,
            'showGrades'                => $request->has('include_grades'),
            'showPreceptory'            => $request->has('include_preceptory'),
            'showOccurrences'           => $request->has('include_occurrences'),
            'showDescriptiveEvaluation' => $request->has('include_descriptive_evaluation'),
            'descriptiveData'           => $descriptiveData,
            'tutor'                     => $this->resolveTutor($classroom),
        ];
    }

    public function getDescriptiveEvaluationData(Student $student, Classroom $classroom): array
    {
        $evaluations = DescriptiveRating::where('student_id', $student->id)
            ->where('year', $classroom->year)
            ->get()
            ->keyBy(fn($item) => $item->descriptive_question_id . '_' . $item->bimester);

        return [
            'questions'   => DescriptiveQuestion::get()->groupBy('subject_id'),
            'evaluations' => $evaluations
        ];
    }

    private function resolveTutor(Classroom $classroom): string
    {
        $tutors = [
            '1º Ano' => 'elisa',
            '2º Ano' => 'simone',
            '3º Ano' => 'maiara',
            '4º Ano' => 'rafaella',
            '5º Ano' => 'graziele',
            '6º Ano' => 'joelson',
            '7º Ano' => 'luma',
            '8º Ano' => 'pablo',
        ];

        return $tutors[$classroom->name] ?? '';
    }
}
