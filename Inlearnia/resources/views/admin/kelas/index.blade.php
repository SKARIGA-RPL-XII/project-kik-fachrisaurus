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
        
        {{-- PENGGUNAAN KOMPONEN BARU --}}
        <x-ui.title text="List Kelas">
            {{-- Kita masukkan Count-nya lewat sini (Slot) --}}
            <span class="text-[20px] font-normal text-[#092C4C] opacity-50">
                {{ $classes->count() }} Data
            </span>
        </x-ui.title>

        <x-ui.button-create href="{{ route('admin.kelas.create') }}" label="Buat Kelas" />
    </div>

    {{-- GARIS PEMBATAS --}}
    <hr class="border-t border-[#D9D9D9] border-[1px] opacity-70 mb-8">

    {{-- Search & Sort Wrapper --}}
    <form action="{{ route('admin.kelas.index') }}" method="GET" class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <x-ui.search-bar placeholder="Telusuri..." />
        <x-ui.sort-group />
    </form>

    {{-- Grid Kelas --}}
    <div id="kelasResultContainer" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-[25px] mt-[35px] transition-opacity duration-200 pb-10">
        @foreach($classes as $class)
            <x-ui.class-card :class="$class" />
        @endforeach

        @if($classes->isEmpty())
            <div class="col-span-full flex flex-col items-center justify-center py-20">
                <p class="text-gray-400 font-medium">Data kelas tidak ditemukan.</p>
            </div>
        @endif
    </div>

    {{-- Script JavaScript (Keep this here for page-specific logic like Live Search) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Trigger SweetAlert2 Toast
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
                function toggleClearBtn() {
                    if (searchInput.value.length > 0) {
                        clearSearchBtn.classList.remove('hidden');
                    } else {
                        clearSearchBtn.classList.add('hidden');
                    }
                }
                toggleClearBtn();
                searchInput.addEventListener('input', toggleClearBtn);
                clearSearchBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    if(hiddenSearchInput) hiddenSearchInput.value = '';
                    toggleClearBtn();
                    searchInput.dispatchEvent(new Event('input'));
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

        // 4. Dropdown Logic Global
        function toggleDropdown(button) {
            const dropdown = button.nextElementSibling;
            const isHidden = dropdown.classList.contains('hidden');
            document.querySelectorAll('.dropdown-menu').forEach(menu => { menu.classList.add('hidden'); });
            if (isHidden) { dropdown.classList.remove('hidden'); } else { dropdown.classList.add('hidden'); }
        }

        window.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-container')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => { menu.classList.add('hidden'); });
            }
        });
    </script>
@endsection