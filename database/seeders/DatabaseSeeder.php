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

        DB::table('school_days')->truncate();
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
            ['name' => 'Estudos Sociais'],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        $allSubjects = Subject::all();

        /*
        |--------------------------------------------------------------------------
        | Estudantes por Turma (Planilha)
        |--------------------------------------------------------------------------
        */
        $studentsByClassroom = [
            '1º Ano' => [
                ['name' => 'Bernardo Mantovani Xavier Barbaro', 'birth_date' => '2019-11-06'],
                ['name' => 'Bruno Flores Pinto', 'birth_date' => '2019-07-25'],
                ['name' => 'Chiara Escobar de Mello', 'birth_date' => '2019-12-21'],
                ['name' => 'Dom de Lima Pereira', 'birth_date' => '2019-06-07'],
                ['name' => 'Elena Maria Zago Schmidt', 'birth_date' => '2019-12-09'],
                ['name' => 'Estevan Flores Dagnese', 'birth_date' => '2019-10-26'],
                ['name' => 'Gonçalo Mascarenhas de Souza Marques da Rocha', 'birth_date' => '2019-07-22'],
                ['name' => 'Helena Piloneto Paes', 'birth_date' => '2019-12-02'],
                ['name' => 'João Paulo Grasel Marramarco Lovato', 'birth_date' => '2020-06-08'],
                ['name' => 'Katharina Dick Lemainski', 'birth_date' => '2020-03-07'],
                ['name' => 'Lucia Quatrin Elias', 'birth_date' => '2020-01-31'],
                ['name' => 'Mariana Bressan Pereira', 'birth_date' => '2019-11-20'],
                ['name' => 'Maria Teresa Gelatti Lovato', 'birth_date' => '2020-03-15'],
                ['name' => 'Matteo Frantz Marques', 'birth_date' => '2019-08-29'],
                ['name' => 'Samuel Sant´anna Iop', 'birth_date' => '2020-02-17'],
                ['name' => 'Vicenzo Baggio Fernandes', 'birth_date' => '2019-10-28'],
                ['name' => 'Yohana Strunkis da Rosa', 'birth_date' => '2019-06-19'],
            ],
            '2º Ano' => [
                ['name' => 'Carmela da Silva Dorneles', 'birth_date' => '2019-11-29'],
                ['name' => 'Conrado Stamm Maldaner', 'birth_date' => '2018-05-21'],
                ['name' => 'Ester Capra Tafernaberri', 'birth_date' => '2019-05-03'],
                ['name' => 'Helena Thereza Berthold Benedetti', 'birth_date' => '2018-01-05'],
                ['name' => 'Isabella Tambara Quatrin', 'birth_date' => '2018-04-23'],
                ['name' => 'Laura Alves Villanova Leão', 'birth_date' => '2019-03-01'],
                ['name' => 'Lucas Mondin Antunes Paul', 'birth_date' => '2018-10-20'],
                ['name' => 'Miguel Brasil Ribeiro', 'birth_date' => '2018-11-17'],
                ['name' => 'Pedro Corso Dos Santos', 'birth_date' => '2019-01-27'],
                ['name' => 'Sarah Pereira', 'birth_date' => '2018-11-19'],
                ['name' => 'Stella Raddatz Fernandes', 'birth_date' => '2019-05-05'],
                ['name' => 'Valentim Vargas Barreto', 'birth_date' => '2019-11-22'],
                ['name' => 'Vicente Rothe da Costa', 'birth_date' => '2019-12-02'],
            ],
            '3º Ano' => [
                ['name' => 'Antônio Grasel Marramarco Lovato', 'birth_date' => '2018-03-20'],
                ['name' => 'Cássia de Lima Pereira', 'birth_date' => '2017-08-09'],
                ['name' => 'Catarina Brandão Fernandes', 'birth_date' => '2017-11-29'],
                ['name' => 'Cecília Stradioto Boligon', 'birth_date' => '2017-09-20'],
                ['name' => 'Ester Flores Pinto', 'birth_date' => '2018-02-05'],
                ['name' => 'Gael Luiz Cecagno Zanini', 'birth_date' => '2018-10-13'],
                ['name' => 'Helena Mallmann Forgiarini', 'birth_date' => '2018-02-15'],
                ['name' => 'Isabela de Moraes Trevisan', 'birth_date' => '2017-10-22'],
                ['name' => 'Maria Antônia Lanza Nakashima', 'birth_date' => '2017-08-22'],
                ['name' => 'Maria Luisa Lanza Nakashima', 'birth_date' => '2017-08-22'],
                ['name' => 'Mateus Mondin Paul', 'birth_date' => '2017-05-30'],
                ['name' => 'Miguel Rossato Lorensi', 'birth_date' => '2017-04-26'],
                ['name' => 'Miguel Sant´anna Iop', 'birth_date' => '2017-10-10'],
                ['name' => 'Tereza Pafiadache Volpato', 'birth_date' => '2018-05-18'],
                ['name' => 'Vittorio Cecchin Cordenuzzi', 'birth_date' => '2018-02-17'],
            ],
            '4º Ano' => [
                ['name' => 'Aurora Ledur Sturmer', 'birth_date' => '2017-05-17'],
                ['name' => 'Bento da Silva Dorneles', 'birth_date' => '2016-08-26'],
                ['name' => 'Bruno Frantz Marques', 'birth_date' => '2016-05-30'],
                ['name' => 'Fernando Piloneto Paes', 'birth_date' => '2016-06-01'],
                ['name' => 'Helena Baggio Fernandes', 'birth_date' => '2017-02-01'],
                ['name' => 'Maria Eduarda Baggio Negrini', 'birth_date' => '2016-04-16'],
                ['name' => 'Maria Teresa Baggio Negrini', 'birth_date' => '2016-04-16'],
                ['name' => 'Martina Menecozi Silveira', 'birth_date' => '2017-01-19'],
                ['name' => 'Miguel Ricieri Machado Mezzomo', 'birth_date' => '2016-01-18'],
            ],
            '5º Ano' => [
                ['name' => 'Antonela Bicca Denardin', 'birth_date' => '2016-01-27'],
                ['name' => 'Eduardo Weber Posser', 'birth_date' => '2015-02-05'],
                ['name' => 'Eric Amaral Masina', 'birth_date' => '2016-01-20'],
                ['name' => 'Helena Waechter Pereira', 'birth_date' => '2015-06-19'],
                ['name' => 'Isabela Baggio Fernandes', 'birth_date' => '2015-02-05'],
                ['name' => 'Isabel Sant´Anna Iop', 'birth_date' => '2015-11-19'],
                ['name' => 'Juliane de Souza Flores', 'birth_date' => '2015-07-17'],
                ['name' => 'Leonel Varaschini Poetini', 'birth_date' => '2016-08-20'],
                ['name' => 'Lorenzo Sangoi de Moura', 'birth_date' => '2015-10-16'],
                ['name' => 'Lucas da Rosa Denardin', 'birth_date' => '2015-06-05'],
                ['name' => 'Manuella da Rosa Canabarro', 'birth_date' => '2015-04-25'],
                ['name' => 'Mariane Petiz Ramos', 'birth_date' => '2015-10-13'],
                ['name' => 'Maria Regina Gelatti Lovato', 'birth_date' => '2016-09-10'],
                ['name' => 'Miguel Sallet Farias', 'birth_date' => '2016-03-07'],
                ['name' => 'Vicente Pafiadache Volpato', 'birth_date' => '2016-05-06'],
            ],
            '6º Ano' => [
                ['name' => 'Alice Quatrin Elias', 'birth_date' => '2014-12-25'],
                ['name' => 'Alice Rothert', 'birth_date' => '2014-12-21'],
                ['name' => 'Ana Rafaela Avelino Monteiro', 'birth_date' => '2014-05-02'],
                ['name' => 'Benjamin Ledur Sturmer', 'birth_date' => '2015-02-01'],
                ['name' => 'Bernardo Righi Medeiros Camillo', 'birth_date' => '2014-04-16'],
                ['name' => 'Dorothy Rothert', 'birth_date' => '2014-12-21'],
                ['name' => 'Heitor Victório Minello', 'birth_date' => '2014-10-18'],
                ['name' => 'Helena Stradioto Boligon', 'birth_date' => '2014-04-17'],
                ['name' => 'João Henrique Winkelmann Luttjohann', 'birth_date' => '2015-02-21'],
                ['name' => 'João Miguel Pereira', 'birth_date' => '2013-11-02'],
                ['name' => 'João Miguel Silva Gozzi', 'birth_date' => '2014-12-10'],
                ['name' => 'Lívia Sofia Dalbem', 'birth_date' => '2015-05-15'],
                ['name' => 'Paco Antônio Cecagno Zanini', 'birth_date' => '2015-05-09'],
            ],
            '7º Ano' => [
                ['name' => 'Antonella Cerolini Charão', 'birth_date' => '2013-11-26'],
                ['name' => 'Antônia Mallmann Forgiarini', 'birth_date' => '2013-09-25'],
                ['name' => 'Beatriz Schroeter Hahn Souza', 'birth_date' => '2013-05-27'],
                ['name' => 'Clara Denardi Schlosser', 'birth_date' => '2013-05-05'],
                ['name' => 'Elano Simionato Girardi', 'birth_date' => '2013-08-09'],
                ['name' => 'Elias Flores Pinto', 'birth_date' => '2013-06-08'],
                ['name' => 'João Ricardo Zago Gössling', 'birth_date' => '2013-07-10'],
                ['name' => 'João Vitor da Silva Niederauer', 'birth_date' => '2012-12-29'],
                ['name' => 'Lorenzo Mascarenhas de Souza Marques da Rocha', 'birth_date' => '2013-10-18'],
                ['name' => 'Marcelle Amaral Masina', 'birth_date' => '2013-09-20'],
                ['name' => 'Maria Schubert Pinheiro', 'birth_date' => '2014-01-12'],
                ['name' => 'Matheus Miguel Rothert', 'birth_date' => '2013-11-30'],
            ],
            '8º Ano' => [
                ['name' => 'Bruna Bernardi Goulart', 'birth_date' => '2011-08-02'],
                ['name' => 'Davi Fernandes Bastos', 'birth_date' => '2013-02-08'],
                ['name' => 'Francisco Boligon Piccinin', 'birth_date' => '2012-09-10'],
                ['name' => 'Henrique Bitencourt de Camargo', 'birth_date' => '2012-08-01'],
                ['name' => 'Isabela Sofia Dalbem', 'birth_date' => '2013-01-28'],
                ['name' => 'Isabele de Souza Flores', 'birth_date' => '2012-08-18'],
                ['name' => 'João Paulo Avelino Monteiro', 'birth_date' => '2012-04-03'],
                ['name' => 'Maria Luísa Falcão Baggio', 'birth_date' => '2012-04-16'],
                ['name' => 'Mateus Legramante Becker Martins', 'birth_date' => '2013-03-22'],
                ['name' => 'Pedro Ernesto Brandão Araldi', 'birth_date' => '2012-09-20'],
                ['name' => 'Rafael Antonio Bassi', 'birth_date' => '2012-10-04'],
                ['name' => 'Vitória Strunkis da Rosa', 'birth_date' => '2012-07-01'],
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Criar turmas + alunos + avaliações
        |--------------------------------------------------------------------------
        */
        $classroomIndex = 0;
        foreach ($studentsByClassroom as $classroomName => $students) {

            $classroom = Classroom::create([
                'name' => $classroomName,
                'year' => 2026,
            ]);

            // Cargas horárias na tabela pivô
            $pivotData = [];
            foreach ($allSubjects as $subject) {
                if (in_array($subject->name, ['Português', 'Matemática'])) {
                    $workload = ($classroomIndex < 5) ? 160 : 200;
                } elseif (in_array($subject->name, ['História', 'Geografia', 'Ciências'])) {
                    $workload = 80;
                } else {
                    $workload = 40;
                }

                $pivotData[$subject->id] = ['workload' => $workload];
            }

            $classroom->subjects()->attach($pivotData);

            // Criar e matricular os estudantes
            foreach ($students as $studentData) {
                $student = Student::create([
                    'name' => $studentData['name'],
                    'registration_number' => fake()->unique()->numerify('2026####'),
                    'birth_date' => $studentData['birth_date'],
                ]);

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

            $classroomIndex++;
        }

        // Chamada dos seeders complementares
        $this->call([
            FullSchoolCalendarSeeder::class,
            OccurrenceTypeSeeder::class,
            SchoolSettingSeeder::class,
            DescriptiveQuestionsSeeder::class,
            UserSeeder::class,
        ]);
    }
}
