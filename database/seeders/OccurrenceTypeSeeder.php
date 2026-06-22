<?php

namespace Database\Seeders;

use App\Models\OccurrenceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OccurrenceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Atendimento na Enfermaria', 'color' => 'emerald'], // Verde para saúde
            ['name' => 'Atraso / Entrada Tardia', 'color' => 'amber'],    // Amarelo aviso
            ['name' => 'Falta de Material / Uniforme', 'color' => 'slate'],
            ['name' => 'Afastamento por Indisciplina', 'color' => 'orange'],
            ['name' => 'Conflito / Briga entre Alunos', 'color' => 'rose'], // Vermelho grave
            ['name' => 'Elogio / Destaque de Virtude', 'color' => 'violet'], // Cor nobre para mérito acadêmico
        ];

        foreach ($types as $type) {
            OccurrenceType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
