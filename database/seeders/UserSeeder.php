<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Executa o seed para criação dos usuários iniciais.
     */
    public function run(): void
    {
        // 1. Definição da lista de usuários base do sistema
        $users = [
            [
                'name'     => 'Administrador Master',
                'email'    => 'admin@escolamatereducatrix.com.br',
                'password' => Hash::make('Admin@2026'),
                'role'     => 'admin',
            ],
            [
                'name'     => 'Coordenação Pedagógica',
                'email'    => 'coordenacao@escolamatereducatrix.com.br',
                'password' => Hash::make('Coord@2026'),
                'role'     => 'admin',
            ],
            [
                'name'     => 'Secretaria Escolar',
                'email'    => 'secretaria@escolamatereducatrix.com.br',
                'password' => Hash::make('Secretaria@2026'),
                'role'     => 'admin',
            ],
            [
                'name'     => 'Aline',
                'email'    => 'aline@escolamatereducatrix.com.br',
                'password' => Hash::make('Aline@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Elisa',
                'email'    => 'elisa@escolamatereducatrix.com.br',
                'password' => Hash::make('Elisa@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Edineia',
                'email'    => 'edineia@escolamatereducatrix.com.br',
                'password' => Hash::make('Edineia@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Lucas Siduoski',
                'email'    => 'lucas@escolamatereducatrix.com.br',
                'password' => Hash::make('Lucas@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Iolanda',
                'email'    => 'iolanda@escolamatereducatrix.com.br',
                'password' => Hash::make('Iolanda@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Maico',
                'email'    => 'maico@escolamatereducatrix.com.br',
                'password' => Hash::make('Maico@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Simone',
                'email'    => 'simone@escolamatereducatrix.com.br',
                'password' => Hash::make('Simone@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Maiara',
                'email'    => 'maiara@escolamatereducatrix.com.br',
                'password' => Hash::make('Maiara@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Silviane',
                'email'    => 'silviane@escolamatereducatrix.com.br',
                'password' => Hash::make('Silviane@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Luma',
                'email'    => 'luma@escolamatereducatrix.com.br',
                'password' => Hash::make('Luma@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Rafaella',
                'email'    => 'rafaella@escolamatereducatrix.com.br',
                'password' => Hash::make('Rafaella@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Cristina',
                'email'    => 'cristina@escolamatereducatrix.com.br',
                'password' => Hash::make('Cristina@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Pablo',
                'email'    => 'pablo@escolamatereducatrix.com.br',
                'password' => Hash::make('Pablo@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Graziele',
                'email'    => 'graziele@escolamatereducatrix.com.br',
                'password' => Hash::make('Graziele@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Joelson',
                'email'    => 'joelson@escolamatereducatrix.com.br',
                'password' => Hash::make('Joelson@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Lucas Ribeiro',
                'email'    => 'ribeiro@escolamatereducatrix.com.br',
                'password' => Hash::make('Ribeiro@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Rafael',
                'email'    => 'rafael@escolamatereducatrix.com.br',
                'password' => Hash::make('Rafael@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Pietro',
                'email'    => 'pietro@escolamatereducatrix.com.br',
                'password' => Hash::make('Pietro@2026'),
                'role'     => 'admin',
            ],
            [
                'name'     => 'Juliana',
                'email'    => 'juliana@escolamatereducatrix.com.br',
                'password' => Hash::make('Juliana@2026'),
                'role'     => 'admin',
            ],
            [
                'name'     => 'Mara',
                'email'    => 'mara@escolamatereducatrix.com.br',
                'password' => Hash::make('Mara@2026'),
                'role'     => 'teacher',
            ],
            [
                'name'     => 'Ane',
                'email'    => 'ane@escolamatereducatrix.com.br',
                'password' => Hash::make('Ane@2026'),
                'role'     => 'teacher',
            ],

        ];

        // 2. Itera sobre cada registro e insere de forma segura
        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']], // Critério de busca (evita duplicação)
                [
                    'name'     => $userData['name'],
                    'password' => $userData['password'],
                    'role'     => $userData['role'],
                ]
            );
        }

        // Mapeamento dos professores por Ano e Disciplina
        $assignmentsByYear = [
            1 => [
                'Linguagem'        => ['Elisa'],
                'Matemática'       => ['Elisa'],
                'Ciências'         => ['Elisa'],
                'Estudos Sociais'  => ['Elisa'],
                'Ensino Religioso' => ['Elisa'],
                'Artes'            => ['Edineia'],
                'Música'           => ['Lucas Siduoski', 'Iolanda'],
                'Ed. Física'       => ['Maico'],
            ],
            2 => [
                'Linguagem'        => ['Simone'],
                'Matemática'       => ['Simone'],
                'Ciências'         => ['Simone'],
                'Estudos Sociais'  => ['Simone'],
                'Ensino Religioso' => ['Simone'],
                'Artes'            => ['Edineia'],
                'Música'           => ['Lucas Siduoski', 'Iolanda'],
                'Ed. Física'       => ['Maico'],
            ],
            3 => [
                'Linguagem'        => ['Maiara'],
                'Matemática'       => ['Silviane'],
                'Ciências'         => ['Maiara'],
                'Estudos Sociais'  => ['Maiara'],
                'Ensino Religioso' => ['Maiara'],
                'Artes'            => ['Edineia'],
                'Música'           => ['Lucas Siduoski', 'Iolanda'],
                'Ed. Física'       => ['Maico'],
                'Inglês'           => ['Luma'],
            ],
            4 => [
                'Português'        => ['Rafaella'],
                'Matemática'       => ['Rafaella'],
                'Ciências'         => ['Cristina'],
                'Estudos Sociais'  => ['Pablo'],
                'Ensino Religioso' => ['Rafaella'],
                'Artes'            => ['Edineia'],
                'Música'           => ['Lucas Siduoski', 'Iolanda'],
                'Ed. Física'       => ['Maico'],
                'Inglês'           => ['Luma'],
            ],
            5 => [
                'Português'        => ['Graziele'],
                'Matemática'       => ['Graziele'],
                'Ciências'         => ['Cristina'],
                'Estudos Sociais'  => ['Pablo'],
                'Ensino Religioso' => ['Joelson'],
                'Artes'            => ['Edineia'],
                'Música'           => ['Lucas Siduoski'],
                'Ed. Física'       => ['Maico', 'Pietro'],
                'Inglês'           => ['Luma'],
            ],
            6 => [
                'Português'        => ['Aline'],
                'Matemática'       => ['Silviane'],
                'Ciências'         => ['Cristina'],
                'História'          => ['Pablo'],
                'Geografia'        => ['Lucas Ribeiro'],
                'Ensino Religioso' => ['Joelson'],
                'Artes'            => ['Edineia'],
                'Música'           => ['Lucas Siduoski'],
                'Ed. Física'       => ['Maico', 'Pietro'],
                'Inglês'           => ['Luma'],
                'Latim'            => ['Rafael'],
            ],
            7 => [
                'Português'        => ['Juliana'],
                'Matemática'       => ['Silviane'],
                'Ciências'         => ['Cristina'],
                'História'          => ['Pablo'],
                'Geografia'        => ['Lucas Ribeiro'],
                'Ensino Religioso' => ['Pablo'],
                'Artes'            => ['Edineia'],
                'Música'           => ['Lucas Siduoski'],
                'Ed. Física'       => ['Maico', 'Pietro'],
                'Inglês'           => ['Luma'],
                'Latim'            => ['Rafael'],
                'Flauta'           => ['Iolanda'],
            ],
            8 => [
                'Português'        => ['Juliana'],
                'Matemática'       => ['Silviane'],
                'Ciências'         => ['Cristina'],
                'História'          => ['Pablo'],
                'Geografia'        => ['Lucas Ribeiro'],
                'Ensino Religioso' => ['Pablo'],
                'Artes'            => ['Edineia'],
                'Música'           => ['Lucas Siduoski'],
                'Ed. Física'       => ['Maico', 'Pietro'],
                'Inglês'           => ['Luma'],
                'Latim'            => ['Rafael'],
                'Flauta'           => ['Iolanda'],
            ],
        ];

        // Processa cada turma cadastrada no banco
        $classrooms = Classroom::all();

        foreach ($classrooms as $classroom) {
            // Identifica o número do ano da turma (ex: "1º Ano" -> 1)
            preg_match('/\d+/', $classroom->name, $matches);
            $yearNumber = isset($matches[0]) ? (int) $matches[0] : null;

            if (!$yearNumber || !isset($assignmentsByYear[$yearNumber])) {
                continue;
            }

            $schedule = $assignmentsByYear[$yearNumber];

            foreach ($schedule as $subjectName => $teacherNames) {
                // Localiza a disciplina pelo nome
                $subject = Subject::where('name', $subjectName)->first();

                if (!$subject) {
                    continue;
                }

                // Localiza os IDs dos professores
                $teacherIds = User::whereIn('name', $teacherNames)->pluck('id')->toArray();

                if (empty($teacherIds)) {
                    continue;
                }

                // Associa a turma, disciplina e professor(es) na tabela pivô
                // Exemplo se você usa relacionamento $classroom->teachers() ou pivô tripla:
                foreach ($teacherIds as $teacherId) {
                    // Exemplo para tabela pivot de atrelamento:
                    $classroom->subjects()->updateExistingPivot($subject->id, [
                        'teacher_id' => $teacherId,
                    ]);
                }
            }
        }
    }
}
