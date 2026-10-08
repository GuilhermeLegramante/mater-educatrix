<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Evaluation;
use App\Models\Subject;
use App\Models\Occurrence;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->isAdmin()) {
            // 1. Indicadores Globais
            $totalStudents = Student::count();
            $averageScore = Grade::avg('score') ?? 0;
            $globalConcept = $this->calculateConcept($averageScore);

            // 2. Dados para os Filtros
            $classrooms = Classroom::orderBy('name')->get();
            $subjects = Subject::orderBy('name')->get();

            // 3. Query Base de Avaliações com Filtros Aplicados
            $evaluationsQuery = Evaluation::with([
                'classroom' => function ($query) {
                    $query->withCount('students');
                },
                'subject'
            ])
                ->withCount('grades');

            // Filtro por Bimestre
            if ($request->filled('bimester')) {
                $evaluationsQuery->where('bimester', $request->input('bimester'));
            }

            // Filtro por Turma
            if ($request->filled('classroom_id')) {
                $evaluationsQuery->where('classroom_id', $request->input('classroom_id'));
            }

            // Filtro por Disciplina
            if ($request->filled('subject_id')) {
                $evaluationsQuery->where('subject_id', $request->input('subject_id'));
            }

            // 4. Paginação com preservação dos parâmetros de busca
            $evaluationsProgress = $evaluationsQuery
                ->latest()
                ->paginate(10)
                ->withQueryString();

            // Transforma os itens da página atual mantendo a estrutura do objeto de Paginação
            $evaluationsProgress->through(function ($evaluation) {
                $totalStudents = $evaluation->classroom->students_count ?? 0;
                $gradesCount = $evaluation->grades_count;

                $percentage = $totalStudents > 0
                    ? round(($gradesCount / $totalStudents) * 100)
                    : 0;

                return [
                    'id'             => $evaluation->id,
                    'title'          => $evaluation->title ?? 'Sem título',
                    'bimester'       => $evaluation->bimester ?? '-',
                    'classroom'      => $evaluation->classroom->name ?? 'N/A',
                    'subject'        => $evaluation->subject->name ?? 'N/A',
                    'grades_count'   => $gradesCount,
                    'total_students' => $totalStudents,
                    'percentage'     => min($percentage, 100),
                    'is_completed'   => $percentage >= 100 && $totalStudents > 0,
                    'created_at'     => $evaluation->created_at,
                ];
            });

            // Indicadores de Pendências
            $totalEvaluations = Evaluation::count();
            $pendingEvaluations = Evaluation::whereHas('classroom.students')
                ->get()
                ->filter(function ($eval) {
                    $total = $eval->classroom->students()->count();
                    $grades = $eval->grades()->count();
                    return $total == 0 || $grades < $total;
                })
                ->count();

            // 5. Registros Recentes
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
                'classrooms',
                'subjects',
                'totalEvaluations',
                'pendingEvaluations',
                'recentGrades',
                'recentEvaluations',
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
