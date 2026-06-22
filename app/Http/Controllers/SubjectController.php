<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::withCount('evaluations')->get();
        return view('subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:subjects',
        ]);

        Subject::create($validated);
        return back()->with('success', 'Disciplina criada com sucesso.');
    }

    // Método especial para vincular disciplina a uma turma específica
    public function attachToClassroom(Request $request, Classroom $classroom)
    {
        $classroom->subjects()->attach($request->subject_id);
        return back()->with('success', 'Disciplina adicionada à grade da turma.');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->evaluations()->exists()) {
            return back()->with('error', 'Não é possível excluir uma disciplina que já possui avaliações.');
        }
        $subject->delete();
        return back()->with('success', 'Disciplina removida.');
    }
}
