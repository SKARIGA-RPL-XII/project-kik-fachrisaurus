<div class="flex items-center gap-2">
    <input type="hidden" id="hiddenSearchInput" name="search" value="{{ request('search') }}">

    <div class="flex bg-white rounded-[5px] border border-gray-200 h-[35px] items-center overflow-hidden p-[2px]">
        @php $sort = request('sort', 'semua'); @endphp
        
        @foreach(['semua' => 'Semua', 'terbaru' => 'Terbaru', 'terlama' => 'Terlama', 'siswa' => 'Siswa'] as $val => $label)
            <button type="submit" name="sort" value="{{ $val }}" 
                class="px-4 h-full text-sm font-medium transition-all duration-200 {{ $sort == $val ? 'bg-[#00A79D] text-white rounded-[5px] shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-[#00A79D]' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>
</div>