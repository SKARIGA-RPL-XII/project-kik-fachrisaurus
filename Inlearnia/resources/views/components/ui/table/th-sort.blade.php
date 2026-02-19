@props([
    'label', 
    'field' => null, // Nama kolom database
])

@php
    // Cek apakah kolom ini sedang di-sort
    $isSorted = request('sort') === $field;
    $direction = request('direction') === 'asc' ? 'desc' : 'asc';
    $url = $field ? request()->fullUrlWithQuery(['sort' => $field, 'direction' => $direction]) : '#';
@endphp

<th {{ $attributes->merge(['class' => 'px-6 py-4 font-semibold']) }}>
    @if($field)
        <a href="{{ $url }}" class="flex items-center gap-2 group cursor-pointer select-none">
            {{ $label }}
            
            <span class="text-slate-400 transition group-hover:text-white">
                @if($isSorted)
                    @if(request('direction') === 'asc')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 15l4-4 4 4"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 9l-4 4-4-4"/></svg>
                    @endif
                @else
                    <svg class="w-4 h-4 opacity-0 group-hover:opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4 4-4"/></svg>
                @endif
            </span>
        </a>
    @else
        {{ $label }}
    @endif
</th>