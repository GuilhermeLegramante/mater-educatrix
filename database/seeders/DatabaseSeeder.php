<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Evaluation;
use App\Models\Grade;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Limpar tabelas com segurança de constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('school_days')->truncate(); // Limpa o calendário antigo ao dar refresh
        DB::table('grades')->truncate();
        DB::table('evaluations')->truncate();
        DB::table('enrollments')->truncate();
        DB::table('classroom_subject')->truncate();
        DB::table('classrooms')->truncate();
        DB::table('subjects')->truncate();
        DB::table('students')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        /*
        |--------------------------------------------------------------------------
        | Disciplinas
        |--------------------------------------------------------------------------
        */
        $subjects = [
            ['name' => 'Português'],
            ['name' => 'Artes'],
            ['name' => 'Ciências'],
            ['name' => 'Matemática'],
            ['name' => 'Música'],
            ['name' => 'Religião'],
            ['name' => 'História'],
            ['name' => 'Geografia'],
            ['name' => 'Educação Física'],
            ['name' => 'Inglês'],
            ['name' => 'Latim'],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        $allSubjects = Subject::all();

        /*
        |--------------------------------------------------------------------------
        | Turmas
        |--------------------------------------------------------------------------
        */
        $classrooms = [
            '1º Ano',
            '2º Ano',
            '3º Ano',
            '4º Ano',
            '5º Ano',
            '6º Ano',
            '7º Ano',
            '8º Ano',
        ];

        /*
        |--------------------------------------------------------------------------
        | Banco de nomes aleatórios
        |--------------------------------------------------------------------------
        */
        $studentNames = [
            'Ana Clara Souza',
            'Miguel Oliveira',
            'Helena Martins',
            'Arthur Costa',
            'Laura Almeida',
            'Heitor Ferreira',
            'Alice Rodrigues',
            'Theo Carvalho',
            'Valentina Gomes',
            'Davi Ribeiro',
            'Maria Eduarda Lopes',
            'Gabriel Santos',
            'Sophia Barbosa',
            'Bernardo Lima',
            'Isabella Fernandes',
            'Samuel Rocha',
            'Manuela Dias',
            'Pedro Henrique Melo',
            'Liz Monteiro',
            'Lucas Teixeira',
            'Cecília Cardoso',
            'Matheus Nunes',
            'Emanuelly Pinto',
            'João Guilherme Castro',
            'Heloísa Freitas',
            'Benjamin Correia',
            'Yasmin Vieira',
            'Enzo Moraes',
            'Antonella Batista',
            'Rafael Duarte',
            'Giovanna Peixoto',
            'Murilo Campos',
            'Clara Farias',
            'Gustavo Cunha',
            'Melissa Andrade',
            'Nicolas Braga',
            'Beatriz Rezende',
            'João Pedro Tavares',
            'Esther Moreira',
            'Felipe Azevedo',
            'Lavínia Barros',
            'Daniel Guimarães',
            'Mariana Silveira',
            'Caio Vinícius',
            'Lorena Pacheco',
            'Vinicius Siqueira',
            'Amanda Fonseca',
            'Eduardo Xavier',
            'Julia Menezes',
            'Henrique Ramos',
            'Bianca Moura',
            'Levi Santana',
            'Rebeca Matos',
            'Otávio Neves',
            'Luna Araújo',
            'Thiago Cavalcante',
            'Camila Assunção',
            'Bruno Valente',
            'Nicole Borges',
            'André Salgado',
            'Elisa Machado',
            'Pietro Leal',
            'Mirella Torres',
            'Joaquim Figueiredo',
            'Sara Macedo',
            'Ryan Viana',
            'Alana Porto',
            'Leonardo Rezende',
            'Agatha Moretti',
            'Diego Prado',
            'Vitória Severo',
            'José Henrique',
            'Aurora Antunes',
            'Lorenzo Bueno',
            'Milena Garcia',
            'Igor Medeiros',
            'Emily Domingues',
            'Nathan Albuquerque',
            'Carolina Rios',
            'Alexandre Pires',
        ];

        shuffle($studentNames);
        $nameIndex = 0;

        /*
        |--------------------------------------------------------------------------
        | Criar turmas + alunos + avaliações
        |--------------------------------------------------------------------------
        */
        foreach ($classrooms as $index => $classroomName) {

            $classroom = Classroom::create([
                'name' => $classroomName,
                'year' => 2026,
            ]);

            /* | Monta a grade de disciplinas injetando o "workload" na tabela pivô.
            | Simulamos cargas horárias diferentes baseadas na relevância da disciplina.
            */
            $pivotData = [];
            foreach ($allSubjects as $subject) {
                // Define cargas horárias padrão diferenciadas
                if (in_array($subject->name, ['Português', 'Matemática'])) {
                    $workload = ($index < 5) ? 160 : 200; // Anos finais ganham mais carga
                } elseif (in_array($subject->name, ['História', 'Geografia', 'Ciências'])) {
                    $workload = 80;
                } else {
                    $workload = 40; // Disciplinas menores (Artes, Música, Religião, Latim...)
                }

                $pivotData[$subject->id] = ['workload' => $workload];
            }

            // Executa o vínculo em massa inserindo o atributo 'workload' em cada linha da tabela pivô
            $classroom->subjects()->attach($pivotData);

            $minAge = 6 + $index;
            $maxAge = 7 + $index;

            // Criar 10 alunos por turma
            for ($i = 1; $i <= 10; $i++) {
                $age = rand($minAge, $maxAge);
                $birthDate = now()->subYears($age)->subDays(rand(0, 364));

                $student = Student::create([
                    'name' => $studentNames[$nameIndex],
                    'registration_number' => fake()->unique()->numerify('2026####'),
                    'birth_date' => $birthDate->format('Y-m-d'),
                ]);

                $nameIndex++;

                $classroom->students()->attach($student->id, [
                    'status' => 'active',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Avaliações por disciplina
            |--------------------------------------------------------------------------
            */
            foreach ($classroom->subjects as $subject) {

                $evaluations = [
                    [
                        'title' => 'Prova Mensal I',
                        'weight' => 1.0,
                        'max_score' => 100,
                        'bimester' => 1,
                        'applied_at' => now()->subDays(rand(25, 20)),
                    ],
                    [
                        'title' => 'Trabalho Escrito',
                        'weight' => 0.5,
                        'max_score' => 100,
                        'bimester' => 1,
                        'applied_at' => now()->subDays(rand(15, 10)),
                    ],
                    [
                        'title' => 'Avaliação Oral',
                        'weight' => 0.7,
                        'max_score' => 100,
                        'bimester' => 1,
                        'applied_at' => now()->subDays(rand(7, 3)),
                    ],
                ];

                $numberOfEvaluations = rand(2, 3);

                for ($e = 0; $e < $numberOfEvaluations; $e++) {
                    $evalData = $evaluations[$e];

                    $evaluation = Evaluation::create([
                        'classroom_id' => $classroom->id,
                        'subject_id' => $subject->id,
                        'title' => $evalData['title'],
                        'weight' => $evalData['weight'],
                        'max_score' => $evalData['max_score'],
                        'bimester' => $evalData['bimester'],
                        'applied_at' => $evalData['applied_at'],
                    ]);

                    foreach ($classroom->students as $student) {
                        Grade::create([
                            'student_id' => $student->id,
                            'evaluation_id' => $evaluation->id,
                            'score' => rand(55, 100),
                        ]);
                    }
                }
            }
        }

        // Chamar o Seeder de Calendário e de Tipos de Ocorrência após as turmas e alunos existirem
        $this->call([
            FullSchoolCalendarSeeder::class,
            OccurrenceTypeSeeder::class,
            SchoolSettingSeeder::class,
        ]);
    }
}
