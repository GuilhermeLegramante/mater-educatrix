@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="font-classic text-3xl text-navy-900 dark:text-white">Dashboard Acadêmico</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Bem-vindo à gestão da Mater Educatrix.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-navy-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800">
                <span class="text-xs font-bold text-slate-400 uppercase">Alunos</span>
                <div class="text-3xl font-black text-navy-900 dark:text-gold-500">124</div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-navy-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-lg">Últimos Lançamentos</h3>
            </div>

            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase font-bold text-slate-400">
                        <tr>
                            <th class="px-6 py-4 text-left">Estudante</th>
                            <th class="px-6 py-4 text-left">Disciplina</th>
                            <th class="px-6 py-4 text-center">Nota</th>
                            <th class="px-6 py-4 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($recentGrades as $grade)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-200">
                                    {{ $grade->student->name }}</td>
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                                    {{ $grade->evaluation->subject->name }}</td>
                                <td class="px-6 py-4 text-center font-black text-lg text-navy-900 dark:text-gold-400">
                                    {{ number_format($grade->score, 1) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-[0.2em] border
                {{ $grade->score >= 7
                    ? 'bg-gold-500/10 text-gold-600 border-gold-500/20 dark:text-gold-400'
                    : 'bg-slate-100 text-slate-500 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700' }}">
                                        {{ $grade->score >= 7 ? 'Suficiente' : 'Em Progresso' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($recentGrades as $grade)
                    <div class="p-5 flex flex-col space-y-3 bg-white dark:bg-navy-900">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-bold text-navy-900 dark:text-white leading-tight">{{ $grade->student->name }}
                                </p>
                                <p class="text-[11px] text-slate-500 uppercase tracking-wider mt-1">
                                    {{ $grade->evaluation->subject->name }}</p>
                            </div>
                            <div class="text-2xl font-black text-navy-900 dark:text-gold-500">
                                {{ number_format($grade->score, 1) }}
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-slate-50 dark:border-slate-800">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Avaliação:
                                {{ $grade->evaluation->title }}</span>
                            <span
                                class="text-[10px] font-black uppercase tracking-widest {{ $grade->score >= 7 ? 'text-gold-600 dark:text-gold-400' : 'text-slate-400' }}">
                                {{ $grade->score >= 7 ? '● Pleno' : '● Em Foco' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
