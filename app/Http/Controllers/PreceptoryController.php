<?php

namespace App\Http\Controllers;

use App\Models\PreceptoryReport;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;

class PreceptoryController extends Controller
{
    /**
     * Exibe o formulário de relato para um aluno específico dentro de uma turma
     */
    public function create(Classroom $classroom, Student $student)
    {
        $subjects = $classroom->subjects;
        return view('preceptory.create', compact('classroom', 'student', 'subjects'));
    }

    public function store(Request $request, Classroom $classroom)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'content'    => 'required|string',
            'bimester'   => 'required|integer',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        // Criamos o registro vinculando à turma da URL
        $classroom->preceptoryReports()->create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'content'    => $request->content,
            'bimester'   => $request->bimester,
        ]);

        return back()->with('success', 'Anotação registrada com sucesso!');
    }

    public function show(PreceptoryReport $report)
    {
        return view('preceptory.show', compact('report'));
    }

    /**
     * Remove um relato de preceptoria do banco de dados.
     *
     * @param  \App\Models\Classroom  $classroom  Model da turma injetado pela rota aninhada
     * @param  \App\Models\PreceptoryReport  $preceptory  Model do relato a ser excluído
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Classroom $classroom, PreceptoryReport $preceptory)
    {
        // 1. Guarda o ID do estudante antes de apagar o registro para usar no redirecionamento
        $studentId = $preceptory->student_id;

        // 2. Executa a exclusão da ocorrência/relato no banco de dados
        $preceptory->delete();

        // 3. Redireciona de volta para o perfil do estudante com mensagem de sucesso
        return redirect()
            ->route('students.show', $studentId)
            ->with('success', 'Registro removido com sucesso.');
    }
}
