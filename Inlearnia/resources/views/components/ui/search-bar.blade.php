@props(['placeholder' => 'Telusuri...'])

<div class="relative w-full md:w-[380px] h-[40px]">
    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
    </span>
    
    <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="{{ $placeholder }}" autocomplete="off"
           class="w-full h-full pl-12 pr-10 rounded-[10px] border border-gray-200 text-sm focus:outline-none focus:border-[#00A79D] transition-all">
    
    <button type="button" id="clearSearchBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
</div>