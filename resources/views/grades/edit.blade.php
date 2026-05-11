@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h2 class="font-classic text-2xl text-navy-900 dark:text-gold-500 uppercase tracking-widest">Editar Notas
                </h2>
                <p class="text-slate-500">{{ $evaluation->title }} | Máximo: {{ $evaluation->max_score }} pts</p>
            </div>
            <a href="{{ route('evaluations.show', $evaluation->id) }}"
                class="text-xs font-bold text-slate-400 hover:text-navy-900">Cancelar</a>
        </div>

        <form action="{{ route('grades.store', [$classroom, $evaluation]) }}" method="POST">
            @csrf
            <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-navy-900 text-white text-[10px] uppercase font-bold">
                        <tr>
                            <th class="px-8 py-4">Aluno</th>
                            <th class="px-8 py-4 w-48">Score (Máx: {{ $evaluation->max_score }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classroom->students as $student)
                            <tr>
                                <td class="px-8 py-4 font-bold">{{ $student->name }}</td>
                                <td class="px-8 py-4">
                                    <input type="number" step="0.1" name="scores[{{ $student->id }}]"
                                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-2 focus:border-gold-500 outline-none"
                                        value="{{ $evaluation->grades->where('student_id', $student->id)->first()?->score }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-8 flex justify-end">
                <button type="submit"
                    class="bg-gold-500 text-navy-900 px-12 py-4 rounded-2xl font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-gold-500/20">
                    Salvar Lançamentos
                </button>
            </div>
        </form>
    </div>
@endsection
