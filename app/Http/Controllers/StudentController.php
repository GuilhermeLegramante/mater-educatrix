<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('name')->get();
        return view('students.index', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:students',
            'birth_date' => 'required|date',
        ]);

        Student::create($validated);
        return back()->with('success', 'Aluno cadastrado com sucesso.');
    }

    public function show(Request $request, Student $student)
    {
        $activeClassroom = $student->classrooms()
            ->wherePivot('status', 'active')
            ->latest('year')
            ->first();

        // Configuração do sistema
        $settings = \App\Models\SchoolSetting::first();

        // Usa o bimestre selecionado na URL
        // ou o bimestre atual configurado no sistema
        $bimester = $request->get(
            'bimester',
            $settings?->active_bimester ?? 1
        );

        $subjectId = $request->get('subject');

        if ($activeClassroom) {

            $student->load([
                'grades.evaluation.subject',
                'preceptoryReports.subject',
            ]);

            $gradesQuery = $student->grades()
                ->whereHas('evaluation', function ($q) use (
                    $activeClassroom,
                    $bimester,
                    $subjectId
                ) {

                    $q->where('classroom_id', $activeClassroom->id)
                        ->where('bimester', $bimester);

                    if ($subjectId) {
                        $q->where('subject_id', $subjectId);
                    }
                });

            $grades = $gradesQuery
                ->with('evaluation.subject')
                ->get();

            $reports = $student->preceptoryReports()
                ->when($subjectId, function ($q) use ($subjectId) {
                    $q->where('subject_id', $subjectId);
                })
                ->where('bimester', $bimester)
                ->with('subject')
                ->latest()
                ->get();
        } else {

            $grades = collect();
            $reports = collect();
        }

        $occurrenceTypes = \App\Models\OccurrenceType::all();

        return view('students.show', compact(
            'student',
            'activeClassroom',
            'grades',
            'reports',
            'bimester',
            'subjectId',
            'occurrenceTypes'
        ));
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Registro removido.');
    }

    public function edit(Student $student)
    {
        $students = Student::latest()->get();

        return view('students.index', compact('students', 'student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required',
            'registration_number' => 'required',
            'birth_date' => 'nullable|date',
        ]);

        $student->update($request->only([
            'name',
            'registration_number',
            'birth_date'
        ]));

        return redirect()
            ->route('students.index')
            ->with('success', 'Aluno atualizado com sucesso!');
    }
}
