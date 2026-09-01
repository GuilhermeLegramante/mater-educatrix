<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\DescriptiveQuestion;
use App\Models\DescriptiveRating;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ReportCardController extends Controller
{
    public function generatePDF(Request $request, Classroom $classroom, Student $student)
    {
        $settings = \App\Models\SchoolSetting::first();
        $relationsToLoad = [];

        // 1. Carregamento de Notas e Faltas
        if ($request->has('include_grades')) {
            $relationsToLoad['grades.evaluation'] = function ($query) use ($classroom) {
                $query->where('classroom_id', $classroom->id);
            };
            $relationsToLoad['bimesterResults'] = function ($query) use ($classroom) {
                $query->where('classroom_id', $classroom->id);
            };

            $relationsToLoad['attendances'] = function ($query) use ($classroom) {
                $query->where('is_absent', true)
                    ->whereHas('schoolDay', function ($q) use ($classroom) {
                        $q->where('classroom_id', $classroom->id);
                    })->with('schoolDay');
            };
        }

        // 2. Relatos de Preceptoria
        if ($request->has('include_preceptory')) {
            $relationsToLoad['preceptoryReports'] = function ($query) use ($classroom) {
                $query->where('classroom_id', $classroom->id)
                    ->with('subject');
            };
        }

        // 3. Ocorrências e Atendimentos do Estudante
        if ($request->has('include_occurrences')) {
            $relationsToLoad['occurrences'] = function ($query) {
                $query->with(['type', 'user'])->orderBy('date', 'desc');
            };
        }

        // Executa Lazy Loading otimizado
        if (!empty($relationsToLoad)) {
            $student->load($relationsToLoad);
        }

        // 4. Carregamento da AVALIAÇÃO DESCRITIVA (Matriz de Perguntas e Respostas)
        $descriptiveData = null;
        if ($request->has('include_descriptive_evaluation')) {
            // Busca perguntas ativas agrupadas por disciplina
            $questions = DescriptiveQuestion::get();

            // Busca as respostas gravadas para este aluno no ano da turma
            $evaluations = DescriptiveRating::where('student_id', $student->id)
                ->where('year', $classroom->year)
                ->get()
                ->keyBy(function ($item) {
                    return $item->descriptive_question_id . '_' . $item->bimester;
                });

            $descriptiveData = [
                'questions'   => $questions->groupBy('subject_id'),
                'evaluations' => $evaluations
            ];
        }

        // Helper de Contagem de Faltas
        $getAbsencesCount = function ($subjectId, $bimester) use ($student, $settings) {
            if (!$settings) return 0;
            $period = $settings->getBimesterPeriod($bimester);
            if (!$period['start'] || !$period['end']) return 0;

            return $student->attendances
                ->where('subject_id', $subjectId)
                ->filter(function ($attendance) use ($period) {
                    return $attendance->schoolDay->date->between($period['start'], $period['end']);
                })
                ->sum('quantity');
        };

        $data = [
            'classroom'                 => $classroom,
            'student'                   => $student,
            'subjects'                  => $classroom->subjects,
            'date'                      => now()->format('d/m/Y'),
            'settings'                  => $settings,
            'getAbsencesCount'          => $getAbsencesCount,
            'showGrades'                => $request->has('include_grades'),
            'showPreceptory'            => $request->has('include_preceptory'),
            'showOccurrences'           => $request->has('include_occurrences'),
            'showDescriptiveEvaluation' => $request->has('include_descriptive_evaluation'),
            'descriptiveData'           => $descriptiveData,
        ];

        $pdf = Pdf::loadView('pdf.report-card', $data)->setPaper('a4', 'portrait');
        return $pdf->stream("boletim_{$student->name}_{$classroom->name}.pdf");
    }
}
