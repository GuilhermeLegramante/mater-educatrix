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
        $students = $classroom->students->sortBy('name');

        return view('grades.create', compact('classroom', 'evaluation', 'students'));
    }

    // No GradeController.php
    public function edit(int $evaluationId)
    {
        $evaluation = Evaluation::with(['subject.students.grades' => function ($query) use ($evaluationId) {
            $query->where('evaluation_id', $evaluationId);
        }])->findOrFail($evaluationId);

        $students = $evaluation->subject->students->sortBy('name');

        return view('grades.edit', compact('evaluation', 'students'));
    }

    /**
     * Importante: Receber Classroom antes de Evaluation para bater com a rota
     */
    public function store(Request $request, Classroom $classroom, Evaluation $evaluation)
    {
        // Filtra o array para remover os inputs que vieram vazios
        $scores = array_filter($request->input('scores', []), function ($value) {
            return $value !== null && $value !== '';
        });

        // Valida apenas os scores preenchidos
        $request->merge(['scores' => $scores]);

        $request->validate([
            'scores'   => 'nullable|array',
            'scores.*' => 'numeric|between:0,' . $evaluation->max_score,
        ]);

        // Se nenhum campo foi preenchido, você pode optar por retornar um aviso ou salvar vazio
        $this->academicService->saveGrades($evaluation->id, $scores);

        return redirect()->route('evaluations.show', $evaluation->id)
            ->with('success', 'Notas processadas com sucesso!');
    }
}
