<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('name')->paginate(15);
        return view('students.index', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:students',
        ]);

        Student::create($validated);
        return back()->with('success', 'Aluno cadastrado com sucesso.');
    }

    public function show(Student $student)
    {
        // Pegamos a turma mais recente (ativa) do aluno
        $activeClassroom = $student->classrooms()->latest()->first();
        $student->load(['evaluations.subject', 'preceptoryReports.subject']);

        return view('students.show', compact('student', 'activeClassroom'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:students,registration_number,' . $student->id,
        ]);

        $student->update($validated);
        return back()->with('success', 'Dados do aluno atualizados.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Registro removido.');
    }
}
