@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8" x-data="{ selectedSubject: '{{ $subjects->first()->id ?? '' }}' }">

        <div class="border-b border-slate-100 pb-6">
            <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Controle de
                Frequência</span>
            <h1 class="font-classic text-3xl text-navy-900 mt-1">Diários de Classe</h1>
            <p class="text-slate-500 text-sm mt-1">Escolha a disciplina abaixo e selecione a turma para
                abrir o diário de chamadas.</p>
        </div>

        <div class="space-y-3">
            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                Selecione a Disciplina:
            </label>

            <div class="flex flex-wrap gap-2.5">
                @foreach ($subjects as $subject)
                    <button @click="selectedSubject = '{{ $subject->id }}'" type="button"
                        class="px-5 py-3 rounded-xl text-sm font-bold transition-all duration-200 border shadow-sm flex items-center gap-2"
                        :class="selectedSubject == '{{ $subject->id }}' ?
                            'bg-amber-500 text-navy-950 border-amber-500 shadow-amber-500/10 font-black scale-[1.02]' :
                            'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'">
                        <span class="w-2 h-2 rounded-full transition-colors"
                            :class="selectedSubject == '{{ $subject->id }}' ? 'bg-navy-950' : 'bg-slate-300'">
                        </span>

                        {{ $subject->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="space-y-4 pt-4">
            <div
                class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                <span>Turmas Disponíveis</span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                <span class="text-amber-600 normal-case font-semibold">
                    Exibindo diários para a matéria selecionada acima
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($classrooms as $classroom)
                    <a :href="'{{ url('/diario/' . $classroom->id) }}/' + selectedSubject"
                        class="group bg-white p-6 rounded-2xl shadow-sm border border-slate-200 transition-all duration-300 hover:shadow-md hover:border-amber-500 flex flex-col justify-between min-h-[170px]">

                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div
                                    class="p-3 bg-slate-50 rounded-xl group-hover:bg-amber-50 transition-colors">
                                    <svg class="w-6 h-6 text-slate-400 group-hover:text-amber-600"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <span
                                    class="text-slate-300 group-hover:text-amber-500 transition-colors">
                                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </div>

                            <h3
                                class="font-bold text-lg text-navy-900 group-hover:text-amber-600 transition-colors leading-tight">
                                {{ $classroom->name }}
                            </h3>
                        </div>

                        <div
                            class="mt-6 pt-3 border-t border-slate-100 flex justify-between items-center text-xs text-slate-400">
                            <span
                                class="font-medium uppercase tracking-wider group-hover:text-amber-600 transition-colors">
                                Lançar Presenças &raquo;
                            </span>
                            <span
                                class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-md font-bold text-[10px]">
                                Ativa
                            </span>
                        </div>
                    </a>
                @empty
                    <div
                        class="col-span-full bg-white border border-slate-200 rounded-2xl p-12 text-center">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="text-slate-700 font-bold text-base">Nenhuma turma cadastrada</h3>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
