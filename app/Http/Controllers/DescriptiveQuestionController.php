<?php

namespace App\Http\Controllers;

use App\Models\DescriptiveQuestion;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;

class DescriptiveQuestionController extends Controller
{
    /**
     * Lista todas as questões cadastradas com suporte a busca e filtros.
     */
    public function index(Request $request)
    {
        // 1. Inicia a consulta carregando os relacionamentos (evita o problema N+1)
        $query = DescriptiveQuestion::with(['subject', 'classroom'])
            ->orderBy('order_index', 'asc');

        // 2. Filtro de Busca por Enunciado (campo 'search' vindo do formulário)
        if ($request->filled('search')) {
            $query->where('question_text', 'like', '%' . $request->search . '%');
        }

        // 3. Filtro Opcional por Turma
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        // 4. Filtro Opcional por Disciplina
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // 5. Executa a paginação e preserva os parâmetros de filtro nos links de página
        $questions = $query->paginate(15)->withQueryString();

        // 6. Obtém as turmas do ano atual (1º ao 4º) para o filtro da view
        $currentYear = now()->year;
        $classrooms = Classroom::where('year', $currentYear)
            ->where(function ($q) {
                $years = ['1º', '2º', '3º', '4º'];
                foreach ($years as $year) {
                    $q->orWhere('name', 'like', "%{$year}%");
                }
            })
            ->get();

        // 7. Carrega as disciplinas ativas para preencher o select de filtro
        $subjects = Subject::orderBy('name', 'asc')->get();

        return view('questions.index', compact('questions', 'classrooms', 'subjects'));
    }

    /**
     * Exibe o formulário de criação de questão.
     */
    public function create()
    {
        $subjects = Subject::all();

        $currentYear = now()->year;

        $classrooms = Classroom::where('year', $currentYear)
            ->where(function ($q) {
                $years = ['1º', '2º', '3º', '4º'];
                foreach ($years as $year) {
                    $q->orWhere('name', 'like', "%{$year}%");
                }
            })
            ->get();

        return view('questions.create', compact('subjects', 'classrooms'));
    }

    /**
     * Salva uma nova questão no banco de dados.
     */
    public function store(Request $request)
    {
        // 1. Validação dos dados do formulário
        $validated = $request->validate([
            'question_text'    => 'required|string|max:1000',
            'subject_id'   => 'nullable|exists:subjects,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'order_index'  => 'required|integer|min:0',
        ]);

        // 2. Criação do registro
        DescriptiveQuestion::create($validated);

        return redirect()
            ->route('questions.index')
            ->with('success', 'Questão descritiva criada com sucesso!');
    }

    /**
     * Exibe o formulário de edição de uma questão existente.
     */
    public function edit(DescriptiveQuestion $question)
    {
        $subjects = Subject::all();

        $currentYear = now()->year;

        $classrooms = Classroom::where('year', $currentYear)
            ->where(function ($q) {
                $years = ['1º', '2º', '3º', '4º'];
                foreach ($years as $year) {
                    $q->orWhere('name', 'like', "%{$year}%");
                }
            })
            ->get();

        return view('questions.edit', compact('question', 'subjects', 'classrooms'));
    }

    /**
     * Atualiza os dados de uma questão no banco de dados.
     */
    public function update(Request $request, DescriptiveQuestion $question)
    {
        // 1. Validação dos dados alterados
        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
            'subject_id'    => 'nullable|exists:subjects,id',
            'classroom_id'  => 'nullable|exists:classrooms,id',
            'order_index'   => 'required|integer|min:0',
        ]);

        // 2. Atualização do registro
        $question->update($validated);

        return redirect()
            ->route('questions.index')
            ->with('success', 'Questão descritiva atualizada com sucesso!');
    }

    /**
     * Remove uma questão do banco de dados.
     */
    public function destroy(DescriptiveQuestion $question)
    {
        $question->delete();

        return redirect()
            ->route('questions.index')
            ->with('success', 'Questão descritiva eliminada com sucesso!');
    }
}
