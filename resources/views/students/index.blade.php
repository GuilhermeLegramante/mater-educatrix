@extends('layouts.app')

@section('content')
    <div
        class="max-w-6xl mx-auto p-8 bg-white rounded-3xl shadow-sm border border-slate-100 backdrop-blur-sm transition-all">

        <!-- Cabeçalho -->
        <div class="flex justify-between items-end border-b border-slate-200 pb-6 mb-6">
            <div>
                <span class="text-gold-600 font-bold tracking-widest text-xs uppercase">Curriculum</span>
                <h1 class="font-classic text-4xl text-navy-900 transition-colors">Listagem de Alunos</h1>
            </div>
            @can('admin')
                <button onclick="document.getElementById('form-aluno').classList.toggle('hidden')"
                    class="bg-navy-900 text-white px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-navy-900/20">
                    + Adicionar Aluno
                </button>
            @endcan
        </div>

        @php
            $isEdit = isset($student);
        @endphp

        <!-- Form de Cadastro / Edição -->
        <div id="form-aluno"
            class="p-6 mb-6 bg-navy-950 text-white border border-slate-200 rounded-2xl {{ $errors->any() || $isEdit ? '' : 'hidden' }}">

            @include('partials.messages.errors')

            <form action="{{ $isEdit ? route('students.update', $student) : route('students.store') }}" method="POST"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <input type="text" name="name" value="{{ old('name', $student->name ?? '') }}"
                    placeholder="Nome Completo"
                    class="bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-sm outline-none focus:border-amber-500 transition-all">

                <input type="text" name="registration_number"
                    value="{{ old('registration_number', $student->registration_number ?? '') }}" placeholder="Nº Matrícula"
                    class="bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-sm font-mono outline-none focus:border-amber-500 transition-all">

                <input type="date" name="birth_date" value="{{ old('birth_date', $student->birth_date ?? '') }}"
                    class="bg-white/10 border border-white/20 rounded-xl px-4 py-2 text-sm outline-none focus:border-amber-500 transition-all">

                <button type="submit"
                    class="bg-gold-500 text-navy-900 font-bold rounded-xl uppercase text-xs px-4 hover:bg-gold-600 transition-all">
                    {{ $isEdit ? 'Atualizar' : 'Gravar' }}
                </button>
            </form>
        </div>

        <!-- 1. BARRA DE BUSCA E FILTROS -->
        <form method="GET" action="{{ route('students.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-12 gap-3">

            <!-- Campo de Busca por Nome ou Matrícula -->
            <div class="md:col-span-6 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar por nome ou nº de matrícula..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm outline-none focus:border-gold-500 transition-all">
                <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-slate-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Filtro por Turma -->
            <div class="md:col-span-4">
                <select name="classroom_id"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-gold-500 transition-all text-slate-600">
                    <option value="">Todas as Turmas</option>
                    @if (isset($classrooms))
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}"
                                {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Botão de Filtrar / Limpar -->
            <div class="md:col-span-2 flex gap-2">
                <button type="submit"
                    class="w-full bg-slate-800 text-white font-bold rounded-xl text-xs uppercase tracking-wider hover:bg-navy-900 transition-all py-2.5">
                    Filtrar
                </button>
                @if (request()->hasAny(['search', 'classroom_id']))
                    <a href="{{ route('students.index') }}"
                        class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-xl transition-all flex items-center justify-center"
                        title="Limpar Filtros">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>

        <!-- Tabela de Alunos -->
        <div class="text-slate-600 text-sm overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] uppercase font-black text-slate-400 bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-4">Nome</th>
                        <th class="px-8 py-4">Matrícula</th>
                        <th class="px-8 py-4">Turma Atual</th>
                        <th class="px-8 py-4">Data de Nascimento</th>
                        <th class="px-8 py-4 text-right">Ações</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($students as $student)
                        <tr class="hover:bg-slate-50/40 transition-colors group">
                            <td class="px-8 py-5 font-bold text-navy-900 group-hover:text-gold-500 transition-colors">
                                {{ $student->name }}
                            </td>
                            <td class="px-8 py-5 text-slate-500 font-mono text-sm">
                                {{ $student->registration_number }}
                            </td>
                            <td class="px-8 py-5 text-slate-500 text-sm font-medium">
                                {{ $student->getCurrentClassroomNameAttribute() ?? 'Não Matriculado' }}
                            </td>
                            <td class="px-8 py-5 text-slate-500 text-sm">
                                {{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-8 py-5 flex justify-end gap-1.5">
                                <a href="{{ route('students.show', $student) }}"
                                    class="p-2 hover:bg-gold-50 rounded-xl text-gold-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('students.edit', $student) }}"
                                    class="p-2 hover:bg-slate-100 rounded-xl text-slate-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST"
                                    class="form-delete inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-2 hover:bg-red-50 rounded-xl text-red-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-8 text-center text-slate-400">
                                Nenhum aluno encontrado com os filtros aplicados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 2. PAGINAÇÃO DO LARAVEL -->
        @if ($students instanceof \Illuminate\Pagination\AbstractPaginator)
            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $students->appends(request()->query())->links() }}
            </div>
        @endif

    </div>
@endsection
