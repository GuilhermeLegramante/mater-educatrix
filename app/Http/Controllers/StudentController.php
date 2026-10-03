<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use App\Models\OccurrenceType;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $query = Student::query();

        // ---------------------------------------------------------------------
        // AJUSTE: Filtro de alunos vinculados às turmas do professor logado
        // ---------------------------------------------------------------------
        if (!$user->isAdmin()) {
            // Extrai apenas os IDs das turmas associadas ao professor
            $teacherClassroomIds = $user->classrooms()->pluck('classrooms.id')->toArray();

            // Garante que o banco trará APENAS alunos dessas turmas
            $query->whereHas('classrooms', function (Builder $q) use ($teacherClassroomIds) {
                $q->whereIn('classrooms.id', $teacherClassroomIds);
            });

            // Carrega no select da view apenas as turmas do professor
            $classrooms = Classroom::whereIn('id', $teacherClassroomIds)
                ->orderBy('name')
                ->get();
        } else {
            // Se for Admin, lista todas as turmas para o filtro da view
            $classrooms = Classroom::orderBy('name')->get();
        }
        // ---------------------------------------------------------------------

        // Filtro por texto (Nome ou Matrícula)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        // Filtro opcional por Turma selecionada no formulário
        if ($request->filled('classroom_id')) {
            // Garante que se o usuário for professor, ele só consiga filtrar turmas dele
            if ($user->isAdmin() || (isset($teacherClassroomIds) && in_array($request->classroom_id, $teacherClassroomIds))) {
                $query->whereHas('classrooms', function (Builder $q) use ($request) {
                    $q->where('classrooms.id', $request->classroom_id);
                });
            }
        }

        // Paginação com 10 alunos por página mantendo os parâmetros na URL
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
        $user = auth()->user();

        // 1. Busca a turma ativa do aluno com as disciplinas filtradas pelo professor (se não for admin)
        $activeClassroom = $student->classrooms()
            ->wherePivot('status', 'active')
            ->latest('year')
            ->first();

        // Configurações do sistema para definir o bimestre
        $settings = SchoolSetting::first();

        $bimester = $request->get(
            'bimester',
            $settings?->active_bimester ?? 1
        );

        $subjectId = $request->get('subject');

        $subjects = collect();

        if ($activeClassroom) {

            // 2. Busca das Disciplinas vinculadas à Turma e ao Professor
            $subjectsQuery = $activeClassroom->subjects();

            // Se o usuário não for Admin, filtra apenas as disciplinas onde ele está associado
            if (!$user->isAdmin()) {
                $subjectsQuery->whereHas('users', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            }

            $subjects = $subjectsQuery->get();

            // Define a coleção 'subjects' dentro do objeto $activeClassroom para ser usada diretamente no Blade se necessário
            $activeClassroom->setRelation('subjects', $subjects);

            // 3. Eager Loading e Busca de Notas/Conceitos
            $student->load([
                'grades.evaluation.subject',
                'preceptoryReports.subject',
            ]);

            $gradesQuery = $student->grades()
                ->whereHas('evaluation', function ($q) use ($activeClassroom, $bimester, $subjectId, $user) {
                    $q->where('classroom_id', $activeClassroom->id)
                        ->where('bimester', $bimester);

                    if ($subjectId) {
                        $q->where('subject_id', $subjectId);
                    } elseif (!$user->isAdmin()) {
                        // Opcional: Garante que nas avaliações gerais traga só o que for do professor
                        $q->whereHas('subject.users', function ($sq) use ($user) {
                            $sq->where('users.id', $user->id);
                        });
                    }
                });

            $grades = $gradesQuery
                ->with('evaluation.subject')
                ->get();

            // 4. Relatórios de Preceptoria
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

        $occurrenceTypes = OccurrenceType::all();

        return view('students.show', compact(
            'student',
            'activeClassroom',
            'grades',
            'reports',
            'bimester',
            'subjectId',
            'occurrenceTypes',
            'subjects'
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
