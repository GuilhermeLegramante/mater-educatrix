<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Evaluation;
use App\Models\Occurrence;
use Illuminate\Http\Request;
use Illuminate\Support;

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

            // 2. Registros Recentes (Global)
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

            return view('index', compact(
                'totalStudents',
                'averageScore',
                'globalConcept',
                'recentGrades',
                'recentEvaluations',
                'recentOccurrences'
            ));
        }

        // Caso não seja Admin (Professor/Comum)
        return view('index');
    }

    /**
     * Auxiliar para converter média numérica em Conceito (A, B, C, D)
     */
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
