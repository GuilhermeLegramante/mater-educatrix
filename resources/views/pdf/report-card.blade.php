<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Boletim Escolar - {{ $student->name }}</title>
    <style>
        /* 1. Ajuste as margens globais da página */
        @page {
            /* Deixamos as margens laterais e inferior, mas a superior será controlada no body */
            margin: 0mm 15mm 20mm 15mm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            background-color: #ffffff;
            font-size: 11pt;
            line-height: 1.4;

            /* CRUCIAL: Abre espaço no topo de TODAS as páginas para o cabeçalho fixo não soterrar o texto */
            margin-top: 35mm;
            margin-bottom: 0px;
        }

        /* 2. Torne a estrutura do cabeçalho FIXA */
        header {
            position: fixed;
            top: 15mm;
            /* Distância do topo físico da página */
            left: 0px;
            right: 0px;
            height: 20mm;
            /* Altura estimada do seu cabeçalho */

            /* Opcional: uma linha sutil ou espaçamento abaixo do cabeçalho em todas as páginas */
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 10px;
        }

        /* Garanta que a tabela interna do cabeçalho ocupe 100% */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px;
            /* Resetado pois o espaçamento agora é controlado pelo body */
        }

        .header-logo {
            width: 70px;
            text-align: left;
            vertical-align: middle;
        }

        .header-logo img {
            width: 65px;
            height: 65px;
        }

        .header-title {
            text-align: left;
            vertical-align: middle;
            padding-left: 15px;
        }

        .header-title h1 {
            font-size: 18pt;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #0f172a;
        }

        .header-title p {
            margin: 2px 0 0 0;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 4px;
            color: #64748b;
            /* Slate 500 */
            font-weight: bold;
        }

        .header-meta {
            text-align: right;
            vertical-align: bottom;
            font-size: 9pt;
            color: #64748b;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Bloco de Informações do Aluno */
        .student-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: #f8fafc;
            /* Slate 50 */
            border: 1px solid #e2e8f0;
            /* Slate 200 */
        }

        .student-box td {
            padding: 10px 15px;
            font-size: 10pt;
        }

        .label {
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            /* Slate 400 */
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
        }

        .value {
            font-weight: bold;
            color: #0f172a;
        }

        /* Tabela Principal de Notas e Conceitos */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
        }

        .report-table th {
            background-color: #0f172a;
            /* Navy 900 Principal */
            color: #ffffff;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 12px 10px;
            border: 1px solid #0f172a;
        }

        /* Alinhamento da disciplina à esquerda */
        .report-table th.subject-col,
        .report-table td.subject-col {
            text-align: left;
            padding-left: 15px;
        }

        .report-table td {
            padding: 10px 10px;
            border: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10pt;
        }

        /* Linhas alternadas para facilitar leitura */
        .report-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .subject-name {
            font-weight: bold;
            color: #0f172a;
        }

        .concept-badge {
            font-weight: 900;
            color: #d4af37;
            /* Dourado / Gold 500 */
            font-size: 11pt;
        }

        /* Classes de Destaque para a Situação Final */
        .status-final {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Seção de Relatos Observacionais / Preceptoria */
        .section-title {
            font-size: 11pt;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #0f172a;
            border-left: 3px solid #d4af37;
            padding-left: 8px;
            margin-top: 25px;
            margin-bottom: 15px;
            font-weight: bold;
            page-break-after: avoid;
            /* Evita título órfão no fim da página */
        }

        .preceptory-block {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            page-break-inside: avoid;
            /* Impede que quebre o relato no meio ao virar a página */
        }

        .preceptory-meta {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 5px;
        }

        .preceptory-content {
            font-style: italic;
            font-family: Georgia, serif;
            font-size: 10.5pt;
            color: #334155;
        }

        /* Tabela Específica para Ocorrências */
        .occurrence-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
        }

        .occurrence-table th {
            background-color: #475569;
            /* Slate 600 Sóbrio */
            color: #ffffff;
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px;
            border: 1px solid #475569;
        }

        .occurrence-table td {
            padding: 10px;
            border: 1px solid #e2e8f0;
            font-size: 9.5pt;
            vertical-align: top;
        }

        .occurrence-type {
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
            color: #0f172a;
        }

        .occurrence-desc {
            color: #334155;
            margin-bottom: 5px;
        }

        .occurrence-actions {
            font-size: 8.5pt;
            font-style: italic;
            color: #64748b;
            background-color: #f8fafc;
            padding: 6px 10px;
            border-left: 2px solid #cbd5e1;
            margin-top: 5px;
        }

        /* Utilitário para controle de quebra forçada */
        .page-break {
            page-break-before: always;
        }

        /* Área de Assinaturas no Rodapé */
        .footer-signatures {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-space {
            width: 45%;
            text-align: center;
            vertical-align: bottom;
            padding-top: 50px;
        }

        .signature-line {
            border-top: 1px solid #94a3b8;
            padding-top: 5px;
            font-size: 9pt;
            text-transform: uppercase;
            font-weight: bold;
            color: #475569;
        }

        /* Configuração do Rodapé Fixo (DomPDF) */
        footer {
            position: fixed;
            bottom: -10mm;
            /* Posiciona dentro da margem inferior de 20mm */
            left: 0px;
            right: 0px;
            height: 30px;
            border-top: 1px solid #e2e8f0;
            /* Slate 200 */
            padding-top: 8px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-info {
            text-align: left;
            font-size: 7.5pt;
            color: #94a3b8;
            /* Slate 400 */
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-info strong {
            color: #475569;
            /* Slate 600 */
        }

        .footer-page {
            text-align: right;
            font-size: 8pt;
            font-family: monospace;
            color: #64748b;
            /* Slate 500 */
        }

        /* Script Nativo do DomPDF para numeração de páginas dinâmicas (X de Y) */
        .page-number:before {
            content: counter(page);
        }

        .page-total:before {
            content: counter(pages);
        }

        .legend-box {
            width: 100%;
            padding: 10px 12px;
            background-color: #f8fafc;
            /* Slate 50 */
            border: 1px solid #e2e8f0;
            /* Slate 200 */
            border-left: 3px solid #64748b;
            /* Slate 500 (Dá o tom de nota informativa) */
            font-size: 7.5pt;
            color: #475569;
            /* Slate 600 */
            line-height: 1.4;
            margin-top: -25px;
            /* Cola a legenda logo abaixo da tabela de notas */
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <header>
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    <img src="{{ public_path('img/logo.png') }}" alt="Logo">
                </td>
                <td class="header-title">
                    <h1>Mater</h1>
                    <p>Educatrix</p>
                </td>
                <td class="header-meta">
                    <div style="font-size: 11pt; color: #0f172a; margin-bottom: 2px;">Boletim Escolar</div>
                    <div
                        style="font-size: 8pt; color: #475569; font-weight: normal; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                        Ano Letivo: <strong>{{ $settings?->current_year ?? now()->year }}</strong>
                        @if ($settings?->active_bimester)
                            • {{ $settings->active_bimester }}º Bimestre
                        @endif
                    </div>
                    <span style="font-size: 7.5pt; font-weight: normal; text-transform: none; color: #94a3b8;">
                        Emissão: {{ $date }}
                    </span>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <table class="footer-table">
            <tr>
                <td class="footer-info">
                    <strong>Mater Educatrix</strong> — Secretaria Acadêmica<br>
                    <span style="font-size: 6.5pt; letter-spacing: 0.5px;">Documento oficial gerado eletronicamente
                        através do sistema de gestão.</span>
                </td>
                <td class="footer-page">
                    Pág. <span class="page-number"></span> de <span class="page-total"></span>
                </td>
            </tr>
        </table>
    </footer>

    <table class="student-box">
        <tr>
            <td width="50%">
                <span class="label">Estudante</span>
                <span class="value" style="font-size: 11pt;">{{ $student->name }}</span>
            </td>

            <td width="25%">
                <span class="label">Turma</span>
                <span class="value">{{ $classroom->name }}</span>
            </td>

            <td width="25%">
                <span class="label">Ano / Bimestre</span>
                <span class="value">
                    {{ $settings?->current_year ?? now()->year }}
                    @if ($settings?->active_bimester)
                        — {{ $settings->active_bimester }}º Bim
                    @endif
                </span>
            </td>
        </tr>
    </table>

    {{-- SEÇÃO 1: RENDIMENTO POR DISCIPLINA (NOTAS) --}}
    @if ($showGrades)
        <div class="section-title">Rendimento por Disciplina</div>
        <table class="report-table">
            <thead>
                <tr>
                    <th class="subject-col" rowspan="2" width="28%" style="vertical-align: middle;">Disciplinas
                        Obrigatórias</th>
                    <th colspan="2" width="12%">1º Bim</th>
                    <th colspan="2" width="12%">2º Bim</th>
                    <th colspan="2" width="12%">3º Bim</th>
                    <th colspan="2" width="12%">4º Bim</th>
                    <th rowspan="2" width="11%"
                        style="background-color: #1e293b; border-color: #1e293b; vertical-align: middle;">Final</th>
                    <th rowspan="2" width="13%"
                        style="background-color: #1e293b; border-color: #1e293b; vertical-align: middle;">Situação</th>
                </tr>
                <tr>
                    <th style="font-size: 7.5pt; padding: 5px 0; background-color: #1e293b;">Cnc</th>
                    <th style="font-size: 7.5pt; padding: 5px 0; background-color: #334155;">Faltas</th>

                    <th style="font-size: 7.5pt; padding: 5px 0; background-color: #1e293b;">Cnc</th>
                    <th style="font-size: 7.5pt; padding: 5px 0; background-color: #334155;">Faltas</th>

                    <th style="font-size: 7.5pt; padding: 5px 0; background-color: #1e293b;">Cnc</th>
                    <th style="font-size: 7.5pt; padding: 5px 0; background-color: #334155;">Faltas</th>

                    <th style="font-size: 7.5pt; padding: 5px 0; background-color: #1e293b;">Cnc</th>
                    <th style="font-size: 7.5pt; padding: 5px 0; background-color: #334155;">Faltas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subjects as $subject)
                    <tr>
                        <td class="subject-col">
                            <span class="subject-name">{{ $subject->name }}</span>
                        </td>

                        {{-- Loop dos 4 Bimestres --}}
                        @for ($bimester = 1; $bimester <= 4; $bimester++)
                            <td>
                                <span class="concept-badge">
                                    {{ $student->getConsolidatedConcept($classroom->id, $subject->id, $bimester) }}
                                </span>
                            </td>
                            <td style="background-color: #fff; color: #64748b; font-size: 9pt; font-family: monospace;">
                                {{ $getAbsencesCount($subject->id, $bimester) }}
                            </td>
                        @endfor

                        <td style="background-color: #f8fafc; font-weight: bold;">
                            <span class="concept-badge" style="color: #0f172a;">
                                {{ method_exists($student, 'getFinalConcept') ? $student->getFinalConcept($classroom->id, $subject->id) : '-' }}
                            </span>
                        </td>

                        <td style="background-color: #f8fafc;">
                            <span class="status-final">
                                {{ method_exists($student, 'getSubjectStatus') ? $student->getSubjectStatus($classroom->id, $subject->id) : 'Cursando' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="legend-box">
            <strong>Nota informativa sobre o sistema de avaliação:</strong> Conforme estabelecido na Escola Mater
            Educatrix, as notas são atribuídas na forma de conceito (A, B, C, D, E e F). O conceito de corte para
            aprovação é C, o qual pode ser equiparado a uma nota entre 6,0 e 7,5 numa escala de 0 a 10. A avaliação leva
            em conta o desempenho em avaliações escritas, eventuais trabalhos propostos pelos professores, bem como
            aspectos qualitativos, incluindo participação em aula, realização de tarefas, comportamento, organização do
            caderno e zelo com os materiais pedagógicos.
        </div>
    @endif

    {{-- SEÇÃO 2: RELATOS DE PRECEPTORIA --}}
    @if ($showPreceptory && $student->preceptoryReports && $student->preceptoryReports->count() > 0)
        <div class="section-title">Parecer Descritivo</div>

        @foreach ($student->preceptoryReports as $relato)
            <div class="preceptory-block">
                <div class="preceptory-meta">
                    {{ $relato->bimester }}º Bimestre —
                    {{ $relato->subject ? $relato->subject->name : 'Desenvolvimento Geral' }}
                </div>
                <div class="preceptory-content">
                    "{!! nl2br(e($relato->content)) !!}"
                </div>
            </div>
        @endforeach
    @endif

    {{-- SEÇÃO 3: HISTÓRICO DISCIPLINAR / OCORRÊNCIAS --}}
    @if ($showOccurrences && $student->occurrences && $student->occurrences->count() > 0)
        <div class="section-title">Histórico Disciplinar e de Atendimentos</div>
        <table class="occurrence-table">
            <thead>
                <tr>
                    <th width="18%">Data / Hora</th>
                    <th width="22%">Classificação</th>
                    <th width="60%">Relato Oficial do Fato</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($student->occurrences as $occurrence)
                    <tr style="page-break-inside: avoid;">
                        <td style="text-align: center; font-family: monospace; font-size: 9pt; color: #475569;">
                            <strong>{{ $occurrence->date->format('d/m/Y') }}</strong>
                            {!! $occurrence->time
                                ? '<br><span style="font-size:8pt; color:#94a3b8;">às ' . substr($occurrence->time, 0, 5) . '</span>'
                                : '' !!}
                        </td>
                        <td>
                            <span class="occurrence-type">{{ $occurrence->type->name }}</span>
                            <br><span style="font-size: 7.5pt; color: #94a3b8; text-transform: uppercase;">Por:
                                {{ $occurrence->user?->name ?? 'Sistema' }}</span>
                        </td>
                        <td>
                            <div class="occurrence-desc">
                                {!! nl2br(e($occurrence->description)) !!}
                            </div>
                            @if ($occurrence->actions_taken)
                                <div class="occurrence-actions">
                                    <strong>Providências aplicadas:</strong> {{ $occurrence->actions_taken }}
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- BLOCO: AVALIAÇÃO DESCRITIVA / MATRIZ --}}
    @if ($showDescriptiveEvaluation && isset($descriptiveData))
        <div style="page-break-before: always; margin-top: 20px;">
            <h3
                style="text-align: center; font-size: 14px; text-transform: uppercase; color: #0f1a34; border-bottom: 2px solid #d97706; padding-bottom: 5px;">
                Matriz de Avaliação Descritiva
            </h3>

            @php
                $labels = [
                    'optimal' => 'Muito Bem / Sim',
                    'partial' => 'Em Parte / Às Vezes',
                    'critical' => 'Não / Raramente',
                ];
            @endphp

            @foreach ($descriptiveData['questions'] as $subjectId => $questions)
                @php
                    $subjectName = $subjects->find($subjectId)?->name ?? 'Desenvolvimento Pessoal & Conduta';
                @endphp

                <h4
                    style="font-size: 11px; background-color: #f1f5f9; padding: 6px; margin-top: 15px; text-transform: uppercase;">
                    {{ $subjectName }}
                </h4>

                <table style="width: 100%; border-collapse: collapse; font-size: 9px; margin-bottom: 10px;">
                    <thead>
                        <tr style="background-color: #0f1a34; color: #ffffff;">
                            <th style="padding: 5px; text-align: left; width: 50%;">Pergunta / Critério</th>
                            <th style="padding: 5px; text-align: center; width: 12.5%;">1º Bim</th>
                            <th style="padding: 5px; text-align: center; width: 12.5%;">2º Bim</th>
                            <th style="padding: 5px; text-align: center; width: 12.5%;">3º Bim</th>
                            <th style="padding: 5px; text-align: center; width: 12.5%;">4º Bim</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($questions as $q)
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 5px; text-align: left;">{{ $q->question_text }}</td>
                                @for ($b = 1; $b <= 4; $b++)
                                    @php
                                        $key = $q->id . '_' . $b;
                                        $rating = $descriptiveData['evaluations'][$key]->rating ?? null;
                                    @endphp
                                    <td style="padding: 5px; text-align: center; font-weight: bold;">
                                        {{ $rating ? $labels[$rating] ?? $rating : '-' }}
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    @endif

    <table class="footer-signatures">
        <tr>
            <td class="signature-space">
                <div class="signature-line">Assinatura da Direção / Coordenação</div>
            </td>
            <td width="10%"></td>
            <td class="signature-space">
                <div class="signature-line">Assinatura do Tutor da Turma</div>
            </td>
        </tr>
    </table>

</body>

</html>
