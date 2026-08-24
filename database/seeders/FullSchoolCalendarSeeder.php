<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\SchoolDay;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class FullSchoolCalendarSeeder extends Seeder
{
    /**
     * Executa o seeder para popular o calendário anual.
     */
    public function run(): void
    {
        // 1. Recupera todas as turmas cadastradas no sistema
        $classrooms = Classroom::all();

        if ($classrooms->isEmpty()) {
            $this->command->warn('Nenhuma turma encontrada! Cadastre turmas antes de rodar este Seeder.');
            return;
        }

        // 2. Define o ano letivo atual dinamicamente ou fixo (Ex: 2026)
        $year = date('Y'); // Pode ser alterado para um ano específico, se necessário

        // Período letivo padrão: de 01 de Fevereiro a 22 de Dezembro
        $startOfYear = Carbon::create($year, 2, 1);
        $endOfYear = Carbon::create($year, 12, 22);

        // Cria o gerador de período dia por dia
        $period = CarbonPeriod::create($startOfYear, $endOfYear);

        // 3. Mapeamento de feriados nacionais fixos (Mês-Dia) para pular automaticamente
        $feriadosFixos = [
            '01-01', // Ano Novo
            '04-21', // Tiradentes
            '05-01', // Dia do Trabalho
            '09-07', // Independência do Brasil
            '10-12', // Nossa Sra. Aparecida / Dia das Crianças
            '11-02', // Finados
            '11-15', // Proclamação da República
            '11-20', // Dia da Consciência Negra
            '12-25', // Natal
        ];

        $this->command->info("Iniciando a geração do calendário letivo para o ano de {$year}...");

        $totalCriados = 0;
        $totalIgnorados = 0;

        // Loop por todas as turmas
        foreach ($classrooms as $classroom) {
            foreach ($period as $date) {

                // Regra A: Pular Finais de Semana (Sábado = 6, Domingo = 0)
                if ($date->isWeekend()) {
                    continue;
                }

                // Regra B: Pular o Recesso Escolar do Meio do Ano (Ex: 13 a 26 de Julho)
                if ($date->month == 7 && $date->day >= 13 && $date->day <= 26) {
                    continue;
                }

                // Regra C: Pular Feriados Nacionais Fixos
                if (in_array($date->format('m-d'), $feriadosFixos)) {
                    continue;
                }

                // Regra Antiduplicação: O 'firstOrCreate' verifica se já existe
                // a combinação exata de classroom_id e data antes de inserir.
                $schoolDay = SchoolDay::firstOrCreate([
                    'classroom_id' => $classroom->id,
                    'date'         => $date->format('Y-m-d'),
                ]);

                // Incrementa os contadores para exibir um relatório limpo no terminal
                if ($schoolDay->wasRecentlyCreated) {
                    $totalCriados++;
                } else {
                    $totalIgnorados++;
                }
            }
        }

        $this->command->info("Calendário processado!");
        $this->command->line("<info>✔</info> {$totalCriados} novos dias letivos criados.");
        $this->command->line("<comment>➔</comment> {$totalIgnorados} dias ignorados por já existirem no banco.");
    }
}
