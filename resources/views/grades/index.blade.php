@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto animate-fade-in">
        <div class="mb-8">
            <h1 class="font-classic text-2xl text-navy-900">Histórico de Notas</h1>
            <p class="text-slate-500 text-sm">Acompanhamento do desempenho acadêmico dos alunos.</p>
        </div>

        <div
            class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Aluno
                            </th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Disciplina
                            </th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Avaliação
                            </th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">
                                Nota</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($grades as $grade)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-navy-900">
                                    {{ $grade->student->name }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-sm">
                                    {{ $grade->evaluation->subject->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-[10px] font-black">
                                        {{ $grade->evaluation->title }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 text-center font-black text-lg {{ $grade->score >= 7 ? 'text-navy-900' : 'text-slate-400' }}">
                                    {{ number_format($grade->score, 1) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($grade->score >= 7)
                                        <span
                                            class="bg-gold-500/10 text-gold-600 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-gold-500/20">
                                            Excelente
                                        </span>
                                    @else
                                        <span
                                            class="bg-slate-100 text-slate-400 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-slate-200">
                                            Em Progresso
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
