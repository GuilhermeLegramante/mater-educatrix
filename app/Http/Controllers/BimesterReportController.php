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
        // 1. Carrega as opções para os seletores de filtro
        $classrooms = Classroom::orderBy('name')->get();
        $subjects   = Subject::orderBy('name')->get();

        // 2. Obtém os IDs filtrados a partir da requisição HTTP
        $selectedClassroomId = $request->input('classroom_id', $classrooms->first()?->id);
        $selectedSubjectId   = $request->input('subject_id');
        $selectedStudentId   = $request->input('student_id');

        $studentsData = [];
        $students     = collect();

        if ($selectedClassroomId) {
            // Busca a turma com os alunos (aplicando o filtro de aluno se selecionado)
            $classroom = Classroom::with(['students' => function ($query) use ($selectedStudentId) {
                $query->orderBy('name');
                if ($selectedStudentId) {
                    $query->where('students.id', $selectedStudentId);
                }
            }])->find($selectedClassroomId);

            if ($classroom) {
                // Lista de alunos da turma para preencher o campo do filtro "Aluno" na View
                $students = Classroom::find($selectedClassroomId)->students()->orderBy('name')->get();

                // Filtra as disciplinas se uma disciplina específica foi selecionada
                $filteredSubjects = $selectedSubjectId
                    ? $subjects->where('id', $selectedSubjectId)
                    : $subjects;

                // Monta o relatório estruturado por Aluno -> Disciplina -> Bimestres (1 a 4)
                foreach ($classroom->students as $student) {
                    $studentReport = [
                        'student'  => $student,
                        'subjects' => []
                    ];

                    foreach ($filteredSubjects as $subject) {
                        $bimesters = [];

                        // Consulta os conceitos consolidados nos 4 bimestres
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

        // 3. Retorna a View enviando todos os dados necessários
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
