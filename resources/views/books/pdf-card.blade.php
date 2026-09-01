<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Ficha do Livro - {{ $book->title }}</title>
    <style>
        /* Configuração de página para 10x15cm sem margens externas */
        @page {
            margin: 0px;
            size: 10cm 15cm;
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
            padding: 6px 8px;
            box-sizing: border-box;
        }

        /* Estrutura principal com ocupação de altura total */
        .card-container {
            width: 100%;
            height: 98%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .card-container td {
            vertical-align: top;
            padding: 1px 4px;
            word-wrap: break-word;
        }

        /* Formatação de Tipografia */
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

        /* Linha Vertical Extensa para a Coluna 'Devolver até' */
        .border-left-divider {
            border-left: 1.5px solid #000;
        }
    </style>
</head>

<body>

    <table class="card-container">
        {{-- LINHA SUPERIOR: DADOS DO LIVRO (Sem borda horizontal inferior) --}}
        <tr style="height: 45px;">
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

        {{-- LINHA INFERIOR: CABEÇALHOS E LINHA VERTICAL ATÉ O FINAL --}}
        <tr>
            {{-- Coluna 1: Nome do Aluno --}}
            <td style="width: 58%; height: 100%;">
                <div class="loan-header">Nome</div>
            </td>

            {{-- Coluna 2: Devolver até (Com linha vertical contínua até o rodapé) --}}
            <td style="width: 42%; height: 100%;" class="border-left-divider">
                <div class="loan-header" style="padding-left: 4px;">Devolver até</div>
            </td>
        </tr>
    </table>

</body>

</html>
