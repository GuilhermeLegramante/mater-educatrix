<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Classroom;
use App\Models\Evaluation;
use App\Models\Grade;
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
        // 1. Limpar dados antigos para evitar duplicados
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('grades')->truncate();
        DB::table('evaluations')->truncate();
        DB::table('enrollments')->truncate();
        DB::table('classroom_subject')->truncate();
        DB::table('classrooms')->truncate();
        DB::table('subjects')->truncate();
        DB::table('students')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Criar Disciplinas (O currículo clássico da Mater Educatrix)
        $subjects = [
            ['name' => 'Latim e Gramática', 'workload' => 80],
            ['name' => 'Filosofia e Lógica', 'workload' => 60],
            ['name' => 'História da Civilização', 'workload' => 60],
            ['name' => 'Matemática e Aritmética', 'workload' => 100],
            ['name' => 'Literatura Clássica', 'workload' => 80],
            ['name' => 'Religião e Virtudes', 'workload' => 40],
        ];

        foreach ($subjects as $s) {
            Subject::create($s);
        }
        $allSubjects = Subject::all();

        // 3. Criar Turmas
        $classrooms = [
            ['name' => '5º Ano A', 'year' => 2024],
            ['name' => '6º Ano A', 'year' => 2024],
            ['name' => '7º Ano A', 'year' => 2024],
        ];

        foreach ($classrooms as $c) {
            $classroom = Classroom::create($c);

            // Vincular todas as disciplinas a cada turma (Grade Curricular)
            $classroom->subjects()->attach($allSubjects->pluck('id'));
        }

        // 4. Criar Alunos (Nomes Clássicos/Históricos)
        $studentNames = [
            'Agostinho de Hipona',
            'Tomás de Aquino',
            'Bento de Núrsia',
            'Teresa de Ávila',
            'Catarina de Sena',
            'Luís de Camões',
            'Dante Alighieri',
            'Isabel de Castela',
            'Francisco de Assis'
        ];

        foreach ($studentNames as $name) {
            $student = Student::create([
                'name' => $name,
                'registration_number' => 'MAT-' . rand(1000, 9999)
            ]);

            // Matricular aluno em uma turma aleatória
            $randomClassroom = Classroom::inRandomOrder()->first();
            $randomClassroom->students()->attach($student->id, ['status' => 'active']);
        }

        // 5. Criar Algumas Avaliações e Notas Reais
        $activeClassrooms = Classroom::all();

        foreach ($activeClassrooms as $classroom) {
            foreach ($classroom->subjects as $subject) {
                // Criar 2 avaliações por disciplina no 1º Bimestre
                $eval1 = Evaluation::create([
                    'classroom_id' => $classroom->id,
                    'subject_id'   => $subject->id,
                    'title'        => 'Prova Mensal I',
                    'weight'       => 1.0,
                    'max_score'    => 100,
                    'bimester'     => 1,
                    'applied_at'   => now()->subDays(15),
                ]);

                $eval2 = Evaluation::create([
                    'classroom_id' => $classroom->id,
                    'subject_id'   => $subject->id,
                    'title'        => 'Trabalho Escrito',
                    'weight'       => 0.5,
                    'max_score'    => 100,
                    'bimester'     => 1,
                    'applied_at'   => now()->subDays(5),
                ]);

                // Lançar notas para todos os alunos daquela turma nestas avaliações
                foreach ($classroom->students as $student) {
                    Grade::create([
                        'student_id'    => $student->id,
                        'evaluation_id' => $eval1->id,
                        'score'         => rand(60, 100), // Notas entre 6 e 10
                    ]);

                    Grade::create([
                        'student_id'    => $student->id,
                        'evaluation_id' => $eval2->id,
                        'score'         => rand(40, 100),
                    ]);
                }
            }
        }
    }
}
