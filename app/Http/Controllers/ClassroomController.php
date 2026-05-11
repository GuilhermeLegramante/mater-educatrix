<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
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
        $allSubjects = Subject::all(); // Para o modal de criação

        return view('classrooms.index', compact('classrooms', 'allSubjects'));
    }

    /**
     * Exibe os detalhes de uma turma específica
     */
    public function show(Classroom $classroom)
    {
        $classroom->load(['students', 'subjects', 'evaluations.subject']);
        $availableStudents = Student::whereDoesntHave('classrooms', function ($q) use ($classroom) {
            $q->where('classroom_id', $classroom->id);
        })->get();

        return view('classrooms.show', compact('classroom', 'availableStudents'));
    }

    // Vincular Disciplinas à Turma
    public function syncSubjects(Request $request, Classroom $classroom)
    {
        $classroom->subjects()->sync($request->subjects); // Boas práticas: sync remove o que não foi marcado
        return back()->with('success', 'Grade curricular atualizada!');
    }

    // Matricular Aluno
    public function enrollStudent(Request $request, Classroom $classroom)
    {
        $classroom->students()->attach($request->student_id, ['status' => 'active']);
        return back()->with('success', 'Aluno matriculado com sucesso!');
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
        $request->validate(['student_id' => 'required|exists:students,id']);

        // Evita duplicidade de matrícula na mesma turma
        if (!$classroom->students()->where('student_id', $request->student_id)->exists()) {
            $classroom->students()->attach($request->student_id, ['status' => 'active']);
        }

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
}
