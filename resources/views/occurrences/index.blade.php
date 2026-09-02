@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in pb-12">

        <!-- Cabeçalho da Página -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-slate-100 pb-4">
            <div>
                <h1 class="font-classic text-3xl text-navy-900 tracking-wide">Registro de Ocorrências</h1>
                <p class="text-slate-500 text-sm">Histórico geral de acompanhamento e registros disciplinares.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="bg-navy-900 text-white text-xs font-bold px-3 py-1.5 rounded-xl uppercase tracking-wider">
                    Total: {{ $occurrences->total() }} registros
                </span>
            </div>
        </div>

        <!-- Tabela de Ocorrências -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead
                        class="bg-slate-50 text-[10px] uppercase font-black text-slate-400 tracking-widest border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left">Data</th>
                            <th class="px-6 py-4 text-left">Aluno</th>
                            <th class="px-6 py-4 text-left">Descrição / Título</th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($occurrences as $occurrence)
                            <tr class="hover:bg-slate-50/60 transition duration-150">
                                <!-- Data -->
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-mono text-slate-500 font-bold">
                                    {{ $occurrence->created_at ? $occurrence->created_at->format('d/m/Y H:i') : '-' }}
                                </td>

                                <!-- Aluno com link para o perfil -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($occurrence->student)
                                        <a href="{{ route('students.show', $occurrence->student->id) }}"
                                            class="font-bold text-xs text-navy-900 hover:text-gold-600 hover:underline transition duration-150 flex items-center gap-1">
                                            🎓 {{ $occurrence->student->name }}
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Aluno removido</span>
                                    @endif
                                </td>

                                <!-- Descrição da Ocorrência -->
                                <td class="px-6 py-4 text-xs text-slate-700 max-w-md">
                                    <p class="font-semibold text-slate-800">
                                        {{ $occurrence->title ?? 'Ocorrência Registrada' }}
                                    </p>
                                    @if ($occurrence->description)
                                        <p class="text-slate-500 text-[11px] mt-0.5">
                                            {{ Str::limit($occurrence->description, 90) }}
                                        </p>
                                    @endif
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
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-xs italic">
                                    Nenhuma ocorrência encontrada no sistema.
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
