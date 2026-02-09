{{-- Hapus div luar yang berisi mb-10 --}}
@props(['items'])

<div class="inline-flex items-center gap-2 bg-white px-[15px] h-[40px] rounded-[10px] shadow-sm border border-slate-50">
    <div class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px] text-[#044153]" fill="currentColor" viewBox="0 0 24 24">
            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
        </svg>
        <a href="{{ route('dashboard') }}" class="text-[15px] font-medium text-[#044153]">Beranda</a>
    </div>

    @foreach($items as $item)
        <div class="flex items-center gap-2">
            <span class="text-slate-300 text-[15px] font-light">/</span>
            @if(isset($item['url']) && $item['url'])
                <a href="{{ $item['url'] }}" class="text-[15px] font-medium text-[#044153]">{{ $item['label'] }}</a>
            @else
                <span class="text-[15px] font-medium text-[#044153]">{{ $item['label'] }}</span>
            @endif
        </div>
    @endforeach
</div>