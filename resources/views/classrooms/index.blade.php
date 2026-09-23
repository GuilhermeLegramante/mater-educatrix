@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 animate-fade-in">

        <div class="flex justify-between items-end mb-10 border-b border-slate-200 pb-6">
            <div>
                <h1 class="font-classic text-4xl text-navy-900 transition-colors">Painel de Turmas</h1>
                <p class="text-slate-500">Selecione uma turma para gerir notas e alunos.</p>
            </div>
            @can('admin')
                <button onclick="openModal('modal-turma')"
                    class="bg-navy-900 text-white px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-navy-900/20">
                    + Nova Turma
                </button>
            @endcan
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($classrooms as $classroom)
                <a href="{{ route('classrooms.show', $classroom) }}"
                    class="group bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-gold-500/30 backdrop-blur-sm transition-all">

                    <div class="flex justify-between items-start mb-4">
                        <span
                            class="bg-gold-500/10 text-gold-700 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $classroom->year }}
                        </span>
                        <svg class="w-5 h-5 text-slate-300 group-hover:text-gold-500 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7" stroke-width="3" />
                        </svg>
                    </div>

                    <h3 class="font-classic text-2xl text-navy-900 mb-2 group-hover:text-gold-500 transition-colors">
                        {{ $classroom->name }}
                    </h3>

                    <p class="text-slate-400 text-xs uppercase font-bold tracking-widest">
                        {{ $classroom->students_count ?? 0 }} Alunos Matriculados
                    </p>
                </a>
            @endforeach
        </div>
    </div>

    <div id="modal-turma" onclick="if(event.target === this) closeModal('modal-turma')"
        class="fixed inset-0 bg-navy-950/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 transition-all">

        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-200 overflow-hidden transform transition-all scale-95 opacity-0"
            id="modal-content">

            <div class="p-8 bg-navy-900 text-white flex justify-between items-center border-b">
                <div>
                    <h3 class="font-classic text-xl text-gold-500">Nova Turma</h3>
                    <p class="text-[10px] uppercase tracking-widest text-slate-400">Configuração de Ano
                        Letivo</p>
                </div>
                <button onclick="closeModal('modal-turma')"
                    class="text-slate-400 hover:text-white text-2xl transition-colors">&times;</button>
            </div>

            <form action="{{ route('classrooms.store') }}" method="POST" class="p-8 space-y-5">
                @csrf

                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-wider">Nome
                        da Turma</label>
                    <input type="text" name="name" required placeholder="Ex: 5º Ano A"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 outline-none focus:border-gold-500 transition-all text-navy-900 font-bold">
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-wider">Grade
                        Curricular (Disciplinas)</label>
                    <div
                        class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-2 bg-slate-50 border-2 border-slate-100 rounded-xl class-scroll-custom">
                        @foreach ($allSubjects as $subject)
                            <label
                                class="flex items-center space-x-2 p-2 hover:bg-white rounded-lg cursor-pointer transition-colors border border-transparent hover:border-slate-100">
                                <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                    class="rounded text-gold-500 focus:ring-gold-500">
                                <span class="text-xs font-bold text-navy-900">{{ $subject->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-wider">Ano
                        Letivo</label>
                    <input type="number" name="year" required value="{{ date('Y') }}"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 outline-none focus:border-gold-500 transition-all text-navy-900 font-bold">
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-gold-500 text-navy-900 font-black py-4 rounded-xl uppercase tracking-widest hover:bg-gold-600 hover:scale-[1.02] transition-all shadow-lg shadow-gold-500/20">
                        Criar Turma Agora
                    </button>
                    <button type="button" onclick="closeModal('modal-turma')"
                        class="w-full mt-3 text-slate-400 text-[10px] font-bold uppercase tracking-widest hover:text-navy-900 transition-colors">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            const content = modal.querySelector('#modal-content');

            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            const content = modal.querySelector('#modal-content');

            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        // Fechar ao clicar fora do conteúdo
        window.onclick = function(event) {
            const modal = document.getElementById('modal-turma');
            if (event.target == modal) {
                closeModal('modal-turma');
            }
        }
    </script>
@endsection
