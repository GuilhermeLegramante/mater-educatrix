<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Occurrence;
use App\Models\OccurrenceType;
use App\Models\Classroom;
use App\Http\Requests\StoreOccurrenceRequest;
use Illuminate\Http\Request;

class OccurrenceController extends Controller
{
    /**
     * Exibe a listagem completa e detalhada de ocorrências com filtros e insights.
     */
    public function index(Request $request)
    {
        // 1. Inicia a consulta carregando os relacionamentos necessários
        $query = Occurrence::with(['student.classrooms', 'type', 'classroom', 'user']);

        // 2. Captura dos Filtros da URL
        $filters = $request->only(['search', 'type_id', 'classroom_id', 'date_start', 'date_end']);

        // Filtro por Nome do Aluno ou Título
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('student', function ($s) use ($search) {
                    $s->where('name', 'LIKE', '%' . $search . '%');
                })->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        // Filtro por Tipo de Ocorrência
        if (!empty($filters['type_id'])) {
            $query->where('occurrence_type_id', $filters['type_id']);
        }

        // Filtro por Turma
        if (!empty($filters['classroom_id'])) {
            $query->where('classroom_id', $filters['classroom_id']);
        }

        // Filtro por Período de Datas
        if (!empty($filters['date_start'])) {
            $query->whereDate('date', '>=', $filters['date_start']);
        }
        if (!empty($filters['date_end'])) {
            $query->whereDate('date', '<=', $filters['date_end']);
        }

        // 3. Cálculo dos Insights (Métricas Globais)
        $insights = [
            'total'         => Occurrence::count(),
            'this_month'    => Occurrence::whereMonth('date', now()->month)->whereYear('date', now()->year)->count(),
            'students_count' => Occurrence::distinct('student_id')->count('student_id'),
        ];

        // 4. Carrega listas para preencher os selects do filtro
        $types = OccurrenceType::orderBy('name')->get();
        $classrooms = Classroom::orderBy('name')->get();

        // 5. Paginação dos resultados ordenados da mais recente para a mais antiga
        $occurrences = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('occurrences.index', compact('occurrences', 'types', 'classrooms', 'insights', 'filters'));
    }

    /**
     * Exibe o formulário ou abre dados para o modal de cadastro
     */
    public function create(Student $student)
    {
        $types = OccurrenceType::where('is_active', true)->orderBy('name')->get();
        $classrooms = $student->classrooms;

        return view('occurrences.create', compact('student', 'types', 'classrooms'));
    }

    /**
     * Salva o registro da ocorrência atrelado ao aluno e ao usuário logado
     */
    public function store(StoreOccurrenceRequest $request, Student $student)
    {
        $student->occurrences()->create([
            'occurrence_type_id' => $request->occurrence_type_id,
            'classroom_id'       => $request->classroom_id,
            'user_id'            => auth()->id() ?? null,
            'date'               => $request->date,
            'time'               => $request->time,
            'description'        => $request->description,
            'actions_taken'      => $request->actions_taken,
        ]);

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Ocorrência registrada com sucesso!');
    }

    /**
     * Remove uma ocorrência caso tenha sido lançada errada
     */
    public function destroy(Occurrence $occurrence)
    {
        $studentId = $occurrence->student_id;
        $occurrence->delete();

        return redirect()->route('students.show', $studentId)
            ->with('success', 'Registro de ocorrência removido.');
    }
}
