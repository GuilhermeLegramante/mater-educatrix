<?php

namespace Database\Seeders;

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
            ],
            [
                'name'     => 'Coordenação Pedagógica',
                'email'    => 'coordenacao@escolamatereducatrix.com.br',
                'password' => Hash::make('Coord@2026'),
            ],
            [
                'name'     => 'Professor de Música',
                'email'    => 'musica@escolamatereducatrix.com.br',
                'password' => Hash::make('Musica@2026'),
            ],
            [
                'name'     => 'Secretaria Escolar',
                'email'    => 'secretaria@escolamatereducatrix.com.br',
                'password' => Hash::make('Secre@2026'),
            ],
        ];

        // 2. Itera sobre cada registro e insere de forma segura
        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']], // Critério de busca (evita duplicação)
                [
                    'name'     => $userData['name'],
                    'password' => $userData['password'],
                ]
            );
        }
    }
}
