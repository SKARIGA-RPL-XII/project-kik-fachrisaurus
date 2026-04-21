@extends('layouts.app')

@section('content')
    <x-sidebar />

    <div class="flex justify-between items-center mb-8">
        <div class="flex-1">
            <x-breadcrumb :items="[['label' => 'Kelas Saya', 'url' => null]]" />
        </div>
        <x-calendar />
    </div>

    <div class="flex justify-between items-center mt-[40px] mb-[25px]">
        <x-ui.title text="Kelas Saya">
            <span class="text-[20px] font-normal text-[#092C4C] opacity-50">
                {{ $classes->count() }} Data
            </span>
        </x-ui.title>
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
        @forelse($classes as $class)
            <div class="bg-white rounded-[15px] shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300 relative flex flex-col overflow-hidden group">
                
                {{-- Cover Image --}}
                <div class="w-full aspect-video border-b border-[#D9D9D9]/70 relative overflow-hidden mb-[20px]">
                    @if($class->logo)
                        <img src="{{ asset('storage/' . $class->logo) }}" alt="Logo" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    @else
                        <div class="w-full h-full bg-[#F8F9FA] flex items-center justify-center">
                             <img src="https://img.freepik.com/free-vector/gradient-abstract-wireframe-background_23-2149009903.jpg" 
                                  class="w-full h-full object-cover opacity-80 transition-transform duration-500 group-hover:scale-110">
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="px-[15px] pb-[15px] flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-1">
                        <div class="w-[75%]">
                            <h3 class="text-[18px] font-bold text-[#2d3748] leading-tight line-clamp-1 mb-1">
                                {{ $class->name }}
                            </h3>
                            {{-- Tambahan informasi nama pengajar untuk siswa --}}
                            <p class="text-[12px] text-gray-500 line-clamp-1">
                                Pengajar: {{ $class->teacher->name ?? 'Tidak diketahui' }}
                            </p>
                        </div>
                        <span class="text-[10px] font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">
                           {{ $class->subject->name ?? 'Umum' }}
                        </span>
                    </div>

                    <div class="flex justify-between mt-[20px] mb-[30px]">
                        <x-ui.stat-badge label="Siswa" :value="$class->students_count" color="orange" />
                        <x-ui.stat-badge label="Pertemuan" :value="$class->meetings_count" color="teal" />
                        <x-ui.stat-badge label="Info" :value="$class->announcements_count" color="purple" />
                    </div>

                    <div class="mt-auto">
                        {{-- Route diubah ke siswa.kelas.show --}}
                        <a href="{{ route('student.kelas.show', $class->id) }}" class="flex items-center justify-center w-full h-[40px] rounded-[8px] bg-[#00A79D] text-white text-sm font-medium hover:bg-[#008f87] transition-all shadow-sm shadow-teal-100">
                            Masuk Kelas
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-20">
                <p class="text-gray-400 font-medium">Anda belum tergabung dalam kelas manapun.</p>
            </div>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
@endsection