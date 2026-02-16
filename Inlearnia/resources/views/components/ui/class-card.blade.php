@props(['class'])

<div class="bg-white rounded-[15px] shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300 relative flex flex-col overflow-hidden">
    
    {{-- GAMBAR --}}
    <div class="w-full aspect-video border-b border-[#D9D9D9]/70 relative group overflow-hidden mb-[20px]">
        @if($class->logo)
            <img src="{{ asset('storage/' . $class->logo) }}" alt="Logo" 
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        @else
            <div class="w-full h-full bg-[#F8F9FA] flex items-center justify-center relative group">
                 <img src="https://img.freepik.com/free-vector/gradient-abstract-wireframe-background_23-2149009903.jpg" 
                      class="w-full h-full object-cover opacity-80 transition-transform duration-500 group-hover:scale-110">
            </div>
        @endif
    </div>

    {{-- KONTEN --}}
    <div class="px-[15px] pb-[15px]">
        
        {{-- Judul & Badge --}}
        <div class="flex justify-between items-start mb-1">
            <h3 class="text-[18px] font-bold text-[#2d3748] leading-tight line-clamp-1 w-[70%]">{{ $class->name }}</h3>
            
            <span class="text-[12px] font-medium text-gray-400 bg-gray-50 px-2 py-1 rounded border border-gray-100">
                @php
                    $kurikulum = is_numeric($class->subject->curriculum ?? '2013') ? 'K-' . ($class->subject->curriculum ?? '2013') : ($class->subject->curriculum ?? '2013');
                @endphp
                {{ $kurikulum }}
            </span>
        </div>

        <p class="text-[12px] text-gray-400 mb-0">
            Pengajar: <span class="text-gray-600">{{ $class->teacher->name ?? 'Belum ada' }}</span>
        </p>

        {{-- STATS --}}
        <div class="flex justify-between mt-[20px] mb-[30px]">
            <x-ui.stat-badge label="Siswa" :value="$class->students_count ?? 0" color="orange" />
            <x-ui.stat-badge label="Pertemuan" :value="$class->meetings_count ?? 0" color="teal" />
            <x-ui.stat-badge label="Info" :value="$class->announcements_count ?? 0" color="purple" />
        </div>

        {{-- TOMBOL AKSI & DETAIL --}}
        <div class="flex justify-between items-center relative z-10">
            
            {{-- Dropdown Aksi --}}
            <div class="relative dropdown-container">
                <button type="button" onclick="toggleDropdown(this)" 
                    class="w-[120px] h-[35px] flex items-center justify-center rounded-[8px] border border-gray-200 text-gray-500 text-sm font-medium hover:bg-gray-50 transition-colors select-none">
                    Aksi
                </button>
                
                <div class="dropdown-menu hidden absolute bottom-full left-0 mb-2 w-[150px] bg-white border border-gray-100 rounded-[8px] shadow-xl overflow-hidden animate-fade-in origin-bottom-left z-50">
                    <a href="{{ route('admin.kelas.edit', $class->id) }}" 
                       class="block px-4 py-2 text-sm text-gray-600 hover:bg-[#E6F6F5] hover:text-[#00A79D] text-left transition-colors">
                       Edit Data
                    </a>
                    <form action="{{ route('admin.kelas.destroy', $class->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Yakin hapus kelas?')" 
                                class="block w-full px-4 py-2 text-sm text-red-500 hover:bg-red-50 text-left transition-colors">
                            Hapus Data
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tombol Detail --}}
            <a href="{{ route('admin.kelas.show', $class->id) }}" class="w-[120px] h-[35px] flex items-center justify-center rounded-[8px] bg-[#00A79D] text-white text-sm font-medium hover:bg-[#008f87] transition-all">
                Detail
            </a>
        </div>

    </div>
</div>