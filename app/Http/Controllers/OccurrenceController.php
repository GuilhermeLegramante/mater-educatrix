<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Occurrence;
use App\Models\OccurrenceType;
use App\Http\Requests\StoreOccurrenceRequest;
use Illuminate\Http\Request;

class OccurrenceController extends Controller
{
    /**
     * Exibe a listagem completa de ocorrências registradas no sistema.
     */
    public function index(Request $request)
    {
        // Busca as ocorrências trazendo os dados do aluno junto (eager loading)
        // Ordena das mais recentes para as mais antigas e pagina de 15 em 15
        $occurrences = Occurrence::with('student')
            ->latest()
            ->paginate(15);

        return view('occurrences.index', compact('occurrences'));
    }

    /**
     * Exibe o formulário ou abre dados para o modal de cadastro
     */
    public function create(Student $student)
    {
        // Pega apenas os tipos ativos para o gestor escolher
        $types = OccurrenceType::where('is_active', true)->orderBy('name')->get();

        // Busca as turmas que o aluno tem vínculo para o select opcional
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
            'user_id'            => auth()->id() ?? null, // Quem está registrando a ocorrência no momento
            'date'               => $request->date,
            'time'               => $request->time,
            'description'        => $request->description,
            'actions_taken'      => $request->actions_taken,
        ]);

        return redirect()->route('students.show', $student->id) // Redireciona de volta para o perfil do aluno
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
