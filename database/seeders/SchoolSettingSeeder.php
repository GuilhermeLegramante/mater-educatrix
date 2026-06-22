<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // O updateOrCreate garante que se o registro já existir, ele apenas atualiza as datas
        SchoolSetting::updateOrCreate(
            ['id' => 1], // Alvo: sempre o primeiro registro de configurações
            [
                'current_year'     => 2026,
                'active_bimester'   => 1, // Bimestre inicial ativo no sistema

                // Definição cronológica dos Bimestres para o Ano Letivo
                // Altere as datas abaixo de acordo com o calendário real da Mater Educatrix
                'bimester_1_start' => '2026-02-05',
                'bimester_1_end'   => '2026-04-17',

                'bimester_2_start' => '2026-04-22',
                'bimester_2_end'   => '2026-07-03',

                'bimester_3_start' => '2026-07-22',
                'bimester_3_end'   => '2026-10-02',

                'bimester_4_start' => '2026-10-07',
                'bimester_4_end'   => '2026-12-11',
            ]
        );
    }
}
