<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Ficha do Livro - {{ $book->title }}</title>
    <style>
        /* Configuração estrita de página 10x15cm */
        @page {
            margin: 0;
            size: 10cm 15cm;
        }

        html,
        body {
            width: 10cm;
            height: 15cm;
            margin: 0;
            padding: 0;
            background-color: #fff;
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            overflow: hidden;
        }

        body {
            padding: 6px 8px;
            box-sizing: border-box;
        }

        /* Tabelas base */
        .card-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .card-table td {
            vertical-align: top;
            padding: 1px 4px;
            word-wrap: break-word;
        }

        /* Tipografia */
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

        .loan-header {
            font-size: 9.5px;
            font-weight: normal;
            padding-top: 2px;
        }

        /* Estilo da célula com a linha vertical estendida */
        .loan-column-divider {
            border-left: 1.5px solid #000;
            height: 13.2cm;
        }
    </style>
</head>

<body>

    {{-- TABELA 1: DADOS DO LIVRO (Larguras independentes: 50% / 50%) --}}
    <table class="card-table" style="margin-bottom: 4px;">
        <tr>
            {{-- Lado Esquerdo: Título e Publicação --}}
            <td style="width: 50%;">
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

            {{-- Lado Direito: Autor e Estante (Ganha 50% da largura da folha) --}}
            <td style="width: 50%;">
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
    </table>

    {{-- TABELA 2: ÁREA DE EMPRÉSTIMO (Larguras independentes: 70% / 30%) --}}
    <table class="card-table">
        <tr>
            {{-- Coluna 1: Nome do Aluno (70% da largura) --}}
            <td style="width: 70%;">
                <div class="loan-header">Nome</div>
            </td>

            {{-- Coluna 2: Devolver até (30% da largura com linha vertical longa) --}}
            <td style="width: 30%;" class="loan-column-divider">
                <div class="loan-header" style="padding-left: 4px;">Devolver até</div>
            </td>
        </tr>
    </table>

</body>

</html>
