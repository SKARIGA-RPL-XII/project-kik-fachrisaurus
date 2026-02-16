@props(['label', 'value', 'color'])

@php
    $colors = [
        'orange' => ['bg' => 'bg-[#FFF4E8]', 'text' => 'text-[#FF9933]', 'circle' => 'bg-[#FF9933]/10'],
        'teal'   => ['bg' => 'bg-[#E6F6F5]', 'text' => 'text-[#00A79D]', 'circle' => 'bg-[#00A79D]/10'],
        'purple' => ['bg' => 'bg-[#F1E9FF]', 'text' => 'text-[#8E59FF]', 'circle' => 'bg-[#8E59FF]/10'],
    ];
    $c = $colors[$color] ?? $colors['orange'];
@endphp

<div class="w-[80px] h-[70px] {{ $c['bg'] }} rounded-[7px] flex flex-col items-center justify-between py-2 overflow-hidden relative">
    <div class="w-[60px] h-[60px] rounded-full {{ $c['circle'] }} flex items-center justify-center -mt-6">
        <span class="text-[22px] font-semibold {{ $c['text'] }} mt-3">{{ $value }}</span>
    </div>
    <span class="text-[13px] font-medium {{ $c['text'] }} opacity-80 leading-none relative z-10">{{ $label }}</span>
</div>