<?php

namespace App\Http\Controllers;

use App\Models\PreceptoryReport;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;

class PreceptoryController extends Controller
{
    /**
     * Exibe o formulário de relato para um aluno específico dentro de uma turma
     */
    public function create(Classroom $classroom, Student $student)
    {
        $subjects = $classroom->subjects;
        return view('preceptory.create', compact('classroom', 'student', 'subjects'));
    }

    public function store(Request $request, Classroom $classroom)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'content'    => 'required|string',
            'bimester'   => 'required|integer',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        // Criamos o registro vinculando à turma da URL
        $classroom->preceptoryReports()->create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'content'    => $request->content,
            'bimester'   => $request->bimester,
        ]);

        return back()->with('success', 'Anotação de preceptoria registrada com sucesso!');
    }

    public function show(PreceptoryReport $report)
    {
        return view('preceptory.show', compact('report'));
    }
}
