@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Gestão de Conceitos por Turma</h1>
                <p class="text-gray-400 text-sm">Visualização consolidada de conceitos bimestrais de todos os alunos</p>
            </div>
        </div>

        <!-- Painel de Filtros -->
        <div class="bg-[#2b2c43] p-4 rounded-lg shadow mb-6">
            <form method="GET" action="{{ route('reports.bimesters.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Filtro por Turma -->
                <div>
                    <label for="classroom_id" class="block text-sm font-medium text-gray-300 mb-1">Turma</label>
                    <select name="classroom_id" id="classroom_id"
                        class="w-full bg-[#020916] border border-gray-700 text-white rounded-md p-2"
                        onchange="this.form.submit()">
                        <option value="">Selecione uma Turma</option>
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}"
                                {{ $selectedClassroomId == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Disciplina -->
                <div>
                    <label for="subject_id" class="block text-sm font-medium text-gray-300 mb-1">Disciplina
                        (Opcional)</label>
                    <select name="subject_id" id="subject_id"
                        class="w-full bg-[#020916] border border-gray-700 text-white rounded-md p-2"
                        onchange="this.form.submit()">
                        <option value="">Todas as Disciplinas</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $selectedSubjectId == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Botão de Ação -->
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                        Filtrar Resultados
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabela Matriz de Conceitos -->
        <div class="bg-[#2b2c43] rounded-lg shadow overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-200">
                <thead class="bg-[#020916] text-xs uppercase text-gray-400 border-b border-gray-700">
                    <tr>
                        <th class="py-3 px-4">Aluno</th>
                        <th class="py-3 px-4">Disciplina</th>
                        <th class="py-3 px-4 text-center">1º Bimestre</th>
                        <th class="py-3 px-4 text-center">2º Bimestre</th>
                        <th class="py-3 px-4 text-center">3º Bimestre</th>
                        <th class="py-3 px-4 text-center">4º Bimestre</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($studentsData as $item)
                        @foreach ($item['subjects'] as $index => $subjectData)
                            <tr class="hover:bg-gray-800/50">
                                {{-- Nome do Aluno (exibido apenas na primeira linha da disciplina) --}}
                                @if ($index === 0)
                                    <td class="py-3 px-4 font-medium text-white border-r border-gray-700"
                                        rowspan="{{ count($item['subjects']) }}">
                                        {{ $item['student']->name }}
                                    </td>
                                @endif

                                <td class="py-3 px-4 text-gray-300 font-semibold">
                                    {{ $subjectData['subject']->name }}
                                </td>

                                {{-- Conceitos dos 4 Bimestres --}}
                                @foreach ([1, 2, 3, 4] as $bimester)
                                    @php
                                        $concept = $subjectData['bimesters'][$bimester] ?? '-';

                                        // Definição de cores conforme o conceito
                                        $colorClass = match ($concept) {
                                            'A' => 'bg-green-900/60 text-green-300 border-green-600',
                                            'B' => 'bg-blue-900/60 text-blue-300 border-blue-600',
                                            'C' => 'bg-yellow-900/60 text-yellow-300 border-yellow-600',
                                            'D' => 'bg-orange-900/60 text-orange-300 border-orange-600',
                                            'E', 'F' => 'bg-red-900/60 text-red-300 border-red-600',
                                            default => 'bg-gray-800 text-gray-400 border-gray-600',
                                        };
                                    @endphp
                                    <td class="py-3 px-4 text-center">
                                        <span
                                            class="inline-block px-3 py-1 text-xs font-bold rounded-full border {{ $colorClass }}">
                                            {{ $concept }}
                                        </span>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">
                                Nenhum registro de conceito encontrado para os filtros selecionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
