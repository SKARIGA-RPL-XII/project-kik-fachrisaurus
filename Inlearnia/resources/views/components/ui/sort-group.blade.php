@props([
    'options' => [
        'semua' => 'Semua', 
        'terbaru' => 'Terbaru', 
        'terlama' => 'Terlama', 
        'siswa' => 'Siswa'
    ],
    'default' => 'semua'
])

<div class="flex bg-white rounded-[5px] border border-gray-200 h-[35px] items-center overflow-hidden p-[2px]">
    @foreach($options as $value => $label)
        <label class="cursor-pointer h-full relative">
            {{-- Radio Input (Hidden) --}}
            <input type="radio" 
                   name="sort" 
                   value="{{ $value }}" 
                   class="peer sr-only"
                   {{ request('sort', $default) == $value ? 'checked' : '' }}>

            {{-- Visual Button --}}
            {{-- Style berubah otomatis berdasarkan state peer-checked --}}
            <div class="px-4 h-full flex items-center justify-center text-sm font-medium transition-all duration-200 rounded-[3px]
                        text-gray-500 hover:bg-gray-100 hover:text-[#00A79D]
                        peer-checked:bg-[#00A79D] peer-checked:text-white peer-checked:shadow-sm">
                {{ $label }}
            </div>
        </label>
    @endforeach
</div>