@props([
    'id',
    'title',
    'description',
    'iconBg' => '#0F9E8A',
    'buttonColor' => 'bg-indigo-600 hover:bg-indigo-700',
])

<div id="{{ $id }}" class="hidden fixed inset-0 z-[999]">

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm"
        onclick="toggleModal('{{ $id }}')"></div>

    {{-- Content --}}
    <div class="relative flex min-h-full items-center justify-center p-4">
        <div class="bg-white rounded-[20px] w-full max-w-[700px] p-6 shadow-lg">

            {{-- Header --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 text-white flex items-center justify-center rounded-[10px]"
                    style="background-color: {{ $iconBg }}">
                    {{ $icon }}
                </div>
                <div>
                    <h2 class="text-[15px] font-semibold text-[#092C4C]">{{ $title }}</h2>
                    <p class="text-[12px] text-slate-400">{{ $description }}</p>
                </div>
            </div>

            <hr class="border-slate-200 mb-5">

            {{-- FORM SLOT --}}
            {{ $slot }}

        </div>
    </div>
</div>