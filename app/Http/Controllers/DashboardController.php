<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Evaluation;
use App\Models\Occurrence;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->isAdmin()) {
            // 1. Indicadores Globais
            $totalStudents = Student::count();
            $averageScore = Grade::avg('score') ?? 0;
            $globalConcept = $this->calculateConcept($averageScore);

            // 2. Acompanhamento do Lançamento de Notas por Avaliação
            // Traz a turma com a contagem de alunos vinculados e a contagem de notas lançadas
            $evaluationsProgress = Evaluation::with(['classroom' => function ($query) {
                $query->withCount('students');
            }, 'subject'])
                ->withCount('grades')
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($evaluation) {
                    // Obtém a quantidade total de alunos matriculados na turma da avaliação
                    $totalStudents = $evaluation->classroom->students_count ?? 0;

                    $gradesCount = $evaluation->grades_count;

                    $percentage = $totalStudents > 0
                        ? round(($gradesCount / $totalStudents) * 100)
                        : 0;

                    return [
                        'id'             => $evaluation->id,
                        'title'          => $evaluation->title ?? 'Sem título',
                        'classroom'      => $evaluation->classroom->name ?? 'N/A',
                        'subject'        => $evaluation->subject->name ?? 'N/A',
                        'grades_count'   => $gradesCount,
                        'total_students' => $totalStudents,
                        'percentage'     => min($percentage, 100),
                        'is_completed'   => $percentage >= 100 && $totalStudents > 0,
                        'created_at'     => $evaluation->created_at,
                    ];
                });

            // Métrica de Pendências Globais
            $totalEvaluations = Evaluation::count();
            $completedEvaluations = $evaluationsProgress->where('is_completed', true)->count();
            $pendingEvaluations = $totalEvaluations - $completedEvaluations;

            // 3. Registros Recentes (Global)
            $recentGrades = Grade::with(['student', 'evaluation.subject'])
                ->latest()
                ->take(5)
                ->get();

            $recentEvaluations = Evaluation::with(['classroom', 'subject'])
                ->latest()
                ->take(5)
                ->get();

            $recentOccurrences = Occurrence::with('student')
                ->latest()
                ->take(5)
                ->get();

            return view('dashboard.index', compact(
                'totalStudents',
                'averageScore',
                'globalConcept',
                'evaluationsProgress',
                'totalEvaluations',
                'pendingEvaluations',
                'recentGrades',
                'recentEvaluations',
                'recentOccurrences',
            ));
        }

        // Visão do Professor / Usuário Padrão
        return view('dashboard.index');
    }

    private function calculateConcept(float $score): string
    {
        return match (true) {
            $score >= 90 => 'A',
            $score >= 75 => 'B',
            $score >= 60 => 'C',
            default      => 'D',
        };
    }
}
