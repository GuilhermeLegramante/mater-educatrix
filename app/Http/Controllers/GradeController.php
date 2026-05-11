<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Evaluation;
use App\Models\Grade;
use App\Services\AcademicService;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    protected AcademicService $academicService;

    public function __construct(AcademicService $academicService)
    {
        $this->academicService = $academicService;
    }

    /**
     *  Lista o histórico de notas lançadas
     */
    public function index()
    {
        $grades = Grade::with(['student', 'evaluation.subject'])
            ->latest()
            ->paginate(15);

        return view('grades.index', compact('grades'));
    }

    /**
     * Exibe o formulário de lançamento de notas para uma avaliação específica.
     */
    public function create(Classroom $classroom, Evaluation $evaluation)
    {
        // Carrega os alunos da turma para listar no formulário de notas
        $students = $classroom->students;

        return view('grades.create', compact('classroom', 'evaluation', 'students'));
    }

    // No GradeController.php
    public function edit(int $evaluationId)
    {
        $evaluation = Evaluation::with(['subject.students.grades' => function ($query) use ($evaluationId) {
            $query->where('evaluation_id', $evaluationId);
        }])->findOrFail($evaluationId);

        $students = $evaluation->subject->students;

        return view('grades.edit', compact('evaluation', 'students'));
    }

    /**
     * Importante: Receber Classroom antes de Evaluation para bater com a rota
     */
    public function store(Request $request, Classroom $classroom, Evaluation $evaluation)
    {
        // Ajuste o 'between' para aceitar até o max_score da avaliação dinâmica
        $request->validate([
            'scores'   => 'required|array',
            'scores.*' => 'required|numeric|between:0,' . $evaluation->max_score,
        ]);

        // Passamos o ID correto da avaliação
        $this->academicService->saveGrades($evaluation->id, $request->scores);

        // Redireciona para o 'show' da avaliação para ver o resultado
        return redirect()->route('evaluations.show', $evaluation->id)
            ->with('success', 'Scores processados com sucesso!');
    }
}
