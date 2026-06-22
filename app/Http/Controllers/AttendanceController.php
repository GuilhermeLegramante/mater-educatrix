<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\SchoolDay;
use App\Models\Attendance;
use App\Models\Subject; // Supondo que você tenha o Model Subject
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Tela Inicial do Diário: Seleção de Turmas e Disciplinas
     */
    public function dashboard()
    {
        // Carrega as turmas e também as disciplinas (subjects)
        $classrooms = Classroom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('attendance.dashboard', compact('classrooms', 'subjects'));
    }

    /**
     * Exibe o Grid de Frequência de uma Turma e Disciplina Específica
     */
    public function index(Request $request, $classroomId, $subjectId)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $currentDate = Carbon::createFromDate($year, $month, 1);

        // Busca os dados da Turma e da Disciplina para exibir no cabeçalho
        $classroom = Classroom::findOrFail($classroomId);
        $subject = Subject::findOrFail($subjectId);

        // 1. Busca os alunos ativos na turma
        $students = Student::whereHas('classrooms', function ($query) use ($classroomId) {
            $query->where('classroom_id', $classroomId)
                ->where('enrollments.status', 'active');
        })->orderBy('name')->get();

        // 2. Busca os dias de aula oficiais criados para essa turma no mês
        $schoolDays = SchoolDay::where('classroom_id', $classroomId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date')
            ->get();

        // 3. Mapeia o mapa de faltas filtrando APENAS pela disciplina atual ($subjectId)
        $absenceMap = Attendance::whereIn('student_id', $students->pluck('id'))
            ->whereIn('school_day_id', $schoolDays->pluck('id'))
            ->get()
            ->groupBy('student_id')
            ->map(function ($attendances) {
                return $attendances->keyBy('school_day_id')->map(function ($attendance) {
                    return $attendance->quantity; // Mapeia o número armazenado na coluna
                });
            })->toArray();

        return view('attendance.index', [
            'classroom'   => $classroom,
            'subject'     => $subject,
            'students'     => $students,
            'schoolDays'   => $schoolDays,
            'absenceMap'   => $absenceMap,
            'currentDate'  => $currentDate,
        ]);
    }

    /**
     * Método disparado via JavaScript Inline (Altera/Acumula as faltas na disciplina)
     */
    public function toggle(Request $request)
    {
        try {
            $request->validate([
                'student_id'         => 'required|exists:students,id',
                'school_day_id'      => 'required|exists:school_days,id',
                'subject_id'         => 'required|exists:subjects,id',
                'requested_absences' => 'required|integer|min:0|max:3',
            ]);

            $studentId    = $request->student_id;
            $schoolDayId  = $request->school_day_id;
            $subjectId    = $request->subject_id;
            $qtyRequested = (int) $request->requested_absences;

            if ($qtyRequested === 0) {
                // Se o professor removeu as faltas, limpa o registro (Vira Presença "P")
                Attendance::where('student_id', $studentId)
                    ->where('school_day_id', $schoolDayId)
                    ->where('subject_id', $subjectId)
                    ->delete();
            } else {
                // Se tem 1, 2 ou 3 faltas, atualiza a linha existente ou cria se não houver
                Attendance::updateOrCreate(
                    [
                        'student_id'    => $studentId,
                        'school_day_id' => $schoolDayId,
                        'subject_id'    => $subjectId,
                    ],
                    [
                        'is_absent' => true,
                        'quantity'  => $qtyRequested // Grava o número bruto (1, 2 ou 3)
                    ]
                );
            }

            return response()->json([
                'success'        => true,
                'absences_count' => $qtyRequested
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Se a validação do Laravel falhar, retorna os erros estruturados em formato JSON
            return response()->json([
                'success' => false,
                'message' => 'Dados de requisição inválidos.',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Captura falhas de banco de dados e impede que o Laravel envie um HTML de erro 500
            return response()->json([
                'success' => false,
                'message' => 'Erro interno no servidor ao processar a frequência.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
