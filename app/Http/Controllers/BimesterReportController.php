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
        // 1. Carrega turmas e disciplinas
        $classrooms = Classroom::orderBy('name')->get();
        $subjects   = Subject::orderBy('name')->get();

        // 2. Obtém os IDs dos filtros
        $selectedClassroomId = $request->input('classroom_id', $classrooms->first()?->id);
        $selectedSubjectId   = $request->input('subject_id');
        $selectedStudentId   = $request->input('student_id');

        $studentsData = [];
        $students     = collect();

        if ($selectedClassroomId) {
            // Alunos pertencentes a esta turma para o dropdown de filtro
            $students = Student::whereHas('classrooms', function ($q) use ($selectedClassroomId) {
                $q->where('classrooms.id', $selectedClassroomId);
            })->orderBy('name')->get();

            if ($selectedStudentId && !$students->contains('id', $selectedStudentId)) {
                $selectedStudentId = null;
            }

            // Carrega a turma e Eager Load das relações de bimesterResults
            $classroom = Classroom::with(['students' => function ($query) use ($selectedStudentId) {
                $query->orderBy('name')
                    ->with(['bimesterResults']); // Carrega os resultados salvos para evitar N+1
                if ($selectedStudentId) {
                    $query->where('students.id', $selectedStudentId);
                }
            }])->find($selectedClassroomId);

            if ($classroom) {
                $filteredSubjects = $selectedSubjectId
                    ? $subjects->where('id', $selectedSubjectId)
                    : $subjects;

                foreach ($classroom->students as $student) {
                    $studentReport = [
                        'student'  => $student,
                        'subjects' => []
                    ];

                    foreach ($filteredSubjects as $subject) {
                        $bimestersData = [];

                        for ($bimester = 1; $bimester <= 4; $bimester++) {
                            // Nota Formatada
                            $score = $student->getFormattedBimesterScore($classroom->id, $subject->id, $bimester);

                            // Conceito Automático (Prévio)
                            $automaticConcept = $student->getConcept($classroom->id, $subject->id, $bimester);

                            // Resultado Sobrescrito / Salvo no banco
                            $bimesterResult = $student->bimesterResults
                                ->where('classroom_id', $classroom->id)
                                ->where('subject_id', $subject->id)
                                ->where('bimester', $bimester)
                                ->first();

                            $finalConcept = $bimesterResult?->concept ?? $automaticConcept;

                            $isOverridden = $bimesterResult &&
                                $bimesterResult->concept &&
                                $bimesterResult->concept !== $automaticConcept;

                            $bimestersData[$bimester] = [
                                'score'             => $score,
                                'automatic_concept' => $automaticConcept,
                                'final_concept'     => $finalConcept,
                                'is_overridden'     => $isOverridden,
                            ];
                        }

                        $studentReport['subjects'][] = [
                            'subject'   => $subject,
                            'bimesters' => $bimestersData,
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
