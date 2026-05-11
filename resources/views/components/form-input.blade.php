@props(['name', 'label', 'type' => 'text', 'value' => ''])

<div class="space-y-1 w-full">
    <label for="{{ $name }}"
        class="text-[11px] font-bold uppercase tracking-[0.1em] text-slate-500 dark:text-slate-400">
        {{ $label }}
    </label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'w-full bg-white dark:bg-navy-900 border-2 ' . ($errors->has($name) ? 'border-gold-600' : 'border-slate-100 dark:border-slate-800') . ' rounded-xl px-4 py-3 focus:border-gold-500 focus:ring-0 transition-all outline-none text-slate-700 dark:text-slate-200 font-medium']) }}>
    @error($name)
        <p class="text-[10px] font-bold text-gold-600 uppercase tracking-tighter">{{ $message }}</p>
    @enderror
</div>
