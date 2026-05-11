@props(['type' => 'success'])

@php
    $styles = [
        'success' => 'bg-gold-500/10 border-gold-500/20 text-gold-600',
        'error' => 'bg-slate-200 border-slate-300 text-navy-900', // Nada de vermelho!
    ];
@endphp

<div x-data="{ show: true }" x-show="show" x-transition
    {{ $attributes->merge(['class' => 'p-4 mb-6 rounded-2xl border font-bold text-sm flex items-center justify-between ' . $styles[$type]]) }}>

    <div class="flex items-center">
        @if ($type === 'success')
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
            </svg>
        @else
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" />
            </svg>
        @endif
        {{ $slot }}
    </div>

    <button @click="show = false" class="opacity-50 hover:opacity-100">&times;</button>
</div>
