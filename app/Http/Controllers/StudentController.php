<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $query = Student::query();

        // 1. Aplica restrição de turmas apenas para usuários que NÃO são administradores
        if (!$user->isAdmin()) {
            // IDs das turmas vinculadas ao professor logado
            $teacherClassroomIds = $user->classrooms()->pluck('classrooms.id')->toArray();

            // Restringe a busca para trazer apenas alunos dessas turmas
            $query->whereHas('classrooms', function (Builder $q) use ($teacherClassroomIds) {
                $q->whereIn('classrooms.id', $teacherClassroomIds);
            });

            // Carrega apenas as turmas do professor para o select da view
            $classrooms = Classroom::whereIn('id', $teacherClassroomIds)
                ->orderBy('name')
                ->get();
        } else {
            // Se for Administrador, carrega todas as turmas para o filtro
            $classrooms = Classroom::orderBy('name')->get();
        }

        // 2. Filtro de Texto Inteligente (Nome sem preposições ou Matrícula)
        if ($request->filled('search')) {
            $rawSearch = trim($request->search);
            $stopWords = ['de', 'da', 'do', 'dos', 'das', 'e'];

            // Converte em minúsculas e remove preposições
            $words = explode(' ', mb_strtolower($rawSearch));
            $keywords = array_filter($words, function ($word) use ($stopWords) {
                return !empty($word) && !in_array($word, $stopWords);
            });

            $query->where(function (Builder $mainQuery) use ($keywords, $rawSearch) {
                // Busca por Nome
                $mainQuery->where(function (Builder $nameQuery) use ($keywords, $rawSearch) {
                    if (!empty($keywords)) {
                        foreach ($keywords as $word) {
                            $nameQuery->where('name', 'like', "%{$word}%");
                        }
                    } else {
                        $nameQuery->where('name', 'like', "%{$rawSearch}%");
                    }
                })
                    // Busca por Matrícula
                    ->orWhere('registration_number', 'like', "%{$rawSearch}%");
            });
        }

        // 3. Filtro por Turma específica selecionada no formulário
        if ($request->filled('classroom_id')) {
            if ($user->isAdmin() || (isset($teacherClassroomIds) && in_array($request->classroom_id, $teacherClassroomIds))) {
                $query->whereHas('classrooms', function (Builder $q) use ($request) {
                    $q->where('classrooms.id', $request->classroom_id);
                });
            }
        }

        // 4. Paginação dos resultados mantendo a ordenação
        $students = $query->orderBy('name')->paginate(10);

        return view('students.index', compact('students', 'classrooms'));
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
