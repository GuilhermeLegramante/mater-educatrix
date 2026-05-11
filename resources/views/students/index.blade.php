@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
            <h2 class="font-classic text-2xl text-navy-900">Catálogo de Alunos</h2>
            <button onclick="document.getElementById('form-aluno').classList.toggle('hidden')"
                class="text-gold-600 font-bold text-sm uppercase">+ Adicionar Aluno</button>
        </div>

        <div id="form-aluno" class="hidden p-8 bg-navy-900 text-white border-b border-navy-800">
            <form action="{{ route('students.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @csrf
                <input type="text" name="name" placeholder="Nome Completo"
                    class="bg-white/10 border border-white/20 rounded-xl px-4 py-2 outline-none focus:border-gold-500">
                <input type="text" name="registration_number" placeholder="Nº Matrícula"
                    class="bg-white/10 border border-white/20 rounded-xl px-4 py-2 outline-none focus:border-gold-500">
                <button type="submit"
                    class="bg-gold-500 text-navy-900 font-bold rounded-xl uppercase text-xs">Gravar</button>
            </form>
        </div>

        <table class="w-full">
            <thead>
                <tr class="text-[10px] uppercase font-black text-slate-400 bg-slate-50/30">
                    <th class="px-8 py-4 text-left">Nome</th>
                    <th class="px-8 py-4 text-left">Matrícula</th>
                    <th class="px-8 py-4 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach ($students as $student)
                    <tr>
                        <td class="px-8 py-5 font-bold text-navy-900">{{ $student->name }}</td>
                        <td class="px-8 py-5 text-slate-500 font-mono text-sm">{{ $student->registration_number }}</td>
                        <td class="px-8 py-5 flex justify-end gap-3">
                            <a href="{{ route('students.show', $student) }}"
                                class="p-2 hover:bg-gold-50 rounded-lg text-gold-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <form action="{{ route('students.destroy', $student) }}" method="POST" class="form-delete">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="p-2 hover:bg-red-50 rounded-lg text-red-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            stroke-width="2" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $students->links() }}</div>
    </div>
@endsection
