<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\SchoolSetting;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Lista todas as turmas (Onde o professor começa)
     */
    public function index()
    {
        $classrooms = Classroom::withCount('students')->get();

        $allSubjects = Subject::all();

        $settings = SchoolSetting::first();
    
        return view('classrooms.index', compact('classrooms', 'allSubjects', 'settings'));
    }

    /**
     * Exibe os detalhes de uma turma específica
     */
    public function show(Request $request, $id)
    {
        // 1. Busca a turma com os alunos e as disciplinas já vinculadas a ela
        $classroom = Classroom::with(['students', 'subjects'])->findOrFail($id);

        // 2. BUSCA TODAS AS DISCIPLINAS (Isso corrige o erro da variável indefinida!)
        $allSubjects = Subject::orderBy('name')->get();

        // 3. Busca as configurações para saber o bimestre ativo (ajuste conforme seu projeto)
        $settings = SchoolSetting::first();

        // 4. Captura o bimestre selecionado no filtro (padrão é o ativo ou 1)
        $selectedBimester = $request->get('bimester', $settings?->active_bimester ?? 1);

        $classrooms = Classroom::with(['students', 'subjects'])->get(); // Para o modal de matrícula, se necessário
       
        $students = Student::orderBy('name')->get(); // Para o modal de matrícula

        // 5. Retorna a view passando TODAS as variáveis necessárias pelo compact
        return view('classrooms.show', compact(
            'classroom',
            'allSubjects', // <-- ENVIANDO PARA A VIEW AQUI
            'settings',
            'selectedBimester',
            'classrooms', // Para o modal de matrícula
            'students'    // Para o modal de matrícula
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
