<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\SchoolSetting;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Lista as turmas e disciplinas de acordo com as permissões do usuário logado.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Checa se o perfil atual é de professor
        $isTeacher = ($user->role === UserRole::TEACHER || $user->role === 'teacher');

        // 1. Filtragem de Turmas
        if ($isTeacher) {
            // Traz apenas as turmas vinculadas ao professor
            $classrooms = $user->classrooms()->withCount('students')->get();
        } else {
            // Traz todas as turmas para admin ou acessos gerais
            $classrooms = Classroom::withCount('students')->get();
        }

        // 2. Filtragem de Disciplinas do Usuário
        if ($isTeacher) {
            // Traz apenas as disciplinas vinculadas a este professor na tabela pivô
            $allSubjects = $user->subjects()->get();
        } else {
            // Traz todas as disciplinas cadastradas no sistema
            $allSubjects = Subject::all();
        }

        // 3. Configurações Globais
        $settings = SchoolSetting::first();

        return view('classrooms.index', compact('classrooms', 'allSubjects', 'settings'));
    }

    /**
     * Exibe os detalhes de uma turma específica com o resumo de conceitos e filtros
     */
    public function show(Request $request, $id)
    {
        // 1. Busca as configurações globais para identificar o bimestre ativo
        $settings = SchoolSetting::first();

        // 2. Captura o bimestre selecionado no filtro da URL (padrão é o ativo ou 1)
        $selectedBimester = (int) $request->get('bimester', $settings?->active_bimester ?? 1);

        // 3. Busca a turma carregando os alunos com seus resultados e notas (Eager Loading)
        $classroom = Classroom::with([
            'subjects',
            'students.bimesterResults',
            'students.grades.evaluation'
        ])->findOrFail($id);

        // 4. Busca todas as disciplinas ordenadas para o modal de grade curricular
        $allSubjects = Subject::orderBy('name')->get();

        // 5. Dados de apoio para os modais de matrícula e transferência
        $classrooms = Classroom::orderBy('name')->get();
        $students   = Student::orderBy('name')->get();

        // 6. Retorna a view com todas as variáveis necessárias
        return view('classrooms.show', compact(
            'classroom',
            'allSubjects',
            'settings',
            'selectedBimester',
            'classrooms',
            'students'
        ));
    }

    // Vincular Disciplinas à Turma
    public function syncSubjects(Request $request, Classroom $classroom)
    {
        $classroom->subjects()->sync($request->subjects); // Boas práticas: sync remove o que não foi marcado
        return back()->with('success', 'Grade curricular atualizada!');
    }

    /**
     * Salva uma nova turma no banco
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'year' => 'required|integer',
            'subjects' => 'required|array' // Disciplinas da grade
        ]);

        $classroom = Classroom::create($data);
        $classroom->subjects()->attach($request->subjects);

        return back()->with('success', 'Turma e Grade Curricular criadas!');
    }

    /**
     * Realiza a matrícula (Link entre Aluno e Turma)
     */
    public function enroll(Request $request, Classroom $classroom)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
        ]);

        $classroom->students()->syncWithoutDetaching([
            $request->student_id => ['status' => 'active']
        ]);

        return back()->with('success', 'Aluno matriculado com sucesso!');
    }

    /**
     * Remove a vinculação de um aluno com a turma (Desmatrícula).
     */
    public function unenroll(Classroom $classroom, Student $student)
    {
        // 1. Remove a associação entre o aluno e a turma na tabela pivô
        $classroom->students()->detach($student->id);

        // 2. Redireciona de volta para a tela da turma com mensagem de sucesso
        return redirect()
            ->route('classrooms.show', $classroom)
            ->with('success', 'Aluno desmatriculado da turma com sucesso!');
    }

    /**
     * Adiciona uma disciplina à grade daquela turma
     */
    public function attachSubject(Request $request, Classroom $classroom)
    {
        $request->validate(['subject_id' => 'required|exists:subjects,id']);

        if (!$classroom->subjects()->where('subject_id', $request->subject_id)->exists()) {
            $classroom->subjects()->attach($request->subject_id);
        }

        return back()->with('success', 'Disciplina adicionada à grade da turma!');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->route('classrooms.index')->with('success', 'Turma removida.');
    }

    public function updateConcept(Request $request, Classroom $classroom)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id', // Validando a disciplina
            'bimester'   => 'required|integer|min:1|max:4',
            'concept'    => 'required|string|max:2',
        ]);

        \App\Models\BimesterResult::updateOrCreate(
            [
                'classroom_id' => $classroom->id,
                'student_id'   => $request->student_id,
                'subject_id'   => $request->subject_id, // Chave única composta
                'bimester'     => $request->bimester,
            ],
            [
                'concept'      => $request->concept,
                'teacher_note' => $request->teacher_note,
            ]
        );

        return back()->with('success', 'Conceito da disciplina atualizado!');
    }

    public function updateCurriculum(Request $request, Classroom $classroom)
    {
        $syncData = [];

        // Verifica se vieram disciplinas do formulário do modal
        if ($request->has('subjects')) {
            foreach ($request->input('subjects') as $subjectId => $data) {
                // Se o usuário digitou uma carga horária válida (maior que zero), preparamos para salvar
                if (!empty($data['workload']) && $data['workload'] > 0) {
                    $syncData[$subjectId] = [
                        'workload' => (int) $data['workload']
                    ];
                }
            }
        }

        // O método sync() remove as matérias que ficaram com carga horária vazia/zero
        // e insere ou atualiza as que possuem carga horária na tabela pivô
        $classroom->subjects()->sync($syncData);

        // Redireciona de volta para a página da turma com uma mensagem de sucesso
        return redirect()->route('classrooms.show', $classroom->id)
            ->with('success', 'Grade curricular atualizada com sucesso!');
    }
}
