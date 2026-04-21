@props(['href'])

<a href="{{ $href }}" 
   {{ $attributes }}
   class="w-9 h-9 flex items-center justify-center rounded-[8px] bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition shadow-sm"
   title="Edit">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 113 3L7 19l-4 1 1-4 12.5-12.5z"/>
    </svg>
</a>