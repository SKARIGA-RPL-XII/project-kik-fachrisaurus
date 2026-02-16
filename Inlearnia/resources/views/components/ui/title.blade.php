@props(['text'])

<div class="flex items-center gap-2">
    {{-- Judul Utama --}}
    <h1 class="text-[20px] font-bold text-[#092C4C]">{{ $text }}</h1>

    {{-- Slot: Area bebas untuk menaruh jumlah data, badge, dll --}}
    {{ $slot }}
</div>