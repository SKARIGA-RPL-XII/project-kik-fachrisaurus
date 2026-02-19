@extends('layouts.app')

@section('content')
    <x-sidebar />

    <div class="flex justify-between items-center mb-8">
        <div class="flex-1">
            <x-breadcrumb :items="[['label' => 'List Kelas', 'url' => null]]" />
        </div>
        <x-calendar />
    </div>

    <div class="flex justify-between items-center mt-[40px] mb-[25px]">
        <x-ui.title text="List Kelas">
            <span class="text-[20px] font-normal text-[#092C4C] opacity-50">
                {{ $classes->count() }} Data
            </span>
        </x-ui.title>

        <x-ui.button-create href="{{ route('admin.kelas.create') }}" label="Buat Kelas" />
    </div>

    <hr class="border-t border-[#D9D9D9] border-[1px] opacity-70 mb-8">

    <form id="filterForm" method="GET" class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        
        <x-ui.search-bar 
            target="kelasResultContainer" 
            formId="filterForm" 
        />

        <x-ui.sort-group />
    </form>

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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. SweetAlert Toast
            @if(session('success'))
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
            @endif

            // 2. Dropdown Logic (Untuk Class Card)
            // Menangani klik tombol dropdown pada card kelas
            window.toggleDropdown = function(button) {
                const dropdown = button.nextElementSibling;
                const isHidden = dropdown.classList.contains('hidden');
                
                // Tutup semua dropdown lain yang terbuka
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });

                // Toggle dropdown yang diklik
                if (isHidden) {
                    dropdown.classList.remove('hidden');
                }
            }

            // Tutup dropdown jika klik di luar area
            window.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown-container')) {
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                }
            });
        });
    </script>
@endsection