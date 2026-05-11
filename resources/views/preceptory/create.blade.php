@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <header>
            <h2 class="font-classic text-3xl text-navy-900">Relato de Preceptoria</h2>
            <p class="text-slate-500">Aluno: <span class="font-bold text-navy-900">{{ $student->name }}</span></p>
        </header>

        <form action="{{ route('preceptory.store', $classroom) }}" method="POST"
            class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl space-y-6">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2">Disciplina Relacionada
                        (Opcional)</label>
                    <select name="subject_id"
                        class="bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 outline-none focus:border-gold-500">
                        <option value="">Geral / Comportamental</option>
                        @foreach ($classroom->subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2">Bimestre</label>
                    <select name="bimester"
                        class="bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 outline-none focus:border-gold-500">
                        <option value="1">1º</option>
                        <option value="2">2º</option>
                        <option value="3">3º</option>
                        <option value="4">4º</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 mb-2">Desenvolvimento e Observações</label>
                <textarea name="content" rows="5"
                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-4 py-3 outline-none focus:border-gold-500"
                    placeholder="Descreva o progresso académico e moral..."></textarea>
            </div>

            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 mb-2">Virtudes Notadas</label>
                <input type="text" name="virtues_noted" placeholder="Ex: Ordem, Fortaleza, Diligência"
                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 outline-none focus:border-gold-500">
            </div>



            <div class="flex justify-end">
                <button type="submit"
                    class="bg-navy-900 text-white px-10 py-4 rounded-xl font-bold uppercase tracking-widest text-xs">Guardar
                    Relatório</button>
            </div>
        </form>
    </div>
@endsection
