@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8 animate-fade-in pb-12">

        {{-- ----------------------------------------------------------------- --}}
        {{-- VISÃO DO ADMINISTRADOR: Panorama Acadêmico Completo --}}
        {{-- ----------------------------------------------------------------- --}}
        @if (auth()->user()->isAdmin())
            <!-- Cabeçalho -->
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-slate-100 pb-4">
                <div>
                    <h1 class="font-classic text-3xl text-navy-900 tracking-wide">Panorama Acadêmico</h1>
                    <p class="text-slate-500 text-sm">Visão analítica integrada em tempo real.</p>
                </div>
                <div
                    class="mt-4 md:mt-0 bg-white border border-slate-200 px-4 py-2 rounded-xl text-xs font-bold text-slate-500 uppercase tracking-wider">
                    Ano Letivo: 2026 • {{ date('d/m/Y') }}
                </div>
            </div>

            <!-- Cards Indicadores com Dados Reais -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total de Alunos -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-gold-500 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total de Alunos</span>
                        <div class="text-3xl font-black text-navy-900 mt-1">{{ number_format($totalStudents) }}</div>
                        <span class="text-[10px] text-emerald-500 font-bold">Base Ativa</span>
                    </div>
                    <div class="text-2xl opacity-40">🎓</div>
                </div>

                <!-- Média Consolidada -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-navy-900 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Média Geral</span>
                        <div class="text-3xl font-black text-navy-900 mt-1">Conceito {{ $globalConcept }}</div>
                        <span class="text-[10px] text-slate-400 font-bold">
                            Média numérica: {{ number_format($averageScore, 1) }}%
                        </span>
                    </div>
                    <div class="text-2xl opacity-40">🏛️</div>
                </div>

                <!-- Avaliações Recentes -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-gold-500 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Avaliações Criadas</span>
                        <div class="text-3xl font-black text-navy-900 mt-1">{{ $recentEvaluations->count() }}</div>
                        <span class="text-[10px] text-slate-400 font-bold">Registradas ultimamente</span>
                    </div>
                    <div class="text-2xl opacity-40">📝</div>
                </div>

                <!-- Ocorrências Recentes -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-navy-900 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ocorrências</span>
                        <div class="text-3xl font-black text-navy-900 mt-1">{{ $recentOccurrences->count() }}</div>
                        <span class="text-[10px] text-amber-500 font-bold">Últimos registros</span>
                    </div>
                    <div class="text-2xl opacity-40">⚠️</div>
                </div>
            </div>

            <!-- Bloco de Atividades Recentes: Avaliações + Ocorrências -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Card 1: Últimas Avaliações Cadastradas -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-navy-900">Últimas Avaliações Cadastradas</h3>
                        <a href="{{ route('evaluations.index') }}"
                            class="text-xs text-gold-600 font-bold hover:underline">Ver todas →</a>
                    </div>
                    <div class="space-y-3">
                        @forelse ($recentEvaluations as $eval)
                            <div
                                class="p-3 bg-slate-50 rounded-xl flex justify-between items-center border border-slate-100">
                                <div>
                                    <p class="font-bold text-xs text-slate-800">{{ $eval->title ?? 'Avaliação sem título' }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">
                                        Turma: {{ $eval->classroom->name ?? 'N/A' }} • Disciplina:
                                        {{ $eval->subject->name ?? 'N/A' }}
                                    </p>
                                </div>
                                <span class="text-[10px] font-mono text-slate-400 font-bold">
                                    {{ $eval->created_at ? $eval->created_at->format('d/m') : '-' }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic text-center py-4">Nenhuma avaliação cadastrada
                                recentemente.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Card 2: Últimas Ocorrências Registradas -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-navy-900">Últimas Ocorrências Registradas</h3>
                        <span
                            class="text-[10px] bg-amber-50 text-amber-700 px-2 py-1 rounded font-bold uppercase">Acompanhamento</span>
                    </div>
                    <div class="space-y-3">
                        @forelse ($recentOccurrences as $occurrence)
                            <div
                                class="p-3 bg-slate-50 rounded-xl flex justify-between items-center border border-slate-100">
                                <div>
                                    <p class="font-bold text-xs text-slate-800">{{ $occurrence->student->name ?? 'Aluno' }}
                                    </p>
                                    <p class="text-[10px] text-slate-500 mt-0.5">
                                        {{ Str::limit($occurrence->description ?? ($occurrence->title ?? 'Sem descrição'), 45) }}
                                    </p>
                                </div>
                                <span class="text-[10px] font-mono text-slate-400 font-bold">
                                    {{ $occurrence->created_at ? $occurrence->created_at->format('d/m') : '-' }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic text-center py-4">Nenhuma ocorrência registrada
                                recentemente.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Tabela de Histórico Recente de Notas -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-navy-900">Registros Recentes de Notas</h3>
                    <span
                        class="text-[10px] bg-slate-100 px-3 py-1 rounded-full text-slate-500 font-bold uppercase tracking-wider">Histórico
                        Vivo</span>
                </div>

                <div class="overflow-x-auto">
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
                                        {{ $grade->student->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 text-xs font-medium">
                                        {{ $grade->evaluation->subject->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-mono font-black text-md text-navy-900">
                                        {{ number_format($grade->score, 1) }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-[0.2em] border
                                            {{ $grade->score >= 70 ? 'bg-gold-500/10 text-gold-600 border-gold-500/20' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                            {{ $grade->score >= 70 ? 'Suficiente' : 'Em Progresso' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-400 text-xs italic">
                                        Nenhum lançamento registrado neste período.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            {{-- ----------------------------------------------------------------- --}}
            {{-- VISÃO DO PROFESSOR / USUÁRIO COMUM: Painel de Boas-Vindas --}}
            {{-- ----------------------------------------------------------------- --}}
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
                    class="pt-6 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-w-5xl mx-auto">

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
                    {{-- <a href="{{ route('books.index') }}"
                        class="flex flex-col items-center justify-center p-6 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-navy-900 hover:text-white hover:border-navy-900 transition duration-200 group shadow-sm">
                        <span class="text-3xl mb-2 group-hover:scale-110 transition-transform">📚</span>
                        <span class="font-bold text-sm tracking-wide">Biblioteca</span>
                        <span class="text-[11px] text-slate-400 group-hover:text-slate-300 mt-1">Acervo e Materiais</span>
                    </a> --}}

                </div>
            </div>
        @endif

    </div>
@endsection
