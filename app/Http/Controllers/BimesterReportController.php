<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;

class BimesterReportController extends Controller
{
    /**
     * Exibe o relatório consolidado de conceitos por turma, disciplina e aluno.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 1. Carrega todas as turmas e disciplinas para os seletores
        $classrooms = Classroom::orderBy('name')->get();
        $subjects   = Subject::orderBy('name')->get();

        // 2. Obtém os IDs dos filtros
        $selectedClassroomId = $request->input('classroom_id', $classrooms->first()?->id);
        $selectedSubjectId   = $request->input('subject_id');
        $selectedStudentId   = $request->input('student_id');

        $studentsData = [];
        $students     = collect();

        if ($selectedClassroomId) {
            // Obtém a lista completa de alunos pertencentes a esta turma
            $students = Student::whereHas('classrooms', function ($q) use ($selectedClassroomId) {
                $q->where('classrooms.id', $selectedClassroomId);
            })->orderBy('name')->get();

            // Se o aluno selecionado não pertencer à turma atual, resetamos o filtro de aluno
            if ($selectedStudentId && !$students->contains('id', $selectedStudentId)) {
                $selectedStudentId = null;
            }

            // Busca a turma carregando apenas os alunos filtrados (ou todos da turma se $selectedStudentId for nulo)
            $classroom = Classroom::with(['students' => function ($query) use ($selectedStudentId) {
                $query->orderBy('name');
                if ($selectedStudentId) {
                    $query->where('students.id', $selectedStudentId);
                }
            }])->find($selectedClassroomId);

            if ($classroom) {
                // Disciplinas a serem exibidas (todas ou apenas a filtrada)
                $filteredSubjects = $selectedSubjectId
                    ? $subjects->where('id', $selectedSubjectId)
                    : $subjects;

                foreach ($classroom->students as $student) {
                    $studentReport = [
                        'student'  => $student,
                        'subjects' => []
                    ];

                    foreach ($filteredSubjects as $subject) {
                        $bimesters = [];

                        // Consulta os conceitos lançados nos 4 bimestres
                        for ($bimester = 1; $bimester <= 4; $bimester++) {
                            $concept = $student->getConsolidatedConcept(
                                $classroom->id,
                                $subject->id,
                                $bimester
                            );

                            $bimesters[$bimester] = $concept;
                        }

                        $studentReport['subjects'][] = [
                            'subject'   => $subject,
                            'bimesters' => $bimesters,
                        ];
                    }

                    $studentsData[] = $studentReport;
                }
            }
        }

        return view('reports.bimesters.index', compact(
            'classrooms',
            'subjects',
            'students',
            'selectedClassroomId',
            'selectedSubjectId',
            'selectedStudentId',
            'studentsData'
        ));
    }
}
