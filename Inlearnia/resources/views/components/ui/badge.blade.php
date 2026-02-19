@props(['color' => 'primary', 'label'])

@php
    $colors = [
        'success' => 'bg-emerald-100 text-emerald-600',
        'info'    => 'bg-blue-100 text-blue-600',
        'warning' => 'bg-amber-100 text-amber-600',
        'danger'  => 'bg-red-100 text-red-600',
        'gray'    => 'bg-slate-100 text-slate-600',
        'primary' => 'bg-indigo-100 text-indigo-600',
    ];
    
    $classes = $colors[$color] ?? $colors['gray'];
@endphp

<span class="{{ $classes }} px-3 py-1 rounded-full text-xs font-medium">
    {{ ucwords(strtolower($label)) }}
</span>