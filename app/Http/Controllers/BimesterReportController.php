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
        $classrooms = Classroom::all();
        $selectedClassroomId = $request->get('classroom_id');
        $selectedSubjectId = $request->get('subject_id');
        $selectedStudentId = $request->get('student_id');

        $subjects = collect();
        $students = collect();
        $studentsData = [];

        if ($selectedClassroomId) {
            $classroom = Classroom::with('subjects', 'students')->find($selectedClassroomId);

            if ($classroom) {
                $subjects = $classroom->subjects;
                $students = $classroom->students;

                // Aplica filtro de aluno se selecionado
                $queryStudents = $classroom->students();
                if ($selectedStudentId) {
                    $queryStudents->where('students.id', $selectedStudentId);
                }

                $activeStudents = $queryStudents->get();

                // Monta a estrutura de dados de conceitos por aluno e disciplina
                foreach ($activeStudents as $student) {
                    // ... lógica de consolidação dos conceitos
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
