<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. KPIs Básicos e Avançados (Dados reais + Simulações estratégicas para MVP)
        $totalStudents = Student::count() ?: 124;
        $totalClassrooms = Classroom::count() ?: 6;

        // Média Global Convertida em Conceito Clássico
        $averageScore = Grade::avg('score') ?? 78.5;
        $globalConcept = $this->determineConcept($averageScore);

        // 2. Últimos lançamentos reais para a tabela
        $recentGrades = Grade::with(['student', 'evaluation.subject'])
            ->latest()
            ->take(6)
            ->get();

        // 3. MÓDULO MVP: Insights para Gestão (Dados Simulados Estruturados)

        // Distribuição de Conceitos (Gráfico de Pizza/Donut)
        $conceptDistribution = [
            'A (Excelente)' => 35,
            'B (Bom)'       => 45,
            'C (Suficiente)' => 14,
            'D (Em Foco)'   => 6
        ];

        // Desempenho por Fase do Trivium (Gráfico de Barras)
        $triviumPerformance = [
            'Gramática' => ['score' => 84, 'color' => 'bg-navy-900'],
            'Lógica'    => ['score' => 76, 'color' => 'bg-gold-500'],
            'Retórica'  => ['score' => 89, 'color' => 'bg-slate-700'],
        ];

        // Índice de Desenvolvimento de Virtudes (Phronesis)
        $virtuesDevelopment = [
            ['name' => 'Prudência', 'status' => 'Avançado', 'trend' => 'up', 'percentage' => 88],
            ['name' => 'Justiça', 'status' => 'Proficiente', 'trend' => 'up', 'percentage' => 75],
            ['name' => 'Fortaleza', 'status' => 'Avançado', 'trend' => 'stable', 'percentage' => 82],
            ['name' => 'Temperança', 'status' => 'Em Desenvolvimento', 'trend' => 'up', 'percentage' => 61],
        ];

        return view('dashboard.index', compact(
            'totalStudents',
            'totalClassrooms',
            'globalConcept',
            'averageScore',
            'recentGrades',
            'conceptDistribution',
            'triviumPerformance',
            'virtuesDevelopment'
        ));
    }

    private function determineConcept($score)
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        return 'D';
    }
}
