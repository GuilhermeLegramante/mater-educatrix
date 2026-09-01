<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Ficha do Livro - {{ $book->title }}</title>
    <style>
        /* Configuração estrita para folha 10x15cm */
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
            padding: 5px 8px;
            box-sizing: border-box;
        }

        /* Estrutura das Tabelas */
        .card-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .card-table td {
            vertical-align: top;
            padding: 0px 4px;
            word-wrap: break-word;
        }

        /* Tipografia Compacta do Cabeçalho */
        .field-label {
            font-size: 9px;
            font-weight: normal;
            line-height: 1.1;
        }

        .field-value {
            font-size: 9.5px;
            font-weight: bold;
        }

        .title-value {
            font-weight: normal;
            text-decoration: underline;
        }

        /* Classe para impedir quebra de linha e truncar textos longos de autor */
        .truncate-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            display: block;
        }

        /* Área de Empréstimo */
        .loan-header {
            font-size: 9px;
            font-weight: normal;
            padding-top: 2px;
        }

        /* Linha Vertical que vai até o final da página */
        .loan-column-divider {
            border-left: 1.5px solid #000;
            height: 13.3cm;
        }
    </style>
</head>

<body>

    {{-- TABELA 1: CABEÇALHO DO LIVRO (Limitado a 3 linhas de altura) --}}
    <table class="card-table" style="margin-bottom: 2px;">
        <tr style="height: 1.1cm;">
            {{-- Lado Esquerdo: Título e Publicação (52% da largura) --}}
            <td style="width: 52%;">
                <div class="field-label truncate-text">
                    Título: <span class="title-value">{{ $book->title }}</span>
                </div>
                @if ($book->sub_title)
                    <div class="field-label truncate-text">{{ $book->sub_title }}</div>
                @endif
                <div class="field-label truncate-text">
                    Publicação: <span
                        class="field-value">{{ $book->publisher ?? 'Ática' }}{{ $book->publication_year ? ', ' . $book->publication_year : '' }}</span>
                </div>
            </td>

            {{-- Lado Direito: Autor e Estante (48% da largura) --}}
            <td style="width: 48%;">
                <div class="field-label">Autor/Ilustr.:</div>
                {{-- O nome do autor é limitado a 1 linha única; se for longo, exibe '...' no final --}}
                <div class="field-value truncate-text" style="font-size: 9.5px;">
                    {{ $book->author }}
                </div>
                <div class="field-label truncate-text">
                    Estante: <span class="field-value">{{ $book->type ?? 'Ilustrado Infantil' }}</span>
                </div>
            </td>
        </tr>
    </table>

    {{-- TABELA 2: ÁREA PAUTADA COM LINHA VERTICAL --}}
    <table class="card-table">
        <tr>
            {{-- Coluna Nome (70% de largura) --}}
            <td style="width: 70%;">
                <div class="loan-header">Nome</div>
            </td>

            {{-- Coluna Devolver até (30% de largura com borda estendida) --}}
            <td style="width: 30%;" class="loan-column-divider">
                <div class="loan-header" style="padding-left: 4px;">Devolver até</div>
            </td>
        </tr>
    </table>

</body>

</html>
