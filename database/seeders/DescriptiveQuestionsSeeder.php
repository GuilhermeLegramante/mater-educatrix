<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\DescriptiveQuestion;
use Illuminate\Support\Facades\Schema;

class DescriptiveQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. DESATIVAR a checagem de chaves estrangeiras temporariamente para evitar o erro 1701
        Schema::disableForeignKeyConstraints();

        // 2. Limpar perguntas antigas com segurança
        DescriptiveQuestion::truncate();

        // 3. REATIVAR a checagem de chaves estrangeiras logo em seguida
        Schema::enableForeignKeyConstraints();

        // Garantir que as disciplinas existam e obter seus IDs reais de forma segura
        $subjects = [
            'matematica' => Subject::firstOrCreate(['name' => 'Matemática'])->id,
            'ciencias'   => Subject::firstOrCreate(['name' => 'Ciências'])->id,
            'sociais'    => Subject::firstOrCreate(['name' => 'Estudos Sociais'])->id,
            'portugues'  => Subject::firstOrCreate(['name' => 'Português'])->id,
        ];

        $orderIndex = 1;

        // --- MATEMÁTICA ---
        $matematicaQuestions = [
            'Atingiu os objetivos esperados na matéria de Matemática?',
            'Fez as tarefas solicitadas?',
            'Demonstra interesse pela matéria?',
            'Demonstra facilidade em compreender os assuntos, fazendo sem dificuldades os exercícios?',
            'Demonstra ter compreendido e executa bem os exercícios de contagem?',
            'Relaciona número e quantidades com facilidade?',
            'Consegue identificar a sequência dos números, colocando-os em ordem crescent e decrescente?',
        ];

        foreach ($matematicaQuestions as $question) {
            DescriptiveQuestion::create([
                'subject_id' => $subjects['matematica'],
                'question_text' => $question,
                'order_index' => $orderIndex++,
            ]);
        }

        // --- CIÊNCIAS ---
        $cienciasQuestions = [
            'Atingiu os objetivos esperados na matéria de Ciências?',
            'Fez as tarefas solicitadas?',
            'Demonstra interesse pela matéria?',
            'Demonstra facilidade em compreender os assuntos, fazendo sem dificuldades os exercícios?',
        ];

        foreach ($cienciasQuestions as $question) {
            DescriptiveQuestion::create([
                'subject_id' => $subjects['ciencias'],
                'question_text' => $question,
                'order_index' => $orderIndex++,
            ]);
        }

        // --- ESTUDOS SOCIAIS ---
        $sociaisQuestions = [
            'Atingiu os objetivos esperados na matéria de Estudos Sociais?',
            'Fez as tarefas solicitadas?',
            'Demonstra interesse pela matéria?',
            'Demonstra facilidade em compreender os assuntos, fazendo sem dificuldades os exercícios?',
        ];

        foreach ($sociaisQuestions as $question) {
            DescriptiveQuestion::create([
                'subject_id' => $subjects['sociais'],
                'question_text' => $question,
                'order_index' => $orderIndex++,
            ]);
        }

        // --- PORTUGUÊS / LINGUAGEM ---
        $portuguesQuestions = [
            'Atingiu os objetivos esperados na matéria de Linguagem/português?',
            'Fez as tarefas solicitadas?',
            'Está lendo bem palavras curtas e simples?',
            'Está lendo bem palavras longas e mais complexas?',
            'Lê e compreende frases?',
            'Escreve de forma satisfatória para o estágio de alfabetização a partir de ditados?',
            'Escreve de forma satisfatória para o estágio de alfabetização a partir da cópia do quadro?',
            'Passou pelos conteúdos do bimestre até o momento sem dificuldade?',
        ];

        foreach ($portuguesQuestions as $question) {
            DescriptiveQuestion::create([
                'subject_id' => $subjects['portugues'],
                'question_text' => $question,
                'order_index' => $orderIndex++,
            ]);
        }

        // --- MATRIZ DE COMPORTAMENTO (subject_id = null) ---
        $comportamentoQuestions = [
            'Tem o caderno completo?',
            'Tem o caderno organizado ou caprichado?',
            'Procura copiar e completar o caderno, pede ajuda quando falta?',
            'Faz as tarefas solicitadas?',
            'Tira dúvidas em aula?',
            'Demonstra estudo em casa?',
            'Está melhorando seu rendimento, em comparação com o começo do bimestre?',
            'Realiza atividades de memorização?',
            'Tenta fazer as atividades sem reclamar ou sem inventar desculpas?',
            'Evita participar de conversas em aula, sem puxar assunto com os colegas?',
            'Busca manter a concentração em aula, sem procurar distrações?',
            'No recreio e no final da aula, mantém a ordem e é obediente às orientações?',
            'Na capela participa bem das orações, sem se distrair ou conversar?',
            'Demonstra esforço em realizar o que tem dificuldade?',
            'Recebe bem as instruções e orientações dos professores e supervisores? Sem demonstrar teimosia, rigidez ou contrariedade?',
        ];

        foreach ($comportamentoQuestions as $question) {
            DescriptiveQuestion::create([
                'subject_id' => null,
                'question_text' => $question,
                'order_index' => $orderIndex++,
            ]);
        }
    }
}
