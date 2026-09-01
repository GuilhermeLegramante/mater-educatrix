<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Ficha do Livro - {{ $book->title }}</title>
    <style>
        /* Configuração de página 10x15cm */
        @page {
            margin: 0;
            size: 7cm 14cm;
        }

        html,
        body {
            width: 7cm;
            height: 14cm;
            margin: 0;
            padding: 0;
            background-color: #fff;
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            overflow: hidden;
        }

        body {
            /* Margem superior para posicionar o texto no topo da ficha */
            padding: 3.5mm 6px 0px 6px;
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
            padding: 0px 3px;
            word-wrap: break-word;
        }

        /* Cabeçalho com fonte reduzida e linha horizontal inferior */
        .header-table {
            margin-top: -3%;
            border-bottom: 0.5px solid #000;
            /* Linha horizontal bem fina */
            padding-bottom: 3px;
            margin-bottom: 2px;
        }

        /* Tipografia Reduzida do Cabeçalho */
        .field-label {
            font-size: 7px;
            /* Fonte reduzida para rótulos */
            font-weight: normal;
            line-height: 1.05;
        }

        .field-value {
            font-size: 7px;
            /* Fonte reduzida para valores */
            font-weight: bold;
        }

        .title-value {
            font-weight: normal;
            text-decoration: underline;
        }

        /* Truncar textos longos em 1 linha */
        .truncate-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            display: block;
        }

        /* Área de Empréstimo */
        .loan-header {
            font-size: 7px;
            font-weight: normal;
            padding-top: 1px;
        }

        /* Linha Vertical Extensa */
        .loan-column-divider {
            border-left: 1px solid #000;
            height: 12.6cm;
            /* Compensação da linha horizontal e paddings */
        }
    </style>
</head>

<body>

    {{-- TABELA 1: CABEÇALHO DO LIVRO (Fonte menor + Linha horizontal abaixo) --}}
    <table class="card-table header-table">
        <tr>
            {{-- Lado Esquerdo: Título e Publicação --}}
            <td style="width: 55%;">
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

            {{-- Lado Direito: Autor e Estante --}}
            <td style="width: 45%;">
                <div class="field-label">Autor/Ilustr.:</div>
                <div class="field-value truncate-text" style="font-size: 7px;">
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
            {{-- Coluna Nome --}}
            <td style="width: 70%;">
                <div class="loan-header">Nome</div>
            </td>

            {{-- Coluna Devolver até --}}
            <td style="width: 30%;" class="loan-column-divider">
                <div class="loan-header" style="padding-left: 4px;">Devolver até</div>
            </td>
        </tr>
    </table>

</body>

</html>
