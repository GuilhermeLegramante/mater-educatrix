<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Ficha do Livro - {{ $book->title }}</title>
    <style>
        /* Remove as margens da folha no leitor de PDF */
        @page {
            margin: 0px;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #fff;
            font-family: 'Times New Roman', Times, serif;
            color: #000;
        }

        body {
            padding: 8px; /* Margem interna para o tamanho 102mm x 152mm */
            box-sizing: border-box;
        }

        /* Estrutura principal da tabela */
        .card-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000;
            table-layout: fixed;
            page-break-inside: avoid !important;
            page-break-after: avoid !important;
        }

        .card-table td {
            border: 1px solid #000;
            vertical-align: top;
            padding: 4px 6px;
            word-wrap: break-word;
        }
        
        /* Fontes e alinhamento dos campos */
        .title-label {
            font-size: 11px;
            font-weight: normal;
            line-height: 1.2;
        }

        .title-value {
            font-size: 12px;
            text-decoration: underline;
        }

        .publisher-text {
            font-size: 10px;
            margin-top: 4px;
            text-decoration: underline;
        }

        .author-text {
            font-size: 12px;
            font-weight: bold;
            line-height: 1.2;
        }

        .category-text {
            font-size: 11px;
            margin-top: 4px;
        }

        .section-header {
            font-size: 11px;
        }
        
        /* Altura dos campos de empréstimo */
        .loan-area {
            height: 280px;
        }

        /* REMOÇÃO DA BORDA DIVISÓRIA ENTRE 'NOME' E A COLUNA CENTRAL */
        .no-border-right {
            border-right: none !important;
        }

        .no-border-left {
            border-left: none !important;
        }
    </style>
</head>
<body>

    <table class="card-table">
        {{-- LINHA SUPERIOR: INFORMAÇÕES DO LIVRO --}}
        <tr>
            {{-- Coluna Título / Publicação (58% de largura) --}}
            <td style="width: 58%; height: 60px;">
                <div class="title-label">
                    Título: <span class="title-value">{{ $book->title }}</span>
                </div>
                <div class="publisher-text">
                    {{ $book->publisher ?? 'Editora' }}{{ $book->publication_city ? ', ' . $book->publication_city : '' }}{{ $book->publication_year ? ', ' . $book->publication_year : '' }}
                </div>
            </td>

            {{-- Coluna Autor / Categoria (42% de largura) --}}
            <td style="width: 42%; height: 60px;" colspan="2">
                <div class="author-text">
                    {{ $book->author }}
                </div>
                <div class="category-text">
                    {{ $book->type }}
                </div>
            </td>
        </tr>

        {{-- LINHA INFERIOR: CARTÃO DE EMPRÉSTIMO (3 COLUNAS) --}}
        <tr>
            {{-- 1. Coluna do Nome (sem borda na direita) --}}
            <td style="width: 58%;" class="loan-area no-border-right">
                <div class="section-header">Nome</div>
            </td>

            {{-- 2. Coluna do Meio (sem borda na esquerda, mas MANTÉM a borda na direita) --}}
            <td style="width: 14%;" class="loan-area no-border-left">
                &nbsp;
            </td>

            {{-- 3. Coluna Devolver até --}}
            <td style="width: 28%;" class="loan-area">
                <div class="section-header">Devolver até</div>
            </td>
        </tr>
    </table>

</body>
</html>