@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">

            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <p class="text-gold-600 font-bold uppercase tracking-widest text-[10px] mb-2">
                    Configurações Acadêmicas
                </p>
                <h1 class="font-classic text-3xl text-navy-900">
                    Ano Letivo & Bimestre
                </h1>
            </div>

            <form action="{{ route('settings.school.update') }}" method="POST" class="p-8 space-y-6">

                @csrf
                @method('PUT')

                @include('partials.messages.errors')

                {{-- Linha: Ano e Bimestre Ativo --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- ANO LETIVO --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            Ano Letivo Atual
                        </label>
                        <input type="number" name="current_year" value="{{ old('current_year', $setting->current_year) }}"
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 font-bold text-navy-900">
                    </div>

                    {{-- BIMESTRE --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            Bimestre Ativo
                        </label>
                        <select name="active_bimester"
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 outline-none focus:border-gold-500 font-bold text-navy-900">
                            <option value="1" @selected(old('active_bimester', $setting->active_bimester) == 1)>1º Bimestre</option>
                            <option value="2" @selected(old('active_bimester', $setting->active_bimester) == 2)>2º Bimestre</option>
                            <option value="3" @selected(old('active_bimester', $setting->active_bimester) == 3)>3º Bimestre</option>
                            <option value="4" @selected(old('active_bimester', $setting->active_bimester) == 4)>4º Bimestre</option>
                        </select>
                    </div>
                </div>

                <hr class="border-slate-100 my-2">

                {{-- SEÇÃO: INTERVALOS DOS BIMESTRES --}}
                <div class="space-y-5">
                    <h3
                        class="text-[11px] font-black uppercase tracking-widest text-navy-900 border-l-2 border-gold-500 pl-2">
                        Cronograma de Vigência dos Bimestres
                    </h3>

                    @for ($i = 1; $i <= 4; $i++)
                        <div class="p-5 bg-slate-50/50 rounded-2xl border border-slate-100 space-y-4">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                Período do {{ $i }}º Bimestre
                            </span>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-1">Data
                                        de Início</label>
                                    <input type="date" name="bimester_{{ $i }}_start"
                                        value="{{ old('bimester_' . $i . '_start', $setting->{"bimester_{$i}_start"}?->format('Y-m-d')) }}"
                                        class="w-full bg-white border-2 border-slate-100 rounded-xl px-4 py-3 outline-none focus:border-gold-500 font-medium text-sm text-navy-900">
                                </div>
                                <div>
                                    <label
                                        class="block text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-1">Data
                                        de Término</label>
                                    <input type="date" name="bimester_{{ $i }}_end"
                                        value="{{ old('bimester_' . $i . '_end', $setting->{"bimester_{$i}_end"}?->format('Y-m-d')) }}"
                                        class="w-full bg-white border-2 border-slate-100 rounded-xl px-4 py-3 outline-none focus:border-gold-500 font-medium text-sm text-navy-900">
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                {{-- BOTÃO --}}
                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-gold-500 hover:bg-gold-600 text-navy-900 font-black uppercase tracking-widest py-4 rounded-2xl transition-all shadow-lg shadow-gold-500/20">
                        Salvar Configurações
                    </button>
                </div>

            </form>

        </div>

    </div>
@endsection
