@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8 animate-fade-in pb-12">

        {{-- ----------------------------------------------------------------- --}}
        {{-- VISÃO DO ADMINISTRADOR: Panorama Acadêmico Completo --}}
        {{-- ----------------------------------------------------------------- --}}
        @if (auth()->user()->isAdmin())
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-slate-100 pb-4">
                <div>
                    <h1 class="font-classic text-3xl text-navy-900 tracking-wide">Panorama Acadêmico</h1>
                    <p class="text-slate-500 text-sm">Visão analítica integrada.</p>
                </div>
                <div
                    class="mt-4 md:mt-0 bg-white border border-slate-200 px-4 py-2 rounded-xl text-xs font-bold text-slate-500 uppercase tracking-wider">
                    Ano Letivo: 2026 • {{ date('d/m/Y') }}
                </div>
            </div>

            <!-- Cards Indicadores Primários -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-gold-500 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total de Alunos</span>
                        <div class="text-3xl font-black text-navy-900 mt-1">{{ $totalStudents }}</div>
                        <span class="text-[10px] text-emerald-500 font-bold">↑ 12% este ano</span>
                    </div>
                    <div class="text-2xl opacity-40">🎓</div>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-navy-900 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Média Consolidada</span>
                        <div class="text-3xl font-black text-navy-900 mt-1">Conceito {{ $globalConcept }}</div>
                        <span class="text-[10px] text-slate-400 font-bold">Média numérica:
                            {{ number_format($averageScore, 1) }}%</span>
                    </div>
                    <div class="text-2xl opacity-40">🏛️</div>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-gold-500 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Retenção Escolar</span>
                        <div class="text-3xl font-black text-navy-900 mt-1">94.8%</div>
                        <span class="text-[10px] text-emerald-500 font-bold">Meta atingida</span>
                    </div>
                    <div class="text-2xl opacity-40">📈</div>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-navy-900 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Parceria Familiar</span>
                        <div class="text-3xl font-black text-navy-900 mt-1">92.1%</div>
                        <span class="text-[10px] text-gold-600 font-bold">Presença em Preceptorias</span>
                    </div>
                    <div class="text-2xl opacity-40">🤝</div>
                </div>
            </div>

            <!-- Gráficos e Desempenho -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between min-h-[350px]">
                    <div>
                        <h3 class="font-classic text-md font-bold text-navy uppercase tracking-wider mb-1">
                            Aproveitamento por Ciclo
                        </h3>
                        <p class="text-xs text-slate-400 mb-6">Média de notas consolidadas por segmento de ensino.</p>
                    </div>

                    <div class="h-48 flex items-end justify-around gap-4 px-2 border-b border-slate-100 pb-2">
                        @php
                            $cyclesPerformance = [
                                'Fund. I' => ['score' => 86, 'color' => 'bg-navy'],
                                'Fund. II' => ['score' => 74, 'color' => 'bg-gold-500'],
                                'Médio' => ['score' => 81, 'color' => 'bg-slate-700'],
                            ];
                        @endphp

                        @foreach ($cyclesPerformance as $cycle => $data)
                            <div class="flex-1 flex flex-col items-center gap-2 group h-full justify-end">
                                <span
                                    class="text-xs font-mono font-bold text-navy opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    {{ $data['score'] }}%
                                </span>

                                <div class="{{ $data['color'] }} w-full rounded-t-lg transition-all duration-500 group-hover:brightness-110 shadow-sm"
                                    style="height: {{ $data['score'] }}%">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-around text-center mt-3">
                        @foreach ($cyclesPerformance as $cycle => $data)
                            <span class="flex-1 text-[10px] font-black uppercase text-slate-400 tracking-tighter">
                                {{ $cycle }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 lg:col-span-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-classic text-md font-bold text-navy-900 uppercase tracking-wider mb-2">
                            Distribuição de Conceitos</h3>
                        <p class="text-xs text-slate-400 mb-4">Porcentagem total de alunos por faixa de nota.</p>
                    </div>

                    <div class="flex items-center justify-center py-2">
                        <div class="w-32 h-32 rounded-full relative shadow-md"
                            style="background: conic-gradient(#0f172a 0% 35%, #c5a059 35% 80%, #475569 80% 94%, #cbd5e1 94% 100%);">
                            <div class="absolute inset-6 bg-white rounded-full flex items-center justify-center flex-col">
                                <span class="text-xl font-black text-navy-900">A/B</span>
                                <span class="text-[8px] text-slate-400 uppercase font-bold">Predominante</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[11px] mt-4 pt-4 border-t border-slate-50">
                        <div class="flex items-center gap-2"><span
                                class="w-2.5 h-2.5 rounded bg-navy-900 inline-block"></span>
                            <span class="text-slate-500">A: 35%</span>
                        </div>
                        <div class="flex items-center gap-2"><span
                                class="w-2.5 h-2.5 rounded bg-gold-500 inline-block"></span>
                            <span class="text-slate-500">B: 45%</span>
                        </div>
                        <div class="flex items-center gap-2"><span
                                class="w-2.5 h-2.5 rounded bg-slate-600 inline-block"></span>
                            <span class="text-slate-500">C: 14%</span>
                        </div>
                        <div class="flex items-center gap-2"><span
                                class="w-2.5 h-2.5 rounded bg-slate-300 inline-block"></span>
                            <span class="text-slate-500">D: 6%</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 lg:col-span-1">
                    <h3 class="font-classic text-md font-bold text-navy-900 uppercase tracking-wider mb-1">
                        Engajamento e Convivência
                    </h3>
                    <p class="text-xs text-slate-400 mb-4">Média consolidada dos indicadores de comportamento e rotina.</p>

                    <div class="space-y-3">
                        @php
                            $engagementMetrics = [
                                [
                                    'name' => 'Pontualidade',
                                    'status' => 'Excelente',
                                    'trend' => 'up',
                                    'percentage' => 92,
                                ],
                                [
                                    'name' => 'Entrega de Tarefas',
                                    'status' => 'Regular',
                                    'trend' => 'stable',
                                    'percentage' => 78,
                                ],
                                [
                                    'name' => 'Participação em Aula',
                                    'status' => 'Bom',
                                    'trend' => 'up',
                                    'percentage' => 84,
                                ],
                                [
                                    'name' => 'Convivência e Rotina',
                                    'status' => 'Excelente',
                                    'trend' => 'up',
                                    'percentage' => 91,
                                ],
                            ];
                        @endphp

                        @foreach ($engagementMetrics as $metric)
                            <div
                                class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex justify-between items-center">
                                <div>
                                    <div class="font-bold text-xs text-slate-700">
                                        {{ $metric['name'] }}
                                    </div>
                                    <div class="text-[9px] font-bold uppercase text-slate-400 tracking-tight mt-0.5">
                                        {{ $metric['status'] }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-16 bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-gold-500 h-full" style="width: {{ $metric['percentage'] }}%"></div>
                                    </div>
                                    <span class="text-xs">
                                        @if ($metric['trend'] == 'up')
                                            <span class="text-emerald-500 font-bold">▲</span>
                                        @else
                                            <span class="text-slate-400 font-bold">●</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tabela de Histórico Recente -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-navy-900">Registros Recentes de Notas</h3>
                    <span
                        class="text-[10px] bg-slate-100 px-3 py-1 rounded-full text-slate-500 font-bold uppercase tracking-wider">Histórico
                        Vivo</span>
                </div>

                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead class="bg-slate-50 text-[10px] uppercase font-black text-slate-400 tracking-widest">
                            <tr>
                                <th class="px-6 py-4 text-left">Aluno</th>
                                <th class="px-6 py-4 text-left">Disciplina</th>
                                <th class="px-6 py-4 text-center">Score Numérico</th>
                                <th class="px-6 py-4 text-right">Aproveitamento</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($recentGrades as $grade)
                                <tr class="hover:bg-slate-50/60 transition duration-150">
                                    <td class="px-6 py-4 font-semibold text-slate-700">
                                        {{ $grade->student->name }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 text-xs font-medium">
                                        {{ $grade->evaluation->subject->name }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-mono font-black text-md text-navy-900">
                                        {{ number_format($grade->score, 1) }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-[0.2em] border
                                        {{ $grade->score >= 70
                                            ? 'bg-gold-500/10 text-gold-600 border-gold-500/20'
                                            : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                            {{ $grade->score >= 70 ? 'Suficiente' : 'Em Progresso' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-400 text-xs italic">Nenhum
                                        lançamento registrado neste bimestre.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden divide-y divide-slate-100">
                    @foreach ($recentGrades as $grade)
                        <div class="p-5 flex flex-col space-y-3 bg-white">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-navy-900 leading-tight">
                                        {{ $grade->student->name }}</p>
                                    <p class="text-[11px] text-slate-500 uppercase tracking-wider mt-1">
                                        {{ $grade->evaluation->subject->name }}</p>
                                </div>
                                <div class="text-xl font-mono font-black text-navy-900">
                                    {{ number_format($grade->score, 1) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ----------------------------------------------------------------- --}}
            {{-- VISÃO DO PROFESSOR / USUÁRIO COMUM: Painel de Boas-Vindas --}}
            {{-- ----------------------------------------------------------------- --}}
        @else
            {{-- ----------------------------------------------------------------- --}}
            {{-- VISÃO DO PROFESSOR / USUÁRIO COMUM: Painel de Boas-Vindas --}}
            {{-- ----------------------------------------------------------------- --}}
        @else
            <div class="bg-white rounded-2xl p-8 sm:p-12 shadow-sm border border-slate-200 text-center space-y-8">

                <!-- Cabeçalho de Boas-Vindas -->
                <div class="space-y-3">
                    <div
                        class="w-20 h-20 bg-gold-500/10 text-gold-600 rounded-full flex items-center justify-center mx-auto text-4xl shadow-inner">
                        👋
                    </div>
                    <h1 class="font-classic text-3xl sm:text-4xl text-navy-900 font-bold tracking-wide">
                        Bem-vindo(a), {{ auth()->user()->name }}!
                    </h1>
                    <p class="text-slate-500 text-sm sm:text-base max-w-xl mx-auto">
                        Selecione um dos módulos abaixo para gerenciar suas atividades acadêmicas e diárias.
                    </p>
                </div>

                <!-- Atalhos Rápido do Professor (Grid de Módulos) -->
                <div
                    class="pt-6 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 max-w-5xl mx-auto">

                    <!-- 1. Diário de Classe -->
                    <a href="{{ route('attendance.index') }}"
                        class="flex flex-col items-center justify-center p-6 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-navy-900 hover:text-white hover:border-navy-900 transition duration-200 group shadow-sm">
                        <span class="text-3xl mb-2 group-hover:scale-110 transition-transform">📖</span>
                        <span class="font-bold text-sm tracking-wide">Diário de Classe</span>
                        <span class="text-[11px] text-slate-400 group-hover:text-slate-300 mt-1">Frequência e
                            Conteúdos</span>
                    </a>

                    <!-- 2. Turmas -->
                    <a href="{{ route('classrooms.index') }}"
                        class="flex flex-col items-center justify-center p-6 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-navy-900 hover:text-white hover:border-navy-900 transition duration-200 group shadow-sm">
                        <span class="text-3xl mb-2 group-hover:scale-110 transition-transform">🏫</span>
                        <span class="font-bold text-sm tracking-wide">Minhas Turmas</span>
                        <span class="text-[11px] text-slate-400 group-hover:text-slate-300 mt-1">Alunos e Aulas</span>
                    </a>

                    <!-- 3. Avaliações -->
                    <a href="{{ route('evaluations.index') }}"
                        class="flex flex-col items-center justify-center p-6 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-navy-900 hover:text-white hover:border-navy-900 transition duration-200 group shadow-sm">
                        <span class="text-3xl mb-2 group-hover:scale-110 transition-transform">📝</span>
                        <span class="font-bold text-sm tracking-wide">Avaliações</span>
                        <span class="text-[11px] text-slate-400 group-hover:text-slate-300 mt-1">Provas e Notas</span>
                    </a>

                    <!-- 4. Biblioteca -->
                    <a href="{{ route('books.index') }}"
                        class="flex flex-col items-center justify-center p-6 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-navy-900 hover:text-white hover:border-navy-900 transition duration-200 group shadow-sm">
                        <span class="text-3xl mb-2 group-hover:scale-110 transition-transform">📚</span>
                        <span class="font-bold text-sm tracking-wide">Biblioteca</span>
                        <span class="text-[11px] text-slate-400 group-hover:text-slate-300 mt-1">Acervo e Materiais</span>
                    </a>

                </div>
            </div>

        @endif
        @endif

    </div>
@endsection
