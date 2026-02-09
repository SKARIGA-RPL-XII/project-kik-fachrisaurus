<div class="flex items-center gap-3 bg-transparent border-[0.5px] border-[#092C4C] px-[20px] h-[40px] rounded-[10px]">
    {{-- Icon Kalender ukuran 23x23px --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="w-[23px] h-[23px] text-[#092C4C]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
        <line x1="16" x2="16" y1="2" y2="6"/>
        <line x1="8" x2="8" y1="2" y2="6"/>
        <line x1="3" x2="21" y1="10" y2="10"/>
    </svg>
    {{-- Text 16px Regular #092C4C --}}
    <span class="text-[16px] font-normal text-[#092C4C]">
        {{ date('d F Y') }}
    </span>
</div>