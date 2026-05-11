@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8 animate-fade-in">
        <div class="flex justify-between items-end border-b border-slate-200 pb-6">
            <div>
                <span class="text-gold-600 font-bold tracking-widest text-xs uppercase">Curriculum</span>
                <h1 class="font-classic text-4xl text-navy-900">Catálogo de Disciplinas</h1>
            </div>
            <button onclick="document.getElementById('modal-subject').classList.remove('hidden')"
                class="bg-navy-900 text-white px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-navy-900/20">
                + Nova Disciplina
            </button>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black">
                    <tr>
                        <th class="px-8 py-4">Nome da Disciplina</th>
                        <th class="px-8 py-4 text-center">Carga Horária</th>
                        <th class="px-8 py-4 text-center">Avaliações Ativas</th>
                        <th class="px-8 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach ($subjects as $subject)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5 font-bold text-navy-900 uppercase tracking-tight">{{ $subject->name }}</td>
                            <td class="px-8 py-5 text-center text-slate-500 font-mono">{{ $subject->workload ?? '---' }}h
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span
                                    class="bg-navy-100 text-navy-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase">
                                    {{ $subject->evaluations_count ?? 0 }} Provas
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="form-delete">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-red-400 hover:text-red-600 p-2 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="modal-subject"
        class="fixed inset-0 bg-navy-900/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden animate-slide-up">
            <div class="p-8 bg-navy-900 text-white flex justify-between items-center">
                <h3 class="font-classic text-xl">Cadastrar Disciplina</h3>
                <button onclick="document.getElementById('modal-subject').classList.add('hidden')"
                    class="text-white/50 hover:text-white">&times;</button>
            </div>
            <form action="{{ route('subjects.store') }}" method="POST" class="p-8 space-y-4">
                @csrf
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Nome (Ex: Latim II)</label>
                    <input type="text" name="name" required
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 outline-none focus:border-gold-500">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Carga Horária
                        (Opcional)</label>
                    <input type="number" name="workload"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 outline-none focus:border-gold-500">
                </div>
                <button type="submit"
                    class="w-full bg-gold-500 text-navy-900 font-black py-4 rounded-xl uppercase tracking-widest hover:scale-[1.02] transition-all">
                    Gravar no Catálogo
                </button>
            </form>
        </div>
    </div>
@endsection
