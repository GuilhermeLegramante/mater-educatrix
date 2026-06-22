<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
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

        // Se o gestor quiser notas, carregamos também a contagem agregada de FALTAS por segurança
        if ($request->has('include_grades')) {
            $relationsToLoad['grades.evaluation'] = function ($query) use ($classroom) {
                $query->where('classroom_id', $classroom->id);
            };
            $relationsToLoad['bimesterResults'] = function ($query) use ($classroom) {
                $query->where('classroom_id', $classroom->id);
            };

            // CARREGAMENTO OTIMIZADO: Traz todas as faltas do aluno nesta turma de uma vez só
            $relationsToLoad['attendances'] = function ($query) use ($classroom) {
                $query->where('is_absent', true)
                    ->whereHas('schoolDay', function ($q) use ($classroom) {
                        $q->where('classroom_id', $classroom->id);
                    })->with('schoolDay'); // Carrega o dia junto para termos a data na memória
            };
        }

        // Relatos de Preceptoria
        if ($request->has('include_preceptory')) {
            $relationsToLoad['preceptoryReports'] = function ($query) use ($classroom) {
                $query->where('classroom_id', $classroom->id)
                    ->with('subject');
            };
        }

        // Ocorrências e Atendimentos do Estudante
        if ($request->has('include_occurrences')) {
            $relationsToLoad['occurrences'] = function ($query) {
                $query->with(['type', 'user'])->orderBy('date', 'desc');
            };
        }

        // 2. Executa o Lazy Loading otimizado apenas se houver algo selecionado
        if (!empty($relationsToLoad)) {
            $student->load($relationsToLoad);
        }

        // Para evitar tocar no banco dentro do loop do Blade, criamos uma função anônima helper
        // que filtra a coleção que já está na memória do PHP. Alta performance pura!
        $getAbsencesCount = function ($subjectId, $bimester) use ($student, $settings) {
            if (!$settings) return 0;
            $period = $settings->getBimesterPeriod($bimester);
            if (!$period['start'] || !$period['end']) return 0;

            return $student->attendances
                ->where('subject_id', $subjectId)
                ->filter(function ($attendance) use ($period) {
                    return $attendance->schoolDay->date->between($period['start'], $period['end']);
                })
                ->sum('quantity'); // 👈 ALTERADO AQUI: Soma as faltas reais estocadas na coluna
        };

        $data = [
            'classroom'        => $classroom,
            'student'          => $student,
            'subjects'         => $classroom->subjects,
            'date'             => now()->format('d/m/Y'),
            'settings'         => $settings,
            'getAbsencesCount' => $getAbsencesCount, // Passamos a função para o Blade usar
            'showGrades'       => $request->has('include_grades'),
            'showPreceptory'   => $request->has('include_preceptory'),
            'showOccurrences'  => $request->has('include_occurrences'),
        ];

        $pdf = Pdf::loadView('pdf.report-card', $data)->setPaper('a4', 'portrait');
        return $pdf->stream("boletim_{$student->registration_number}.pdf");
    }
}
