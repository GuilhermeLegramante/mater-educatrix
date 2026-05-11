@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h2 class="font-classic text-2xl text-navy-900 dark:text-gold-500 uppercase tracking-widest">
                {{ $evaluation->title }}</h2>
            <p class="text-slate-500">Disciplina: <span
                    class="font-bold text-navy-900 dark:text-white">{{ $evaluation->subject->name }}</span></p>
        </div>

        <form action="{{ route('grades.store', [$classroom->id, $evaluation->id]) }}" method="POST"
            class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800">
            @csrf
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-navy-950/50 border-b border-slate-100 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400">Aluno</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 w-48 text-center">
                            Pontos Obtidos (Máx: {{ $evaluation->max_score }})
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($students as $student)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 font-semibold text-navy-900 dark:text-slate-200">
                                {{ $student->name }}
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" name="scores[{{ $student->id }}]"
                                    value="{{ $evaluation->grades->where('student_id', $student->id)->first()?->score ?? '' }}"
                                    step="0.1" max="{{ $evaluation->max_score }}" required
                                    class="w-full bg-slate-50 dark:bg-navy-950 border-slate-200 dark:border-slate-800 rounded-lg focus:ring-gold-500 focus:border-gold-500 dark:text-gold-500 font-bold text-center">
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="p-6 bg-slate-50 dark:bg-navy-950/50 flex justify-end">
                <button type="submit"
                    class="bg-gold-500 text-navy-950 px-8 py-3 rounded-xl font-black uppercase text-xs tracking-widest hover:scale-105 transition-transform shadow-lg shadow-gold-500/20">
                    Salvar Notas
                </button>
            </div>
        </form>
    </div>
@endsection
