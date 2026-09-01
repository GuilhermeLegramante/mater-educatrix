<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Ficha do Livro - {{ $book->title }}</title>
    <style>
        /* Configuração estrita de página 10x15cm sem margens externas */
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
            /* Evita a criação de páginas extras por transbordamento */
        }

        body {
            padding: 6px 8px;
            box-sizing: border-box;
        }

        /* Estrutura principal com altura delimitada */
        .card-container {
            width: 100%;
            height: 14.2cm;
            /* Define a altura máxima para caber perfeitamente na página */
            border-collapse: collapse;
            table-layout: fixed;
        }

        .card-container td {
            vertical-align: top;
            padding: 1px 4px;
            word-wrap: break-word;
        }

        /* Tipografia compacta */
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

        /* Linha Vertical Extensa sem estourar a página */
        .border-left-divider {
            border-left: 1.5px solid #000;
        }
    </style>
</head>

<body>

    <table class="card-container">
        {{-- LINHA SUPERIOR: CABEÇALHO DO LIVRO --}}
        <tr style="height: 1.2cm;">
            {{-- Lado Esquerdo: Título e Publicação --}}
            <td style="width: 58%;">
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

            {{-- Lado Direito: Autor e Estante --}}
            <td style="width: 42%;">
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

        {{-- LINHA INFERIOR: ÁREA PAUTADA COM LINHA VERTICAL --}}
        <tr>
            {{-- Coluna 1: Nome do Aluno --}}
            <td style="width: 58%;">
                <div class="loan-header">Nome</div>
            </td>

            {{-- Coluna 2: Devolver até (Com a linha vertical estendida) --}}
            <td style="width: 42%;" class="border-left-divider">
                <div class="loan-header" style="padding-left: 4px;">Devolver até</div>
            </td>
        </tr>
    </table>

</body>

</html>
