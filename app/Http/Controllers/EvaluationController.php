<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        // Busca as avaliações com as relações para mostrar na tabela
        $evaluations = Evaluation::with(['subject', 'classroom'])->latest()->paginate(10);

        return view('evaluations.index', compact('evaluations'));
    }

    public function show(Evaluation $evaluation)
    {
        // Carrega as relações para exibir na tela de detalhes
        $evaluation->load(['classroom.students', 'subject', 'grades.student']);

        return view('evaluations.show', compact('evaluation'));
    }

    public function create(Request $request)
    {
        // Buscamos a turma caso venha um ID via URL (ex: de dentro da página da turma)
        // Caso contrário, deixamos nulo para o professor escolher no select
        $classroom = null;
        if ($request->has('classroom_id')) {
            $classroom = Classroom::find($request->classroom_id);
        }

        $classrooms = Classroom::all();
        $subjects = Subject::all();

        return view('evaluations.create', compact('classroom', 'classrooms', 'subjects'));
    }

    public function store(Request $request)
    {
        // 1. Validação dos dados
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id'   => 'required|exists:subjects,id',
            'title'        => 'required|string|max:255',
            'bimester'     => 'required|integer|between:1,4',
            'max_score'    => 'required|integer|min:1',
            'weight'       => 'required|numeric|min:0',
        ]);

        // 2. Criação da Avaliação
        $evaluation = Evaluation::create($validated);

        // 3. Redirecionamento Estratégico
        // Enviamos o professor direto para a planilha de notas da turma/avaliação recém-criada
        return redirect()->route('grades.create', [
            'classroom' => $evaluation->classroom_id,
            'evaluation' => $evaluation->id
        ])->with('success', 'Avaliação criada com sucesso! Agora, lance os scores dos alunos.');
    }
}
