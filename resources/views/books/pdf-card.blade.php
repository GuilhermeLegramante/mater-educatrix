<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Ficha do Livro - {{ $book->title }}</title>
    <style>
        /* Configuração para impressão em folha 10x15cm */
        @page {
            margin: 0px;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #fff;
            font-family: 'Times New Roman', Times, serif;
            color: #000;
        }

        body {
            padding: 4px 6px;
            /* Margem interna reduzida para perfeito ajuste */
            box-sizing: border-box;
        }

        /* Estrutura principal da ficha */
        .card-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            page-break-inside: avoid !important;
            page-break-after: avoid !important;
        }

        .card-table td {
            vertical-align: top;
            padding: 1px 4px;
            word-wrap: break-word;
        }

        /* Linha divisória horizontal entre o cabeçalho e o corpo da ficha */
        .header-divider {
            border-bottom: 2px solid #000;
            padding-bottom: 3px !important;
        }

        /* Formatação de Fontes Compactas */
        .field-label {
            font-size: 9.5px;
            font-weight: normal;
            line-height: 1.15;
        }

        .field-value {
            font-size: 10px;
            font-weight: bold;
        }

        .title-value {
            font-weight: normal;
            text-decoration: underline;
        }

        /* Seção de Empréstimo */
        .loan-header {
            font-size: 9.5px;
            font-weight: normal;
            padding-top: 2px;
        }

        /* Coluna 'Devolver até' com linha vertical divisor de coluna */
        .border-left-divider {
            border-left: 1.5px solid #000;
        }
    </style>
</head>

<body>

    <table class="card-table">
        {{-- LINHA SUPERIOR: CABEÇALHO DO LIVRO --}}
        <tr>
            {{-- Lado Esquerdo: Título e Publicação --}}
            <td style="width: 55%;" class="header-divider">
                <div class="field-label">
                    Título: <span class="title-value">{{ $book->title }}</span>
                </div>
                @if ($book->sub_title)
                    <div class="field-label">{{ $book->sub_title }}</div>
                @endif
                <div class="field-label" style="margin-top: 2px;">
                    Publicação: <span
                        class="field-value">{{ $book->publisher ?? 'Ática' }}{{ $book->publication_year ? ', ' . $book->publication_year : '' }}</span>
                </div>
            </td>

            {{-- Lado Direito: Autor e Estante/Tipo --}}
            <td style="width: 45%;" class="header-divider">
                <div class="field-label">
                    Autor/Ilustr.:
                </div>
                <div class="field-value" style="font-size: 10.5px;">
                    {{ $book->author }}
                </div>
                <div class="field-label" style="margin-top: 2px;">
                    Estante: <span class="field-value">{{ $book->type ?? 'Ilustrado Infantil' }}</span>
                </div>
            </td>
        </tr>

        {{-- LINHA INFERIOR: TABELA DE EMPRÉSTIMO (2 COLUNAS) --}}
        <tr>
            {{-- Coluna 1: Nome do Aluno --}}
            <td style="width: 72%;">
                <div class="loan-header">Nome</div>
            </td>

            {{-- Coluna 2: Devolver até (com borda vertical à esquerda) --}}
            <td style="width: 28%;" class="border-left-divider">
                <div class="loan-header">Devolver até</div>
            </td>
        </tr>
    </table>

</body>

</html>
