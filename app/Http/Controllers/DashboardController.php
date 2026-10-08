<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Evaluation;
use App\Models\Occurrence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->isAdmin()) {
            // 1. Totalizadores Globais
            $totalStudents = Student::count();
            $averageScore = Grade::avg('score') ?? 0;
            $globalConcept = $this->calculateConcept($averageScore);

            // 2. Acompanhamento do Lançamento de Notas por Avaliação/Professor
            // Traz as avaliações com contagem de notas já lançadas e total de alunos da turma
            $evaluationsProgress = Evaluation::with(['classroom.students', 'subject', 'user']) // 'user' assume ser o professor responsável
                ->withCount('grades')
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($evaluation) {
                    $totalStudents = $evaluation->classroom->students_count
                        ?? $evaluation->classroom->students()->count();

                    $gradesCount = $evaluation->grades_count;

                    $percentage = $totalStudents > 0
                        ? round(($gradesCount / $totalStudents) * 100)
                        : 0;

                    return [
                        'id'              => $evaluation->id,
                        'title'           => $evaluation->title,
                        'classroom'       => $evaluation->classroom->name ?? 'N/A',
                        'subject'         => $evaluation->subject->name ?? 'N/A',
                        'teacher_name'    => $evaluation->user->name ?? 'Não atribuído',
                        'grades_count'    => $gradesCount,
                        'total_students'  => $totalStudents,
                        'percentage'      => min($percentage, 100),
                        'is_completed'    => $percentage >= 100,
                        'created_at'      => $evaluation->created_at,
                    ];
                });

            // 3. Resumo de Pendências Globais
            $totalEvaluations = Evaluation::count();
            $completedEvaluations = $evaluationsProgress->where('is_completed', true)->count();
            $pendingEvaluations = $totalEvaluations - $completedEvaluations;

            // 4. Registros Recentes (Global)
            $recentGrades = Grade::with(['student', 'evaluation.subject'])
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
                'recentOccurrences'
            ));
        }

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
