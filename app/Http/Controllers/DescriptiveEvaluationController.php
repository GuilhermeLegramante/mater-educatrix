<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\DescriptiveQuestion;
use App\Models\DescriptiveRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DescriptiveEvaluationController extends Controller
{
    /**
     * Exibe a planilha de avaliação do aluno escolhido,
     * carregando EXCLUSIVAMENTE as questões cadastradas para a sua turma.
     */
    public function edit(Student $student, Request $request)
    {
        // 1. Obtém os parâmetros ativos da requisição (ou define o padrão)
        $bimester = $request->input('bimester', 1);
        $year = $request->input('year', date('Y'));

        // 2. Identifica o ID da turma vinculada ao aluno
        $classroomId = $student->currentClassroom->first()->id ?? null;

        // 3. Busca APENAS as perguntas de matérias cadastradas para a turma deste aluno
        $groupedQuestions = DescriptiveQuestion::whereNotNull('subject_id')
            ->where('classroom_id', $classroomId)
            ->orderBy('order_index', 'asc')
            ->get()
            ->groupBy('subject_id');

        // 4. Busca APENAS as perguntas de comportamento cadastradas para a turma deste aluno
        $behaviorQuestions = DescriptiveQuestion::whereNull('subject_id')
            ->where('classroom_id', $classroomId)
            ->orderBy('order_index', 'asc')
            ->get();


        // 5. Busca todas as disciplinas para mapear os nomes nos cabeçalhos da visualização
        $subjects = Subject::all()->keyBy('id');

        // 6. Carrega os lançamentos de notas/avaliações já salvos previamente para este aluno
        $existingRatings = DescriptiveRating::where('student_id', $student->id)
            ->where('bimester', $bimester)
            ->where('year', $year)
            ->pluck('rating', 'descriptive_question_id')
            ->toArray();

        // 7. Retorna a view enviando todas as variáveis necessárias
        return view('evaluations.descriptive-matrix', compact(
            'student',
            'bimester',
            'year',
            'groupedQuestions',
            'behaviorQuestions',
            'subjects',
            'existingRatings'
        ));
    }

    /**
     * Atualiza a avaliação descritiva do aluno.
     */
    public function update(Request $request, Student $student)
    {
        // 1. Validação robusta dos dados recebidos do formulário
        $validated = $request->validate([
            'bimester' => 'required|integer|between:1,4',
            'year'     => 'required|integer',
            'ratings'  => 'nullable|array',
            // Garante que os valores enviados sejam apenas os três permitidos
            'ratings.*' => 'nullable|string|in:optimal,partial,critical',
        ]);

        $bimester = $validated['bimester'];
        $year = $validated['year'];
        $ratings = $validated['ratings'] ?? [];

        try {
            // Usamos uma transação de banco de dados para garantir consistência absoluta
            DB::transaction(function () use ($student, $bimester, $year, $ratings) {

                foreach ($ratings as $questionId => $value) {
                    // Se o professor desmarcar ou enviar vazio, removemos o registro ou ignoramos
                    if (is_null($value)) {
                        DescriptiveRating::where('student_id', $student->id)
                            ->where('descriptive_question_id', $questionId)
                            ->where('bimester', $bimester)
                            ->where('year', $year)
                            ->delete();
                        continue;
                    }

                    // Atualiza ou cria a resposta para a questão específica
                    DescriptiveRating::updateOrCreate(
                        [
                            'student_id'              => $student->id,
                            'descriptive_question_id' => $questionId,
                            'bimester'                => $bimester,
                            'year'                    => $year,
                        ],
                        [
                            'rating' => $value, // Salva 'optimal', 'partial' ou 'critical'
                        ]
                    );
                }
            });

            return redirect()
                ->route('students.show', [$student, 'bimester' => $bimester])
                ->with('success', 'Avaliação descritiva salva com sucesso!');
        } catch (\Exception $e) {
            // Se houver qualquer falha no banco de dados, capturamos para evitar a tela branca
            // Em ambiente de desenvolvimento local, podemos exibir o erro real na tela:
            if (config('app.debug')) {
                dd($e->getMessage(), $e->getTraceAsString());
            }

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Ocorreu um erro ao salvar as respostas: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove todos os lançamentos da avaliação descritiva do aluno no bimestre e ano selecionados.
     */
    public function destroy(Request $request, Student $student)
    {
        // 1. Validação dos parâmetros recebidos
        $validated = $request->validate([
            'bimester' => 'required|integer|between:1,4',
            'year'     => 'required|integer',
        ]);

        $bimester = $validated['bimester'];
        $year = $validated['year'];

        try {
            // 2. Apaga todos os registos do aluno para aquele bimestre e ano
            DescriptiveRating::where('student_id', $student->id)
                ->where('bimester', $bimester)
                ->where('year', $year)
                ->delete();

            return redirect()
                ->route('students.show', [$student, 'bimester' => $bimester])
                ->with('success', 'Avaliação descritiva eliminada com sucesso!');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                dd($e->getMessage(), $e->getTraceAsString());
            }

            return redirect()
                ->back()
                ->withErrors(['error' => 'Ocorreu um erro ao eliminar a avaliação: ' . $e->getMessage()]);
        }
    }
}
