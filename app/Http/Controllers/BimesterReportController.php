<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Student;
use App\Models\BimesterResult;
use Illuminate\Http\Request;

class BimesterReportController extends Controller
{
    /**
     * Exibe a matriz de conceitos da gestão administrativa.
     */
    public function index(Request $request)
    {
        // 1. Carrega todas as turmas e disciplinas para preencher os seletores de filtro
        $classrooms = Classroom::orderBy('name')->get();
        $subjects   = Subject::orderBy('name')->get();

        // 2. Obtém os IDs selecionados no filtro
        $selectedClassroomId = $request->input('classroom_id', $classrooms->first()?->id);
        $selectedSubjectId   = $request->input('subject_id');

        $studentsData = [];

        if ($selectedClassroomId) {
            // Busca a turma selecionada junto com seus alunos
            $classroom = Classroom::with(['students' => function ($query) {
                $query->orderBy('name');
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
                            // Tenta buscar o conceito manual em BimesterResult ou o calculado via Student
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
            'selectedClassroomId',
            'selectedSubjectId',
            'studentsData'
        ));
    }
}
