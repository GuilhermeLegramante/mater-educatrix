@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in pb-12">

        <!-- Cabeçalho da Página -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-slate-100 pb-4">
            <div>
                <h1 class="font-classic text-3xl text-navy-900 tracking-wide">Registro de Ocorrências</h1>
                <p class="text-slate-500 text-sm">Histórico geral de acompanhamento e registros disciplinares.</p>
            </div>
        </div>

        <!-- CARDS DE INSIGHTS / MÉTRICAS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Total de Ocorrências</p>
                    <h3 class="text-2xl font-black text-navy-900 mt-1">{{ $insights['total'] }}</h3>
                </div>
                <div
                    class="w-12 h-12 bg-navy-900/5 text-navy-900 rounded-xl flex items-center justify-center font-black text-lg">
                    📋
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Registros Neste Mês</p>
                    <h3 class="text-2xl font-black text-gold-600 mt-1">{{ $insights['this_month'] }}</h3>
                </div>
                <div
                    class="w-12 h-12 bg-gold-500/10 text-gold-600 rounded-xl flex items-center justify-center font-black text-lg">
                    📅
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Alunos Envolvidos</p>
                    <h3 class="text-2xl font-black text-navy-900 mt-1">{{ $insights['students_count'] }}</h3>
                </div>
                <div
                    class="w-12 h-12 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center font-black text-lg">
                    🎓
                </div>
            </div>
        </div>

        <!-- BARRA DE FILTROS -->
        <form method="GET" action="{{ route('occurrences.index') }}"
            class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm grid grid-cols-1 md:grid-cols-5 gap-4">

            <!-- Busca por Aluno/Descrição -->
            <div class="md:col-span-1">
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Buscar Aluno</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome do aluno..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-navy-900 outline-none focus:border-gold-500">
            </div>

            <!-- Tipo de Ocorrência -->
            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Tipo</label>
                <select name="type_id" onchange="this.form.submit()"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-navy-900 outline-none focus:border-gold-500">
                    <option value="">Todos os Tipos</option>
                    @foreach ($types as $t)
                        <option value="{{ $t->id }}" {{ request('type_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Turma -->
            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Turma</label>
                <select name="classroom_id" onchange="this.form.submit()"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-navy-900 outline-none focus:border-gold-500">
                    <option value="">Todas as Turmas</option>
                    @foreach ($classrooms as $c)
                        <option value="{{ $c->id }}" {{ request('classroom_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Data Inicial -->
            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">De</label>
                <input type="date" name="date_start" value="{{ request('date_start') }}" onchange="this.form.submit()"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-navy-900 outline-none focus:border-gold-500">
            </div>

            <!-- Ações dos Filtros -->
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="w-full bg-navy-900 text-white font-black py-2 rounded-xl text-xs uppercase tracking-wider hover:bg-gold-600 hover:text-navy-950 transition-all">
                    Filtrar
                </button>
                @if (request()->hasAny(['search', 'type_id', 'classroom_id', 'date_start', 'date_end']))
                    <a href="{{ route('occurrences.index') }}"
                        class="px-3 py-2 bg-slate-100 text-slate-500 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors"
                        title="Limpar Filtros">
                        ✕
                    </a>
                @endif
            </div>
        </form>

        <!-- TABELA DE OCORRÊNCIAS DETALHADA -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead
                        class="bg-slate-50 text-[10px] uppercase font-black text-slate-400 tracking-widest border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left">Data e Hora</th>
                            <th class="px-6 py-4 text-left">Aluno / Turma</th>
                            <th class="px-6 py-4 text-left">Tipo</th>
                            <th class="px-6 py-4 text-left">Descrição e Providências</th>
                            <th class="px-6 py-4 text-left">Registrado por</th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($occurrences as $occurrence)
                            <tr class="hover:bg-slate-50/60 transition duration-150">
                                <!-- Data e Hora -->
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-mono text-slate-500 font-bold">
                                    <div>
                                        {{ $occurrence->date ? $occurrence->date->format('d/m/Y') : ($occurrence->created_at ? $occurrence->created_at->format('d/m/Y') : '-') }}
                                    </div>
                                    @if ($occurrence->time)
                                        <div class="text-[10px] text-slate-400 font-normal">
                                            {{ \Carbon\Carbon::parse($occurrence->time)->format('H:i') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Aluno e Turma -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($occurrence->student)
                                        <a href="{{ route('students.show', $occurrence->student->id) }}"
                                            class="font-bold text-xs text-navy-900 hover:text-gold-600 hover:underline transition duration-150 flex items-center gap-1">
                                            🎓 {{ $occurrence->student->name }}
                                        </a>
                                        <!-- Turma vinculada à ocorrência ou Turma atual do aluno -->
                                        <div class="text-[10px] font-bold text-slate-400 mt-0.5">
                                            🏫
                                            {{ $occurrence->classroom->name ?? ($occurrence->student->current_classroom_name ?? 'Sem turma') }}
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Aluno removido</span>
                                    @endif
                                </td>

                                <!-- Tipo de Ocorrência -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($occurrence->type)
                                        {{-- BADGE DE VISUALIZAÇÃO DA OCORRÊNCIA COM COR HEXADECIMAL --}}
                                        <span
                                            class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider text-white shadow-sm border border-black/10"
                                            style="background-color: {{ $occurrence->type->color ?? '#3b82f6' }};">
                                            {{ $occurrence->type->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Geral</span>
                                    @endif
                                </td>

                                <!-- Descrição e Providências -->
                                <td class="px-6 py-4 text-xs text-slate-700 max-w-xs">
                                    @if ($occurrence->description)
                                        <p class="font-semibold text-slate-800">
                                            {{ Str::limit($occurrence->description, 100) }}
                                        </p>
                                    @endif
                                    @if ($occurrence->actions_taken)
                                        <p class="text-[11px] text-gold-600 font-medium mt-1">
                                            <strong>Ações:</strong> {{ Str::limit($occurrence->actions_taken, 80) }}
                                        </p>
                                    @endif
                                </td>

                                <!-- Registrado por -->
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-medium">
                                    {{ $occurrence->user->name ?? 'Sistema' }}
                                </td>

                                <!-- Ações -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if ($occurrence->student)
                                        <a href="{{ route('students.show', $occurrence->student->id) }}"
                                            class="inline-flex items-center gap-1 text-xs font-bold text-gold-600 hover:text-gold-700 bg-gold-50 px-3 py-1.5 rounded-lg border border-gold-200 hover:bg-gold-100 transition duration-150">
                                            Ver Aluno →
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-xs italic">
                                    Nenhuma ocorrência encontrada com os filtros selecionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Links de Paginação -->
            @if ($occurrences->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $occurrences->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
