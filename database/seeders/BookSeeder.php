<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = storage_path('app/livros.csv');

        // 1. Verifica se o arquivo CSV foi colocado na pasta storage/app
        if (!file_exists($filePath)) {
            $this->command->error("Arquivo não encontrado em: {$filePath}");
            $this->command->info("Por favor, converta a planilha para 'livros.csv' e salve em storage/app/");
            return;
        }

        // 2. Abre o arquivo CSV para leitura
        $file = fopen($filePath, 'r');

        // 3. Lê e descarta o cabeçalho (a primeira linha com os nomes das colunas)
        $header = fgetcsv($file, 2000, ',');

        // Trata a possibilidade do separador ser ponto e vírgula (;) em vez de vírgula (,)
        if ($header && count($header) === 1) {
            rewind($file);
            $header = fgetcsv($file, 2000, ';');
            $delimiter = ';';
        } else {
            $delimiter = ',';
        }

        $imported = 0;

        // Desativa checagens de chave estrangeira e limpa a tabela antes de importar
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Book::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 4. Converte e lê linha por linha
        while (($row = fgetcsv($file, 2000, $delimiter)) !== false) {

            // Pula linhas totalmente vazias ou sem título (Coluna 3)
            if (!isset($row[3]) || empty(trim($row[3]))) {
                continue;
            }

            // Mapeamento e limpeza dos campos
            Book::create([
                'isbn'               => !empty($row[1]) ? trim($row[1]) : null,
                'author'             => !empty($row[2]) ? trim($row[2]) : 'Autor Desconhecido',
                'title'              => trim($row[3]),
                'publisher'          => !empty($row[4]) ? trim($row[4]) : null,
                'publication_year'   => !empty($row[5]) ? trim($row[5]) : null,
                'publication_city'   => !empty($row[6]) ? trim($row[6]) : null,
                'first_edition_year' => !empty($row[7]) ? trim($row[7]) : null,
                'type'               => !empty($row[8]) ? trim($row[8]) : 'Literatura',
                'discipline'         => !empty($row[9]) ? trim($row[9]) : null,
                'location_shelf'     => !empty($row[10]) ? trim($row[10]) : null,
                'status'             => 'available',
            ]);

            $imported++;
        }

        fclose($file);

        $this->command->info("Sucesso! {$imported} livros foram importados para o acervo.");
    }
}
