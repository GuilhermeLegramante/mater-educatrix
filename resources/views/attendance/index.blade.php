@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        <div
            class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Gestão
                        Pedagógica</span>
                    <span class="text-slate-300">•</span>
                    <span
                        class="text-xs font-bold bg-amber-500/10 text-amber-700 px-2 py-0.5 rounded-md uppercase">
                        {{ $classroom->name }}
                    </span>
                </div>

                <h1 class="text-2xl font-bold text-slate-900 mt-1 flex items-center gap-2">
                    Diário de Frequência:
                    <span
                        class="text-amber-500 font-classic px-3 py-0.5 bg-amber-500/5 border border-amber-500/20 rounded-xl">
                        {{ $subject->name }}
                    </span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">Clique nas células (P/F) para alternar
                    instantaneamente a presença na matéria acima.</p>
            </div>

            <div
                class="flex items-center gap-1.5 bg-slate-50 p-1.5 rounded-2xl border border-slate-100 w-full md:w-auto">
                <label class="text-[10px] font-bold text-slate-400 uppercase px-2 hidden sm:block">Mês:</label>
                <div class="grid grid-cols-3 sm:flex items-center gap-1 w-full">
                    @foreach (range(2, 12) as $m)
                        @php
                            $monthDate = \Carbon\Carbon::create($currentDate->year, $m, 1);
                            $isActive = $currentDate->month == $m;
                        @endphp
                        <a href="{{ request()->url() }}?month={{ $m }}&year={{ $currentDate->year }}"
                            class="px-3 py-1.5 text-center text-xs font-bold uppercase rounded-xl transition-all border
                           {{ $isActive
                               ? 'bg-amber-500 text-navy-950 border-amber-500 shadow-sm font-black'
                               : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                            {{ $monthDate->translatedFormat('M') }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div
            class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th
                                class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider min-w-[260px] sticky left-0 bg-slate-50 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                Nome do Aluno
                            </th>
                            @forelse ($schoolDays as $day)
                                @php
                                    $isToday = $day->date->isToday();
                                @endphp
                                <th
                                    class="p-3 text-center min-w-[65px] border-l border-slate-100 {{ $isToday ? 'bg-amber-50/30' : '' }}">
                                    <div
                                        class="text-[10px] uppercase font-bold {{ $isToday ? 'text-amber-600' : 'text-slate-400' }}">
                                        {{ $day->date->translatedFormat('D') }}
                                    </div>

                                    <div class="flex justify-center mt-0.5">
                                        <span
                                            class="text-sm font-black w-7 h-7 flex items-center justify-center rounded-full transition-all
                                            {{ $isToday
                                                ? 'bg-amber-500 text-navy-950 font-black shadow-sm'
                                                : 'text-slate-700' }}">
                                            {{ $day->date->format('d') }}
                                        </span>
                                    </div>
                                </th>
                            @empty
                                <th class="p-8 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">
                                    Nenhum dia letivo gerado ou configurado para este mês nesta turma.
                                </th>
                            @endforelse
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($students as $student)
                            <tr class="hover:bg-slate-50/60 transition-colors group">
                                <td
                                    class="p-4 font-semibold text-sm text-slate-800 sticky left-0 bg-white z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] group-hover:bg-slate-50">
                                    {{ $student->name }}
                                </td>

                                @if ($schoolDays->isNotEmpty())
                                    @foreach ($schoolDays as $day)
                                        @php
                                            // Em vez de true/false, pegamos a quantidade de faltas salvas para esse par estudante/dia
                                            $absencesCount = isset($absenceMap[$student->id][$day->id])
                                                ? $absenceMap[$student->id][$day->id]
                                                : 0;
                                            $isToday = $day->date->isToday();
                                        @endphp
                                        <td
                                            class="p-2 text-center border-l border-slate-100 {{ $isToday ? 'bg-amber-50/30' : '' }}">
                                            <button
                                                onclick="toggleAttendance(this, {{ $student->id }}, {{ $day->id }})"
                                                type="button" data-current-absences="{{ $absencesCount }}"
                                                class="w-8 h-8 rounded-lg text-xs font-black transition-all duration-200 shadow-sm border
                                                {{ $absencesCount > 0
                                                    ? 'bg-rose-50 border-rose-300 text-rose-600 hover:bg-rose-100'
                                                    : 'bg-emerald-50 border-emerald-300 text-emerald-600 hover:bg-emerald-100' }}
                                                {{ $isToday ? 'ring-2 ring-amber-500/20' : '' }}"
                                                title="Clique para adicionar faltas nesta matéria">

                                                {{-- Mostra 'P' se não houver faltas, ou a quantidade delas (ex: 2F) --}}
                                                {{ $absencesCount > 0 ? $absencesCount . 'F' : 'P' }}
                                            </button>
                                        </td>
                                    @endforeach
                                @else
                                    <td class="p-4 text-center text-sm text-slate-400 italic">
                                        Aguardando calendário letivo...
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        function toggleAttendance(button, studentId, schoolDayId) {
            if (button.disabled) return;
            button.disabled = true;
            button.classList.add('opacity-50', 'scale-95');

            const token = "{{ csrf_token() }}";
            const currentSubjectId = "{{ $subject->id }}";

            // Pega a quantidade atual e incrementa. Se passar de 3 (ou o limite da sua escola), volta para 0.
            let currentAbsences = parseInt(button.getAttribute('data-current-absences')) || 0;
            let nextAbsences = currentAbsences + 1;
            if (nextAbsences > 2) { // Altere o '2' para o número máximo de aulas possíveis no mesmo dia
                nextAbsences = 0;
            }

            fetch("/diario/toggle", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({
                        student_id: studentId,
                        school_day_id: schoolDayId,
                        subject_id: currentSubjectId,
                        requested_absences: nextAbsences // Enviamos a quantidade exata
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    button.disabled = false;
                    button.classList.remove('opacity-50', 'scale-95');

                    if (data.success) {
                        // Atualiza o estado do botão com o retorno oficial do servidor
                        const savedAbsences = data.absences_count;
                        button.setAttribute('data-current-absences', savedAbsences);

                        if (savedAbsences > 0) {
                            button.innerText = savedAbsences + 'F';
                            button.className =
                                "w-8 h-8 rounded-lg text-xs font-black transition-all duration-200 shadow-sm border bg-rose-50 border-rose-300 text-rose-600 hover:bg-rose-100";
                        } else {
                            button.innerText = 'P';
                            button.className =
                                "w-8 h-8 rounded-lg text-xs font-black transition-all duration-200 shadow-sm border bg-emerald-50 border-emerald-300 text-emerald-600 hover:bg-emerald-100";
                        }
                    } else {
                        alert(data.message || 'Erro ao atualizar a frequência.');
                    }
                })
                .catch(error => {
                    button.disabled = false;
                    button.classList.remove('opacity-50', 'scale-95');
                    console.error('Erro detalhado do Servidor:', error);
                    alert(error.error || error.message || 'Erro ao conectar ao servidor.');
                });
        }
    </script>
@endsection
