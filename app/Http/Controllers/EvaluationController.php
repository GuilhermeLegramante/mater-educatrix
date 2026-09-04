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
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Inicia a consulta com os relacionamentos pré-carregados
        $query = Evaluation::with(['subject', 'classroom']);

        // Se o usuário NÃO for administrador, filtra pelas suas turmas
        if (!$user->isAdmin()) {
            // Obtém os IDs das turmas associadas ao professor logado
            $teacherClassroomIds = $user->classrooms()->pluck('classrooms.id')->toArray();

            // Filtra as avaliações que pertencem às turmas do professor
            $query->whereIn('classroom_id', $teacherClassroomIds);
        }

        // Ordena pelas mais recentes e aplica a paginação com 10 itens por página
        $evaluations = $query->latest()->paginate(10);

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
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // 1. Filtragem de Turmas e Disciplinas com base no perfil do usuário
        if (!$user->isAdmin()) {
            // Professor: Carrega apenas suas turmas e disciplinas vinculadas
            $classrooms = $user->classrooms()->orderBy('name')->get();
            $subjects = $user->subjects()->orderBy('name')->get();
        } else {
            // Administrador: Carrega todas as turmas e disciplinas cadastradas
            $classrooms = Classroom::orderBy('name')->get();
            $subjects = Subject::orderBy('name')->get();
        }

        // 2. Busca a turma caso venha um ID na URL (ex: acessado a partir da página da turma)
        $classroom = null;
        if ($request->filled('classroom_id')) {
            // Garante que a turma solicitada esteja entre as turmas permitidas para o usuário
            $classroom = $classrooms->firstWhere('id', $request->classroom_id);
        }

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

    /**
     * Exibe o formulário de edição de uma avaliação existente.
     */
    public function edit(Evaluation $evaluation)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Garante que o professor não edite uma avaliação de turma à qual não tem acesso
        if (!$user->isAdmin()) {
            $teacherClassroomIds = $user->classrooms()->pluck('classrooms.id')->toArray();
            if (!in_array($evaluation->classroom_id, $teacherClassroomIds)) {
                abort(403, 'Acesso não autorizado para editar esta avaliação.');
            }

            $classrooms = $user->classrooms()->orderBy('name')->get();
            $subjects = $user->subjects()->orderBy('name')->get();
        } else {
            $classrooms = Classroom::orderBy('name')->get();
            $subjects = Subject::orderBy('name')->get();
        }

        return view('evaluations.edit', compact('evaluation', 'classrooms', 'subjects'));
    }

    /**
     * Atualiza os dados da avaliação no banco de dados.
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Validação de acesso
        if (!$user->isAdmin()) {
            $teacherClassroomIds = $user->classrooms()->pluck('classrooms.id')->toArray();
            if (!in_array($evaluation->classroom_id, $teacherClassroomIds)) {
                abort(403, 'Acesso não autorizado para atualizar esta avaliação.');
            }
        }

        // Validação dos dados informados
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id'   => 'required|exists:subjects,id',
            'title'        => 'required|string|max:255',
            'bimester'     => 'required|integer|between:1,4',
            'max_score'    => 'required|integer|min:1',
            'weight'       => 'required|numeric|min:0',
        ]);

        // Atualização do registro
        $evaluation->update($validated);

        return redirect()->route('evaluations.index')
            ->with('success', 'Avaliação atualizada com sucesso!');
    }

    /**
     * Remove a avaliação (e suas notas associadas se não houver CASCADE na migration).
     */
    public function destroy(Evaluation $evaluation)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Validação de acesso
        if (!$user->isAdmin()) {
            $teacherClassroomIds = $user->classrooms()->pluck('classrooms.id')->toArray();
            if (!in_array($evaluation->classroom_id, $teacherClassroomIds)) {
                abort(403, 'Acesso não autorizado para excluir esta avaliação.');
            }
        }

        // Deleta as notas vinculadas antes de excluir a avaliação (garantia de integridade)
        $evaluation->grades()->delete();
        $evaluation->delete();

        return redirect()->route('evaluations.index')
            ->with('success', 'Avaliação excluída com sucesso!');
    }
}
