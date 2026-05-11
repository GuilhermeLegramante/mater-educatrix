<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Evaluation;
use App\Models\Grade;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Dados Reais do Banco
        $totalStudents = Student::count();
        $totalEvaluations = Evaluation::count();

        // Pegamos os 5 últimos lançamentos de notas para a tabela
        $recentGrades = Grade::with(['student', 'evaluation.subject'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalStudents',
            'totalEvaluations',
            'recentGrades'
        ));
    }
}
