@if (isset($classrooms) && isset($students))
    <div id="modal-enroll" onclick="if(event.target === this) closeModal('modal-enroll')"
        class="fixed inset-0 bg-navy-950/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 transition-all">

        <div
            class="bg-white p-8 w-full max-w-md rounded-3xl shadow-2xl border border-slate-200 modal-content transform transition-all scale-95 relative">

            <button type="button" onclick="closeModal('modal-enroll')"
                class="absolute top-6 right-6 p-2 text-slate-400 hover:text-slate-600 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h3 class="font-classic text-2xl text-navy-900 mb-2">
                Matricular Aluno
            </h3>

            <p class="text-slate-400 text-xs mb-6 uppercase font-bold tracking-widest">
                Vincular aluno a uma turma
            </p>

            <form action="{{ route('classrooms.enroll', $classroom) }}" method="POST" class="space-y-5">
                @csrf

                @include('partials.messages.errors')

                {{-- TURMA --}}
                <div>
                    <label
                        class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-wider">
                        Turma
                    </label>

                    <select id="classroom_id" name="classroom_id" required
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-bold text-navy-900 focus:outline-none focus:border-amber-500 transition-colors">

                        <option value="">Selecione a turma...</option>

                        @foreach ($classrooms as $c)
                            <option value="{{ $c->id }}" @if (isset($classroom) && $classroom->id === $c->id) selected @endif>
                                {{ $c->name }} ({{ $c->year }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ALUNO --}}
                <div>
                    <label
                        class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-wider">
                        Aluno
                    </label>

                    <select name="student_id" id="student_id" required
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-bold text-navy-900 focus:outline-none focus:border-amber-500 transition-colors">

                        <option value="">Escolha um aluno...</option>

                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-gold-500 text-navy-900 font-black py-4 rounded-xl uppercase tracking-widest hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/20">
                        Confirmar Matrícula
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
