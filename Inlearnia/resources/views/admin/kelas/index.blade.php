@extends('layouts.app')

@section('content')
    <x-sidebar />

    {{-- Load SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div class="flex-1">
            <x-breadcrumb :items="[['label' => 'List Kelas', 'url' => null]]" />
        </div>
        <x-calendar />
    </div>

    {{-- Judul & Tombol Buat Kelas --}}
    <div class="flex justify-between items-center mt-[40px] mb-[25px]">
        <div class="flex items-center gap-2">
            <h1 class="text-[20px] font-bold text-[#092C4C]">List Kelas</h1>
            <span class="text-[20px] font-normal text-[#092C4C] opacity-50">{{ $classes->count() }} Data</span>
        </div>

        <a href="{{ route('admin.kelas.create') }}" 
           class="h-[35px] px-[15px] bg-[#00A79D] hover:bg-[#008f87] text-white rounded-[8px] text-sm font-medium flex items-center gap-2 transition-colors shadow-sm">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Buat Kelas
        </a>
    </div>

    {{-- GARIS PEMBATAS --}}
    <hr class="border-t border-[#D9D9D9] border-[1px] opacity-70 mb-8">

    {{-- Search & Sort Wrapper --}}
    <form action="{{ route('admin.kelas.index') }}" method="GET" class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        
        {{-- Search Box dengan Tombol Clear (X) --}}
        <div class="relative w-full md:w-[380px] h-[40px]">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            
            <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="Telusuri..." autocomplete="off"
                   class="w-full h-full pl-12 pr-10 rounded-[10px] border border-gray-200 text-sm focus:outline-none focus:border-[#00A79D] transition-all">
            
            {{-- Tombol X (Clear) --}}
            <button type="button" id="clearSearchBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        {{-- Sorting --}}
        <div class="flex items-center gap-2">
            <input type="hidden" id="hiddenSearchInput" name="search" value="{{ request('search') }}">

            <div class="flex bg-white rounded-[5px] border border-gray-200 h-[35px] items-center overflow-hidden p-[2px]">
                @php $sort = request('sort', 'semua'); @endphp
                
                <button type="submit" name="sort" value="semua" 
                    class="px-4 h-full text-sm font-medium transition-all duration-200 {{ $sort == 'semua' ? 'bg-[#00A79D] text-white rounded-[5px] shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-[#00A79D]' }}">
                    Semua
                </button>
                <button type="submit" name="sort" value="terbaru" 
                    class="px-4 h-full text-sm font-medium transition-all duration-200 {{ $sort == 'terbaru' ? 'bg-[#00A79D] text-white rounded-[5px] shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-[#00A79D]' }}">
                    Terbaru
                </button>
                <button type="submit" name="sort" value="terlama" 
                    class="px-4 h-full text-sm font-medium transition-all duration-200 {{ $sort == 'terlama' ? 'bg-[#00A79D] text-white rounded-[5px] shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-[#00A79D]' }}">
                    Terlama
                </button>
                <button type="submit" name="sort" value="siswa" 
                    class="px-4 h-full text-sm font-medium transition-all duration-200 {{ $sort == 'siswa' ? 'bg-[#00A79D] text-white rounded-[5px] shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-[#00A79D]' }}">
                    Siswa
                </button>
            </div>
        </div>
    </form>

    {{-- Grid Kelas --}}
    <div id="kelasResultContainer" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-[25px] mt-[35px] transition-opacity duration-200 pb-10">
        @foreach($classes as $class)
            <div class="bg-white rounded-[15px] shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300 relative flex flex-col overflow-hidden">
                
                {{-- GAMBAR: Rasio 16:9 --}}
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

                    {{-- STATS: Lingkaran Besar (50px), Posisi Agak ke Atas (-mt-2) --}}
                    {{-- STATS --}}
                    <div class="flex justify-between mt-[20px] mb-[30px]">
                        
                        {{-- Siswa --}}
                        <div class="w-[80px] h-[70px] bg-[#FFF4E8] rounded-[7px] flex flex-col items-center justify-between py-2 overflow-hidden relative">
                            {{-- Lingkaran: Tarik ke atas dengan -mt-6 --}}
                            <div class="w-[60px] h-[60px] rounded-full bg-[#FF9933]/10 flex items-center justify-center -mt-6">
                                {{-- Angka: diposisikan agak ke bawah dalam lingkaran karena lingkarannya naik --}}
                                <span class="text-[22px] font-semibold text-[#FF9933] mt-3">{{ $class->students_count ?? 0 }}</span>
                            </div>
                            <span class="text-[14px] font-medium text-[#FF9933] opacity-80 leading-none relative z-10">Siswa</span>
                        </div>
                        
                        {{-- Pertemuan --}}
                        <div class="w-[80px] h-[70px] bg-[#E6F6F5] rounded-[7px] flex flex-col items-center justify-between py-2 overflow-hidden relative">
                             <div class="w-[60px] h-[60px] rounded-full bg-[#00A79D]/10 flex items-center justify-center -mt-6">
                                <span class="text-[22px] font-semibold text-[#00A79D] mt-3">{{ $class->meetings_count ?? 0 }}</span>
                             </div>
                            <span class="text-[13px] font-medium text-[#00A79D] opacity-80 leading-none relative z-10">Pertemuan</span>
                        </div>
                        
                        {{-- Pengumuman --}}
                        <div class="w-[80px] h-[70px] bg-[#F1E9FF] rounded-[7px] flex flex-col items-center justify-between py-2 overflow-hidden relative">
                             <div class="w-[60px] h-[60px] rounded-full bg-[#8E59FF]/10 flex items-center justify-center -mt-6">
                                <span class="text-[22px] font-semibold text-[#8E59FF] mt-3">{{ $class->announcements_count ?? 0 }}</span>
                             </div>
                            <span class="text-[14px] font-medium text-[#8E59FF] opacity-80 leading-none relative z-10">Info</span>
                        </div>
                    </div>

                    {{-- TOMBOL AKSI & DETAIL --}}
                    <div class="flex justify-between items-center relative z-10">
                        
                        {{-- Custom Dropdown Aksi (Click Outside Close Logic) --}}
                        <div class="relative dropdown-container">
                            <button type="button" onclick="toggleDropdown(this)" 
                                class="w-[120px] h-[35px] flex items-center justify-center rounded-[8px] border border-gray-200 text-gray-500 text-sm font-medium hover:bg-gray-50 transition-colors select-none">
                                Aksi
                            </button>
                            
                            {{-- Dropdown Content: Muncul di ATAS --}}
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
                        <a href="{{ route('admin.kelas.edit', $class->id) }}" class="w-[120px] h-[35px] flex items-center justify-center rounded-[8px] bg-[#00A79D] text-white text-sm font-medium hover:bg-[#008f87] transition-all">
                            Detail
                        </a>
                    </div>

                </div>
            </div>
        @endforeach

        @if($classes->isEmpty())
            <div class="col-span-full flex flex-col items-center justify-center py-20">
                <p class="text-gray-400 font-medium">Data kelas tidak ditemukan.</p>
            </div>
        @endif
    </div>

    {{-- Script JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Trigger SweetAlert2 Toast (Pengganti Toast Lama)
            @if(session('success'))
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            // 2. Clear Search Functionality
            const searchInput = document.getElementById('searchInput');
            const clearSearchBtn = document.getElementById('clearSearchBtn');
            const hiddenSearchInput = document.getElementById('hiddenSearchInput');
            
            if(searchInput && clearSearchBtn) {
                // Tampilkan/Sembunyikan tombol X
                function toggleClearBtn() {
                    if (searchInput.value.length > 0) {
                        clearSearchBtn.classList.remove('hidden');
                    } else {
                        clearSearchBtn.classList.add('hidden');
                    }
                }

                // Cek saat load
                toggleClearBtn();

                // Cek saat ngetik
                searchInput.addEventListener('input', toggleClearBtn);

                // Aksi tombol X
                clearSearchBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    if(hiddenSearchInput) hiddenSearchInput.value = '';
                    toggleClearBtn();
                    searchInput.dispatchEvent(new Event('input')); // Trigger update Live Search
                    searchInput.focus();
                });
            }

            // 3. Live Search & Dropdown Logic
            const resultContainer = document.getElementById('kelasResultContainer');
            let debounceTimer;

            if(searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    const query = this.value;
                    if(hiddenSearchInput) hiddenSearchInput.value = query;

                    debounceTimer = setTimeout(() => {
                        const url = new URL(window.location.href);
                        url.searchParams.set('search', query);
                        
                        resultContainer.style.opacity = '0.5';

                        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.getElementById('kelasResultContainer');
                            if(newContent) {
                                resultContainer.innerHTML = newContent.innerHTML;
                            }
                            resultContainer.style.opacity = '1';
                            window.history.pushState({}, '', url);
                        });
                    }, 300);
                });
            }
        });

        // 4. Dropdown Logic Global (Outside Click)
        function toggleDropdown(button) {
            const dropdown = button.nextElementSibling;
            const isHidden = dropdown.classList.contains('hidden');
            
            // Tutup semua dropdown lain
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });

            if (isHidden) {
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }

        window.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-container')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });
    </script>
@endsection