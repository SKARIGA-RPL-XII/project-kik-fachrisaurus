@props(['href', 'label'])

<a href="{{ $href }}" 
   {{ $attributes }}
   class="h-[35px] px-[15px] bg-[#00A79D] hover:bg-[#008f87] text-white rounded-[8px] text-sm font-medium flex items-center gap-2 transition-colors shadow-sm">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
    {{ $label }}
</a>